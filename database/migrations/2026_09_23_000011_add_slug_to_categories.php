<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Category;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug', 100)->nullable()->unique()->after('name');
        });

        // Tạo slug cho các category đã có
        foreach (Category::withTrashed()->get() as $cat) {
            $base = Str::slug($cat->name);
            $slug = $base;
            $i    = 1;
            while (Category::withTrashed()->where('slug', $slug)->where('id', '!=', $cat->id)->exists()) {
                $slug = $base . '-' . $i++;
            }
            $cat->slug = $slug;
            $cat->saveQuietly();
        }
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
