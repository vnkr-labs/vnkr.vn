<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ý tưởng từ: github/docs src/redirects
 * Quản lý redirect 301/302 trong DB thay vì hardcode trong routes.
 * Admin có thể thêm/xóa redirect mà không cần deploy lại code.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            $table->string('from_path', 500)->unique()->index(); // /bai-viet-cu
            $table->string('to_path', 500);                     // /detail/bai-viet-moi
            $table->unsignedSmallInteger('status_code')->default(301); // 301 | 302
            $table->boolean('is_active')->default(true);
            $table->string('note', 255)->nullable(); // lý do tạo redirect
            $table->unsignedInteger('hit_count')->default(0); // số lần được dùng
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redirects');
    }
};
