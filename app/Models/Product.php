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
 * @property bool $is_incoming
 * @property int $sort
 */
#[Fillable([
    'name', 'slug', 'internal_code', 'active_ingredient', 'description', 'approx_price',
    'brand_id', 'category_id', 'main_image_path', 'is_active', 'is_featured', 'is_incoming', 'sort',
])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_incoming' => 'boolean',
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

    public function scopeIncoming(Builder $query): Builder
    {
        return $query->where('is_incoming', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('name');
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        $like = '%'.$term.'%';

        return $query->where(function (Builder $q) use ($like): void {
            $q->whereLike('name', $like)
                ->orWhereLike('active_ingredient', $like)
                ->orWhereLike('description', $like);
        });
    }

    public function getMainImageUrlAttribute(): string
    {
        return $this->main_image_path
            ? Storage::disk('public')->url($this->main_image_path)
            : '';
    }

    public function getDisplayImageUrlAttribute(): string
    {
        if ($this->is_incoming) {
            return asset('images/products/proximamente.svg');
        }

        return $this->main_image_url;
    }

    public function getHasDisplayImageAttribute(): bool
    {
        return $this->is_incoming || filled($this->main_image_path);
    }

    public function getSocialImageUrlAttribute(): string
    {
        if ($this->is_incoming || blank($this->main_image_path)) {
            return Storage::disk('public')->url('logo_delta.png');
        }

        return $this->main_image_url;
    }

    public function getFormattedPriceAttribute(): string
    {
        return $this->approx_price !== null
            ? 'Bs. '.number_format((float) $this->approx_price, 2, ',', '.')
            : '';
    }
}
