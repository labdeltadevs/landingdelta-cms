<?php

namespace App\Models;

use Database\Factories\BrochureFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string $file_path
 * @property string|null $line
 * @property bool $is_active
 * @property int $sort
 */
#[Fillable(['title', 'description', 'file_path', 'line', 'is_active', 'sort'])]
class Brochure extends Model
{
    /** @use HasFactory<BrochureFactory> */
    use HasFactory;

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('title');
    }

    public function getFileUrlAttribute(): string
    {
        return $this->file_path
            ? Storage::disk('public')->url($this->file_path)
            : '';
    }
}
