<?php

namespace App\Models;

use Database\Factories\HeroSlideFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $title
 * @property string|null $subtitle
 * @property string $image_path
 * @property string|null $cta_label
 * @property string|null $cta_url
 * @property string|null $slideable_type
 * @property int|null $slideable_id
 * @property bool $is_active
 * @property int $sort
 */
#[Fillable([
    'title', 'subtitle', 'image_path', 'cta_label', 'cta_url',
    'slideable_type', 'slideable_id', 'is_active', 'sort',
])]
class HeroSlide extends Model
{
    /** @use HasFactory<HeroSlideFactory> */
    use HasFactory;

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function slideable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort');
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image_path
            ? Storage::disk('public')->url($this->image_path)
            : '';
    }
}
