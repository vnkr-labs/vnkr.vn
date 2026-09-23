<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('breaking_news', function (Blueprint $table) {
            $table->id();
            $table->string('title', 500);
            $table->string('url', 500)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'expired_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('breaking_news');
    }
};
