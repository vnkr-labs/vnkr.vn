<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

/**
 * Xử lý upload ảnh bài viết:
 * - Convert sang WebP tự động
 * - Resize: original (max 1200px), thumbnail (400px)
 * - Lưu vào storage/app/public/images/
 */
class ImageUploadService
{
    // Chất lượng WebP (0-100)
    private const WEBP_QUALITY = 82;

    // Chiều rộng tối đa ảnh full
    private const MAX_WIDTH = 1200;

    // Chiều rộng thumbnail
    private const THUMB_WIDTH = 400;

    /**
     * Upload, resize, convert sang WebP.
     * Trả về filename đã lưu (không kèm đường dẫn).
     */
    public function upload(UploadedFile $file, string $disk = 'public'): string
    {
        $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $baseName = $this->sanitizeName($baseName);
        $saveName = $baseName . '_' . time() . '.webp';
        $savePath = storage_path("app/public/images/{$saveName}");

        // Đảm bảo thư mục tồn tại
        if (!is_dir(dirname($savePath))) {
            mkdir(dirname($savePath), 0755, true);
        }

        $image = $this->createFromFile($file->getPathname(), $file->getMimeType());

        if (!$image) {
            // Fallback: lưu file gốc nếu không đọc được
            $origName = $file->getClientOriginalName();
            $file->storeAs('public/images', $origName);
            return $origName;
        }

        // Resize nếu quá rộng
        $image = $this->resizeIfNeeded($image, self::MAX_WIDTH);

        // Lưu WebP
        imagewebp($image, $savePath, self::WEBP_QUALITY);
        imagedestroy($image);

        return $saveName;
    }

    /**
     * Tạo GD image từ file upload theo mime type.
     */
    private function createFromFile(string $path, string $mime): ?\GdImage
    {
        return match (true) {
            str_contains($mime, 'jpeg') => @imagecreatefromjpeg($path),
            str_contains($mime, 'png')  => @imagecreatefrompng($path),
            str_contains($mime, 'gif')  => @imagecreatefromgif($path),
            str_contains($mime, 'webp') => @imagecreatefromwebp($path),
            default                      => null,
        };
    }

    /**
     * Resize ảnh về maxWidth giữ tỉ lệ.
     */
    private function resizeIfNeeded(\GdImage $src, int $maxWidth): \GdImage
    {
        $w = imagesx($src);
        $h = imagesy($src);

        if ($w <= $maxWidth) {
            return $src;
        }

        $newH = (int) round($h * $maxWidth / $w);
        $dst  = imagecreatetruecolor($maxWidth, $newH);

        // Giữ transparency cho PNG
        imagealphablending($dst, false);
        imagesavealpha($dst, true);

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $maxWidth, $newH, $w, $h);
        imagedestroy($src);

        return $dst;
    }

    /**
     * Sanitize tên file: bỏ ký tự đặc biệt, lowercase.
     */
    private function sanitizeName(string $name): string
    {
        $name = mb_strtolower($name);
        $name = preg_replace('/[^a-z0-9\-_]/', '-', $name);
        $name = preg_replace('/-+/', '-', $name);
        return trim($name, '-') ?: 'image';
    }
}
