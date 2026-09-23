<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ý tưởng từ: github/docs src/events
 * Ghi lại hành vi người dùng: page_view, search, link_click, survey.
 * Dữ liệu ẩn danh (không lưu PII) — chỉ dùng để cải thiện nội dung.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            // Loại event: page_view | search | link_click | survey | exit
            $table->string('type', 30)->index();

            // Request context
            $table->string('request_uuid', 36)->index();     // UUID duy nhất mỗi request
            $table->string('path', 500)->nullable()->index(); // URL path (/detail/slug)
            $table->string('referrer', 500)->nullable();      // Trang trước đó
            $table->string('user_agent', 300)->nullable();

            // User (ẩn danh hoá — chỉ lưu hash)
            $table->unsignedBigInteger('user_id')->nullable()->index(); // null nếu guest
            $table->string('session_hash', 64)->nullable();             // hash(session_id)

            // Payload linh hoạt theo từng event type
            $table->string('search_query', 300)->nullable();  // type=search
            $table->string('link_url', 500)->nullable();       // type=link_click
            $table->unsignedBigInteger('article_id')->nullable()->index(); // type=page_view
            $table->tinyInteger('survey_rating')->nullable();  // type=survey (1-5)
            $table->text('survey_comment')->nullable();        // type=survey

            // Performance
            $table->unsignedSmallInteger('time_on_page')->nullable(); // giây, type=exit

            $table->timestamp('created_at')->useCurrent()->index();

            // Không cần updated_at — events là immutable
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
