<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

/**
 * Ý tưởng từ: github/docs src/content-linter
 * Kiểm tra chất lượng bài viết theo bộ quy tắc chuẩn VNKR.
 *
 * Chạy: php artisan vnkr:lint
 *       php artisan vnkr:lint --fix       (tự sửa những gì có thể)
 *       php artisan vnkr:lint --id=5      (kiểm tra 1 bài)
 *       php artisan vnkr:lint --errors    (chỉ hiện lỗi ERROR, bỏ WARNING)
 */
class ContentLint extends Command
{
    protected $signature   = 'vnkr:lint
                                {--id=      : Chỉ kiểm tra article ID cụ thể}
                                {--fix      : Tự động sửa những vấn đề có thể}
                                {--errors   : Chỉ hiện lỗi ERROR, bỏ qua WARNING}
                                {--format=  : Định dạng output: table (mặc định) | json | summary}';
    protected $description = 'Kiểm tra chất lượng bài viết theo chuẩn VNKR (lấy ý tưởng từ github/docs content-linter)';

    /** Quy tắc: [rule_id, severity, mô tả, callback kiểm tra] */
    private array $rules = [];

    public function handle(): int
    {
        $this->registerRules();

        $query = Product::with('category', 'tags')->whereNull('deleted_at');
        if ($id = $this->option('id')) {
            $query->where('id', $id);
        }
        $articles = $query->orderBy('id')->get();

        if ($articles->isEmpty()) {
            $this->warn('Không tìm thấy bài viết nào.');
            return self::SUCCESS;
        }

        $allIssues   = [];
        $fixedCount  = 0;
        $onlyErrors  = $this->option('errors');
        $autoFix     = $this->option('fix');

        foreach ($articles as $article) {
            $issues = $this->lint($article);
            if ($onlyErrors) {
                $issues = array_filter($issues, fn($i) => $i['severity'] === 'ERROR');
            }
            if (!empty($issues)) {
                $allIssues[$article->id] = [
                    'article' => $article,
                    'issues'  => array_values($issues),
                ];
            }
            if ($autoFix) {
                $fixedCount += $this->autoFix($article);
            }
        }

        $format = $this->option('format') ?: 'table';
        match ($format) {
            'json'    => $this->outputJson($allIssues, $articles->count()),
            'summary' => $this->outputSummary($allIssues, $articles->count()),
            default   => $this->outputTable($allIssues, $articles->count()),
        };

        if ($autoFix && $fixedCount > 0) {
            $this->info("✅ Đã tự sửa {$fixedCount} vấn đề.");
        }

        $errorCount = $this->countBySeverity($allIssues, 'ERROR');
        return $errorCount > 0 ? self::FAILURE : self::SUCCESS;
    }

    // ──────────────────────────────────────────────
    // ĐĂNG KÝ QUY TẮC (tương tự github/docs GHD### rules)
    // ──────────────────────────────────────────────

