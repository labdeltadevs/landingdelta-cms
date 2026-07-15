<?php

namespace App\Models;

use Database\Factories\NewsImageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $news_id
 * @property string $path
 * @property int $sort
 */
#[Fillable(['news_id', 'path', 'sort'])]
class NewsImage extends Model
{
    /** @use HasFactory<NewsImageFactory> */
    use HasFactory;

    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class);
    }

    public function getUrlAttribute(): string
    {
        return $this->path
            ? Storage::disk('public')->url($this->path)
            : '';
    }
}
