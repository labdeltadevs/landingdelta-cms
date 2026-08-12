<?php

namespace App\Models;

use App\Models\Brand;
use App\Models\Product;
use Carbon\CarbonInterface;
use Database\Factories\HeroSlideFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $title
 * @property string|null $subtitle
 * @property string|null $tag
 * @property string|null $image_path
 * @property string|null $cta_label
 * @property string|null $cta_url
 * @property string|null $slideable_type
 * @property int|null $slideable_id
 * @property bool $is_active
 * @property Carbon|null $valid_from
 * @property Carbon|null $valid_until
 * @property int $sort
 */
#[Fillable([
    'title', 'subtitle', 'tag', 'image_path', 'cta_label', 'cta_url',
    'slideable_type', 'slideable_id', 'is_active', 'sort', 'valid_from', 'valid_until',
])]
class HeroSlide extends Model
{
    /** @use HasFactory<HeroSlideFactory> */
    use HasFactory;

    protected $casts = [
        'is_active' => 'boolean',
        'valid_from' => 'date:Y-m-d',
        'valid_until' => 'date:Y-m-d',
    ];

    public function slideable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Slides vigentes en la fecha indicada (por defecto, hoy): respeta los
     * rangos valid_from / valid_until y trata las fechas nulas como ilimitadas.
     */
    public function scopeValid(Builder $query, ?CarbonInterface $at = null): Builder
    {
        $at ??= now();

        return $query
            ->where(fn (Builder $q) => $q->whereNull('valid_from')->orWhereDate('valid_from', '<=', $at))
            ->where(fn (Builder $q) => $q->whereNull('valid_until')->orWhereDate('valid_until', '>=', $at));
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort');
    }

    /**
     * Indica si el slide esta vigente en la fecha indicada (por defecto, hoy).
     */
    public function isValid(?CarbonInterface $at = null): bool
    {
        $at ??= now();

        return ($this->valid_from === null || $this->valid_from->lessThanOrEqualTo($at))
            && ($this->valid_until === null || $this->valid_until->greaterThanOrEqualTo($at));
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image_path
            ? Storage::disk('public')->url($this->image_path)
            : '';
    }

    /**
     * Etiqueta mostrada sobre el titulo (por defecto "Aviso").
     */
    public function tagLabel(): string
    {
        $value = trim($this->tag ?? '');

        return $value !== '' ? $value : 'Aviso';
    }

    /**
     * Nombre del producto o marca vinculado (para una UI de confirmacion).
     */
    public function slideableLabel(): ?string
    {
        return $this->slideable?->name;
    }

    /**
     * URL de destino del CTA: prioriza la URL manual; si no, la del
     * producto o marca vinculada por slideable.
     */
    public function ctaLink(): ?string
    {
        if (filled(trim($this->cta_url ?? ''))) {
            return $this->cta_url;
        }

        return match (true) {
            $this->slideable instanceof Product => route('public.products.show', $this->slideable),
            $this->slideable instanceof Brand => route('public.brands.show', $this->slideable),
            default => null,
        };
    }
}
