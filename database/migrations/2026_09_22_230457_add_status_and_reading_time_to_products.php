<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ý tưởng từ: github/docs — Staged publishing workflow
 * Thêm trạng thái xuất bản và reading_time tính tự động.
 * status: draft | review | published | archived
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Staged publishing (thay thế bool is_published)
            $table->string('status', 20)->default('published')->after('is_published')
                  ->comment('draft | review | published | archived');

            // Reading time tính tự động (phút)
            $table->unsignedTinyInteger('reading_time')->default(0)->after('status');

            // Meta SEO riêng cho từng bài (ý tưởng từ docs frontmatter)
            $table->string('meta_title', 255)->nullable()->after('reading_time');
            $table->string('meta_description', 500)->nullable()->after('meta_title');

            // Cho phép comment hay không
            $table->boolean('allow_comments')->default(true)->after('meta_description');

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['status', 'reading_time', 'meta_title', 'meta_description', 'allow_comments']);
        });
    }
};
