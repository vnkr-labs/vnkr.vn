<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('users')->cascadeOnDelete();
            $table->text('content');
            $table->boolean('is_pinned')->default(false);
            $table->timestamp('posted_at')->useCurrent();
            $table->index(['article_id', 'posted_at']);
        });

        // Thêm cột is_live vào products để bật/tắt live blog
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_live')->default(false)->after('is_published');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_live');
        });
        Schema::dropIfExists('live_updates');
    }
};