    private function registerRules(): void
    {
        // VKR001 — Tiêu đề
        $this->rules[] = [
            'id'       => 'VKR001',
            'severity' => 'ERROR',
            'desc'     => 'Tiêu đề (name) không được để trống',
            'check'    => fn($a) => empty(trim($a->name ?? '')),
        ];
        $this->rules[] = [
            'id'       => 'VKR002',
            'severity' => 'WARNING',
            'desc'     => 'Tiêu đề quá ngắn (< 10 ký tự) — nên viết đủ ý',
            'check'    => fn($a) => mb_strlen(trim($a->name ?? '')) < 10,
        ];
        $this->rules[] = [
            'id'       => 'VKR003',
            'severity' => 'WARNING',
            'desc'     => 'Tiêu đề quá dài (> 120 ký tự) — ảnh hưởng SEO',
            'check'    => fn($a) => mb_strlen(trim($a->name ?? '')) > 120,
        ];

        // VKR010 — Slug
        $this->rules[] = [
            'id'       => 'VKR010',
            'severity' => 'ERROR',
            'desc'     => 'Slug không được để trống',
            'check'    => fn($a) => empty(trim($a->slug ?? '')),
        ];
        $this->rules[] = [
            'id'       => 'VKR011',
            'severity' => 'ERROR',
            'desc'     => 'Slug chứa ký tự không hợp lệ (phải là a-z0-9-)',
            'check'    => fn($a) => !empty($a->slug) && !preg_match('/^[a-z0-9\-]+$/', $a->slug),
        ];
        $this->rules[] = [
            'id'       => 'VKR012',
            'severity' => 'WARNING',
            'desc'     => 'Slug quá dài (> 80 ký tự) — ảnh hưởng SEO',
            'check'    => fn($a) => mb_strlen($a->slug ?? '') > 80,
        ];

        // VKR020 — Tóm tắt (tomtat / excerpt)
        $this->rules[] = [
            'id'       => 'VKR020',
            'severity' => 'ERROR',
            'desc'     => 'Tóm tắt (tomtat) không được để trống — cần cho SEO meta description',
            'check'    => fn($a) => empty(trim($a->tomtat ?? '')),
        ];
        $this->rules[] = [
            'id'       => 'VKR021',
            'severity' => 'WARNING',
            'desc'     => 'Tóm tắt quá ngắn (< 50 ký tự) — nên ít nhất 1-2 câu',
            'check'    => fn($a) => !empty($a->tomtat) && mb_strlen(trim($a->tomtat)) < 50,
        ];
        $this->rules[] = [
            'id'       => 'VKR022',
            'severity' => 'WARNING',
            'desc'     => 'Tóm tắt quá dài (> 300 ký tự) — nên < 160 ký tự cho SEO tối ưu',
            'check'    => fn($a) => mb_strlen(trim($a->tomtat ?? '')) > 300,
        ];

        // VKR030 — Nội dung chính
        $this->rules[] = [
            'id'       => 'VKR030',
            'severity' => 'ERROR',
            'desc'     => 'Nội dung (description) không được để trống',
            'check'    => fn($a) => empty(trim(strip_tags($a->description ?? ''))),
        ];
        $this->rules[] = [
            'id'       => 'VKR031',
            'severity' => 'WARNING',
            'desc'     => 'Nội dung quá ngắn (< 150 ký tự sau khi bỏ HTML) — bài quá sơ sài',
            'check'    => fn($a) => !empty($a->description)
                && mb_strlen(strip_tags($a->description)) < 150,
        ];

        // VKR040 — Ảnh
        $this->rules[] = [
            'id'       => 'VKR040',
            'severity' => 'WARNING',
            'desc'     => 'Thiếu ảnh đại diện (image) — ảnh hưởng Open Graph khi share',
            'check'    => fn($a) => empty(trim($a->image ?? '')),
        ];
        $this->rules[] = [
            'id'       => 'VKR041',
            'severity' => 'WARNING',
            'desc'     => 'Ảnh vẫn là placeholder (news-placeholder.jpg)',
            'check'    => fn($a) => ($a->image ?? '') === 'news-placeholder.jpg',
        ];

        // VKR050 — Danh mục & Tags
        $this->rules[] = [
            'id'       => 'VKR050',
            'severity' => 'ERROR',
            'desc'     => 'Bài viết chưa được gán danh mục',
            'check'    => fn($a) => empty($a->category_id),
        ];
        $this->rules[] = [
            'id'       => 'VKR051',
            'severity' => 'WARNING',
            'desc'     => 'Bài viết chưa có tag nào — ảnh hưởng SEO + khả năng tìm kiếm',
            'check'    => fn($a) => $a->tags->isEmpty(),
        ];
        $this->rules[] = [
            'id'       => 'VKR052',
            'severity' => 'WARNING',
            'desc'     => 'Bài viết có quá nhiều tag (> 8) — nên giữ dưới 5',
            'check'    => fn($a) => $a->tags->count() > 8,
        ];

        // VKR060 — Tác giả
        $this->rules[] = [
            'id'       => 'VKR060',
            'severity' => 'WARNING',
            'desc'     => 'Bài viết chưa gán tác giả (author_id)',
            'check'    => fn($a) => empty($a->author_id),
        ];

        // VKR070 — Nội dung nguy hiểm
        $this->rules[] = [
            'id'       => 'VKR070',
            'severity' => 'ERROR',
            'desc'     => 'Nội dung chứa script inline (<script) — nguy cơ XSS',
            'check'    => fn($a) => str_contains(strtolower($a->description ?? ''), '<script'),
        ];
        $this->rules[] = [
            'id'       => 'VKR071',
            'severity' => 'WARNING',
            'desc'     => 'Nội dung chứa iframe — cần xem xét bảo mật',
            'check'    => fn($a) => str_contains(strtolower($a->description ?? ''), '<iframe'),
        ];

        // VKR080 — Trạng thái xuất bản
        $this->rules[] = [
            'id'       => 'VKR080',
            'severity' => 'WARNING',
            'desc'     => 'is_published = false nhưng bài vẫn hiển thị (chưa có paywall)',
            'check'    => fn($a) => isset($a->is_published) && !$a->is_published,
        ];

        // VKR081 — Reading time chưa tính
        $this->rules[] = [
            'id'       => 'VKR081',
            'severity' => 'WARNING',
            'desc'     => 'Chưa tính reading_time — chạy --fix để tự sửa',
            'check'    => fn($a) => empty($a->reading_time),
        ];
    }

    // ──────────────────────────────────────────────
    // CHẠY LINT CHO 1 BÀI
    // ──────────────────────────────────────────────

    private function lint(Product $article): array
    {
        $issues = [];
        foreach ($this->rules as $rule) {
            if (($rule['check'])($article)) {
                $issues[] = [
                    'rule'     => $rule['id'],
                    'severity' => $rule['severity'],
                    'desc'     => $rule['desc'],
                ];
            }
        }
        return $issues;
    }

