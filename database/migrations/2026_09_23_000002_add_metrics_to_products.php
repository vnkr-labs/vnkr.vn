<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('view_count')->default(0)->after('stock');
            $table->unsignedInteger('like_count')->default(0)->after('view_count');
            $table->boolean('is_featured')->default(false)->after('like_count');
            $table->boolean('is_published')->default(true)->after('is_featured');
            $table->timestamp('published_at')->nullable()->after('is_published');
            $table->foreignId('author_id')->nullable()->after('published_at')
                  ->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn(['view_count', 'like_count', 'is_featured', 'is_published', 'published_at', 'author_id']);
        });
    }
};
