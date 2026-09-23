<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiveUpdate extends Model
{
    public $timestamps = false;

    protected $fillable = ['article_id', 'admin_id', 'content', 'is_pinned', 'posted_at'];

    protected $casts = [
        'is_pinned' => 'boolean',
        'posted_at' => 'datetime',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'article_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
