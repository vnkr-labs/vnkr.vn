<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['keyword', 'results_count', 'searched_at'];

    protected $casts = [
        'searched_at' => 'datetime',
    ];
}
