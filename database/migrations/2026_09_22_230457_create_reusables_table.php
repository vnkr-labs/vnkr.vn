<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ý tưởng từ: github/docs — Reusables & Variables
 * Snippet nội dung tái sử dụng — nhúng vào bài viết bằng {{reusable:slug}}.
 * Ví dụ: {{reusable:canh-bao-dau-tu}} → block cảnh báo dùng lại ở mọi bài Web3.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reusables', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 100)->unique()->index(); // key nhúng: {{reusable:slug}}
            $table->string('title', 200);                  // tên hiển thị trong admin
            $table->text('content');                       // HTML content
            $table->string('type', 30)->default('block');  // block | inline | callout
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reusables');
    }
};
