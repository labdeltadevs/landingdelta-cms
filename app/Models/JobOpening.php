<?php

namespace App\Models;

use Database\Factories\JobOpeningFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $title
 * @property string|null $slug
 * @property string $description
 * @property string $valid_from
 * @property string $valid_until
 * @property string|null $image_path
 * @property string|null $application_email
 * @property bool $is_active
 * @property int $sort
 */
#[Fillable(['title', 'slug', 'description', 'application_email', 'valid_from', 'valid_until', 'image_path', 'is_active', 'sort'])]
class JobOpening extends Model
{
    /** @use HasFactory<JobOpeningFactory> */
    use HasFactory;

    protected $casts = [
        'is_active' => 'boolean',
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    protected static function booted(): void
    {
        static::saving(function (JobOpening $job): void {
            if (blank($job->slug)) {
                $job->slug = static::uniqueSlug($job->title);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getNameAttribute(): string
    {
        return $this->title;
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'convocatoria';

        do {
            $slug = $base.'-'.Str::lower(Str::random(4));
        } while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId !== null, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists());

        return $slug;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->active()
            ->where('valid_from', '<=', now()->format('Y-m-d'))
            ->where('valid_until', '>=', now()->format('Y-m-d'));
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderByDesc('created_at');
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image_path
            ? Storage::disk('public')->url($this->image_path)
            : '';
    }

    public function isValidAttribute(): bool
    {
        $today = now()->format('Y-m-d');

        return $this->is_active
            && $this->valid_from <= $today
            && $this->valid_until >= $today;
    }
}
