<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug', 'status', 'parent_id'];

    /**
     * Tự động tạo slug khi set name nếu slug chưa được set.
     */
    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = $value;
        if (empty($this->attributes['slug'])) {
            $base = Str::slug($value);
            $slug = $base;
            $i    = 1;
            while (self::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
                $slug = $base . '-' . $i++;
            }
            $this->attributes['slug'] = $slug;
        }
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
