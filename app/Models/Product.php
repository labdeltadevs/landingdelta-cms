<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $active_ingredient
 * @property string|null $description
 * @property float|null $approx_price
 * @property int|null $brand_id
 * @property int|null $category_id
 * @property string|null $main_image_path
 * @property bool $is_active
 * @property bool $is_featured
 * @property int $sort
 */
#[Fillable([
    'name', 'slug', 'active_ingredient', 'description', 'approx_price',
    'brand_id', 'category_id', 'main_image_path', 'is_active', 'is_featured', 'sort',
])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'approx_price' => 'decimal:2',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('name');
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        $like = '%'.$term.'%';

        return $query->where(function (Builder $q) use ($like): void {
            $q->where('name', 'like', $like)
                ->orWhere('active_ingredient', 'like', $like)
                ->orWhere('description', 'like', $like);
        });
    }

    public function getMainImageUrlAttribute(): string
    {
        return $this->main_image_path
            ? Storage::disk('public')->url($this->main_image_path)
            : '';
    }

    public function getFormattedPriceAttribute(): string
    {
        return $this->approx_price !== null
            ? 'Bs. '.number_format((float) $this->approx_price, 2, ',', '.')
            : '';
    }
}
