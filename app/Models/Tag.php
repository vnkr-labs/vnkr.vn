<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = ['name', 'slug'];

    public static function findOrCreateByName(string $name): self
    {
        $slug = Str::slug($name);
        return static::firstOrCreate(['slug' => $slug], ['name' => trim($name), 'slug' => $slug]);
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'article_tag', 'tag_id', 'article_id');
    }
}
