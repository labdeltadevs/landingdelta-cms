<?php

namespace App\Models;

use Database\Factories\BranchFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $city
 * @property string $address
 * @property string|null $phone
 * @property string|null $phone_2
 * @property string|null $email
 * @property float|null $lat
 * @property float|null $lng
 * @property bool $is_active
 * @property int $sort
 */
#[Fillable(['name', 'city', 'address', 'phone', 'phone_2', 'email', 'lat', 'lng', 'is_active', 'sort'])]
class Branch extends Model
{
    /** @use HasFactory<BranchFactory> */
    use HasFactory;

    protected $casts = [
        'is_active' => 'boolean',
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('name');
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([$this->address, $this->city]);

        return implode(', ', $parts);
    }
}