    // ──────────────────────────────────────────────
    // AUTO-FIX (chỉ những gì an toàn)
    // ──────────────────────────────────────────────

    private function autoFix(Product $article): int
    {
        $fixed = 0;

        // Sửa slug chứa ký tự lạ
        if (!empty($article->slug) && !preg_match('/^[a-z0-9\-]+$/', $article->slug)) {
            $article->slug = \Illuminate\Support\Str::slug($article->name) . '-' . $article->id;
            $article->saveQuietly();
            $fixed++;
        }

        // Gán author_id nếu trống — lấy admin đầu tiên
        if (empty($article->author_id)) {
            $adminId = \App\Models\User::where('role', 'admin')->value('id');
            if ($adminId) {
                $article->author_id = $adminId;
                $article->saveQuietly();
                $fixed++;
            }
        }

        // Tính reading_time nếu chưa có
        if (empty($article->reading_time) && !empty($article->description)) {
            $wordCount          = str_word_count(strip_tags($article->description))
                               ?: (int) ceil(mb_strlen(preg_replace('/\s+/', '', strip_tags($article->description))) / 5);
            $article->reading_time = max(1, (int) ceil($wordCount / 200));
            $article->saveQuietly();
            $fixed++;
        }

        return $fixed;
    }

    // ──────────────────────────────────────────────
    // OUTPUT FORMATTERS
    // ──────────────────────────────────────────────

    private function outputTable(array $allIssues, int $total): void
    {
        if (empty($allIssues)) {
            $this->info("✅  Tất cả {$total} bài viết đều hợp lệ — không có vấn đề nào!");
            return;
        }

        $errors   = $this->countBySeverity($allIssues, 'ERROR');
        $warnings = $this->countBySeverity($allIssues, 'WARNING');

        $this->line('');
        $this->line("<fg=cyan>━━━ VNKR Content Linter ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</>");
        $this->line("  Kiểm tra: <fg=white>{$total} bài</> | " .
            "<fg=red>❌ {$errors} ERROR</> | <fg=yellow>⚠ {$warnings} WARNING</>");
        $this->line('');

        foreach ($allIssues as $id => $data) {
            $a = $data['article'];
            $this->line("<fg=white;options=bold>  [#{$id}]</> " .
                \Illuminate\Support\Str::limit($a->name ?? '(no title)', 60));

            $rows = array_map(fn($i) => [
                $i['severity'] === 'ERROR'
                    ? "<fg=red>{$i['rule']}</>"
                    : "<fg=yellow>{$i['rule']}</>",
                $i['severity'] === 'ERROR'
                    ? "<fg=red>ERROR</>"
                    : "<fg=yellow>WARNING</>",
                $i['desc'],
            ], $data['issues']);

            $this->table(['Rule', 'Severity', 'Mô tả'], $rows);
        }

        $this->line('');
        $affected = count($allIssues);
        $this->line("  <fg=white>{$affected}/{$total} bài có vấn đề</> — " .
            "Chạy <fg=green>php artisan vnkr:lint --fix</> để tự sửa.");
        $this->line('');
    }

    private function outputSummary(array $allIssues, int $total): void
    {
        $errors   = $this->countBySeverity($allIssues, 'ERROR');
        $warnings = $this->countBySeverity($allIssues, 'WARNING');
        $clean    = $total - count($allIssues);

        $this->line('');
        $this->line("VNKR Content Lint Summary");
        $this->line("Total articles : {$total}");
        $this->line("Clean          : {$clean}");
        $this->line("With issues    : " . count($allIssues));
        $this->line("Errors         : {$errors}");
        $this->line("Warnings       : {$warnings}");

        // Rule frequency
        $freq = [];
        foreach ($allIssues as $data) {
            foreach ($data['issues'] as $i) {
                $freq[$i['rule']] = ($freq[$i['rule']] ?? 0) + 1;
            }
        }
        arsort($freq);
        $this->line('');
        $this->line("Top rules triggered:");
        foreach (array_slice($freq, 0, 5, true) as $rule => $count) {
            $this->line("  {$rule}: {$count} articles");
        }
    }

    private function outputJson(array $allIssues, int $total): void
    {
        $out = [
            'total'    => $total,
            'clean'    => $total - count($allIssues),
            'errors'   => $this->countBySeverity($allIssues, 'ERROR'),
            'warnings' => $this->countBySeverity($allIssues, 'WARNING'),
            'articles' => [],
        ];
        foreach ($allIssues as $id => $data) {
            $out['articles'][] = [
                'id'     => $id,
                'title'  => $data['article']->name,
                'issues' => $data['issues'],
            ];
        }
        $this->line(json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function countBySeverity(array $allIssues, string $severity): int
    {
        $n = 0;
        foreach ($allIssues as $data) {
            foreach ($data['issues'] as $i) {
                if ($i['severity'] === $severity) {
                    $n++;
                }
            }
        }
        return $n;
    }
}
