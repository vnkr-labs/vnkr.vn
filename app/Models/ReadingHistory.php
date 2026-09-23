<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingHistory extends Model
{
    public $timestamps = false;

    protected $table = 'reading_history';

    protected $fillable = ['user_id', 'article_id', 'read_at'];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'article_id');
    }
}
