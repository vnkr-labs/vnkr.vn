<?php

namespace App\Services;

/**
 * Ý tưởng từ: github/docs metrics module
 * Tính toán content metrics — reading time, word count.
 */
class ContentMetricsService
{
    // Tốc độ đọc trung bình tiếng Việt ~200 từ/phút
    private const WPM = 200;

    /**
     * Tính reading time (phút) từ HTML content.
     */
    public function readingTime(string $html): int
    {
        $text      = strip_tags($html);
        $wordCount = str_word_count($text) ?: mb_strlen(preg_replace('/\s+/', '', $text)) / 5;
        $minutes   = (int) ceil($wordCount / self::WPM);
        return max(1, $minutes);
    }

    /**
     * Tính và lưu reading_time cho 1 bài.
     */
    public function updateReadingTime(\App\Models\Product $article): void
    {
        $time = $this->readingTime($article->description ?? '');
        if ($article->reading_time !== $time) {
            $article->reading_time = $time;
            $article->saveQuietly();
        }
    }

    /**
     * Tóm tắt metrics của 1 bài (dùng cho admin docstat).
     */
    public function statFor(\App\Models\Product $article): array
    {
        return [
            'id'            => $article->id,
            'title'         => $article->name,
            'url'           => route('detail', $article->slug),
            'view_count'    => $article->view_count ?? 0,
            'like_count'    => $article->like_count ?? 0,
            'comment_count' => $article->comments()->count(),
            'reading_time'  => $article->reading_time ?? $this->readingTime($article->description ?? ''),
            'word_count'    => str_word_count(strip_tags($article->description ?? '')),
            'has_image'     => !empty($article->image) && $article->image !== 'news-placeholder.jpg',
            'has_tags'      => $article->tags()->exists(),
            'has_author'    => !empty($article->author_id),
            'status'        => $article->status ?? 'published',
            'created_at'    => $article->created_at?->format('d/m/Y'),
            'updated_at'    => $article->updated_at?->format('d/m/Y H:i'),
        ];
    }
}
