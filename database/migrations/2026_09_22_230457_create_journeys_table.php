<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ý tưởng từ: github/docs src/journeys
 * Lộ trình đọc có hướng dẫn — giúp người mới đọc bài theo thứ tự logic.
 * Ví dụ: "Nhập môn Web3" → [bài 1, bài 2, bài 3, bài 4]
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journeys', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 200)->unique();
            $table->text('description')->nullable();
            $table->string('icon', 10)->default('📚');       // emoji icon
            $table->string('color', 20)->default('#0A3D62'); // brand color
            $table->unsignedSmallInteger('estimated_minutes')->default(0); // tổng thời gian đọc
            $table->unsignedBigInteger('category_id')->nullable();  // gán vào danh mục
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();   // admin tạo
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
        });

        // Pivot: journey_article (thứ tự các bài trong lộ trình)
        Schema::create('journey_article', function (Blueprint $table) {
            $table->unsignedBigInteger('journey_id');
            $table->unsignedBigInteger('article_id');
            $table->unsignedSmallInteger('order')->default(0); // vị trí trong lộ trình
            $table->string('note', 255)->nullable();           // ghi chú cho bài trong lộ trình

            $table->primary(['journey_id', 'article_id']);
            $table->foreign('journey_id')->references('id')->on('journeys')->cascadeOnDelete();
            $table->foreign('article_id')->references('id')->on('products')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journey_article');
        Schema::dropIfExists('journeys');
    }
};
