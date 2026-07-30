<?php

namespace App\Models;

use Database\Factories\NewsFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $excerpt
 * @property string|null $body
 * @property string|null $cover_image_path
 * @property Carbon|null $published_at
 * @property bool $is_active
 * @property int $sort
 */
#[Fillable([
    'title', 'slug', 'excerpt', 'body', 'cover_image_path',
    'published_at', 'is_active', 'sort',
])]
class News extends Model
{
    /** @use HasFactory<NewsFactory> */
    use HasFactory;

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(NewsImage::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->active()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderByDesc('published_at');
    }

    public function getCoverImageUrlAttribute(): string
    {
        return $this->cover_image_path
            ? Storage::disk('public')->url($this->cover_image_path)
            : '';
    }
}
