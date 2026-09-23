<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by staged-publishing status (từ dashboard links)
        if ($request->filled('status') && in_array($request->status, ['draft', 'review', 'published', 'archived'])) {
            $query->where('status', $request->status);
        }

        $products    = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $statusFilter = $request->input('status');

        return view("admin.product.index", compact("products", "statusFilter"));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view("admin.product.create", compact("categories"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $imgService = new ImageUploadService();
        $fileName   = $imgService->upload($request->file('photo'));
        // Sanitize slug server-side so it is always URL-safe
        $slug = $request->input('slug') ?: $request->input('name', '');
        $request->merge([
            'image' => $fileName,
            'slug'  => Str::slug($slug),
        ]);
        try {
            $product = Product::create($request->only([
                'name', 'slug', 'image', 'description', 'tomtat',
                'category_id', 'video_url', 'stock', 'is_live', 'status',
            ]));
            $this->syncTags($product, $request->input('tags', ''));
            $this->flushProductCache($product);
            return redirect()->route('product.index')->with('success', 'Bài viết đã được tạo thành công.');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => 'Đã xảy ra lỗi khi tạo bài viết.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all(); // Lấy tất cả các danh mục để hiển thị trong dropdown
        return view('admin.product.edit', compact('product', 'categories'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Xác thực dữ liệu yêu cầu
        $request->validate([
            'name'        => 'required|string|max:100',
            'tomtat'      => 'nullable|string',
            'photo'       => 'nullable|image|mimes:jpg,png,jpeg,webp,gif|max:10240',
            'video_url'   => 'nullable|url|max:500',
            'category_id' => 'required|exists:categories,id',
            'slug'        => 'required|string|max:100|unique:products,slug,' . $id,
            'description' => 'nullable|string',
        ]);

        $product = Product::findOrFail($id);

        // Xử lý file ảnh — convert WebP
        if ($request->hasFile('photo')) {
            $imgService = new ImageUploadService();
            $product->image = $imgService->upload($request->file('photo'));
        }

        // Cập nhật các trường khác
        $product->name = $request->input('name');
        $product->tomtat = $request->input('tomtat');
        $product->category_id = $request->input('category_id');
        $product->slug = Str::slug($request->input('slug') ?: $product->slug);
        $product->description = $request->input('description');
        $product->video_url  = $request->input('video_url') ?: null;
        $product->is_live    = $request->boolean('is_live');
        $product->status     = $request->input('status', 'published');
        // is_premium removed — VNKR is completely free
       
        // $product->user_id = auth()->id();

        try {
            $product->save();
            $this->syncTags($product, $request->input('tags', ''));
            $this->flushProductCache($product);
            return redirect()->route('product.index')->with('success', 'Bài viết đã được cập nhật thành công.');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => 'Đã xảy ra lỗi khi cập nhật bài viết.']);
        }
    }





    /**
     * Thùng rác — bài viết đã xóa mềm.
     */
    public function trash()
    {
        $products = Product::onlyTrashed()->orderByDesc('deleted_at')->get();
        return view('admin.product.trash', compact('products'));
    }

    /**
     * Khôi phục bài viết từ thùng rác.
     */
    public function restore($id)
    {
        Product::withTrashed()->where('id', $id)->restore();
        return redirect()->route('product.trash')->with('success', 'Khôi Phục Thành Công');
    }

    /**
     * Xóa vĩnh viễn bài viết.
     */
    public function forceDelete($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        if ($product->image) {
            Storage::disk('public')->delete('images/' . $product->image);
        }
        $product->forceDelete();
        return redirect()->route('product.trash')->with('success', 'Xóa Vĩnh Viễn Thành Công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $this->flushProductCache($product);
            $product->delete();
            return redirect()->route('product.index')->with('success', 'Xóa Thành Công');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Xóa Thất Bại');
        }
    }

    /**
     * Xóa cache liên quan khi bài viết thay đổi.
     */
    private function flushProductCache(Product $product): void
    {
        Cache::forget('home:featured');
        if ($product->category_id) {
            Cache::forget("category:{$product->category_id}");
        }
    }

    /**
     * Đồng bộ tags từ chuỗi phân cách bởi dấu phẩy.
     */
    private function syncTags(Product $product, string $tagString): void
    {
        if (trim($tagString) === '') {
            $product->tags()->detach();
            return;
        }

        $tagIds = collect(explode(',', $tagString))
            ->map(fn($t) => trim($t))
            ->filter()
            ->map(fn($name) => Tag::findOrCreateByName($name)->id)
            ->unique()
            ->toArray();

        $product->tags()->sync($tagIds);
    }
}
