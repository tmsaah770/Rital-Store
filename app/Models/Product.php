<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'type',
        'price',
        'quantity',
        'discount',
        'whatsapp_link',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'quantity' => 'integer',
            'discount' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * التصنيف الذي يتبع له المنتج
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function availableColors(): array
    {
        return $this->variants()
            ->whereNotNull('color_name')
            ->select('color_name', 'color_code')
            ->distinct()
            ->get()
            ->toArray();
    }

    public function availableSizes(): array
    {
        return $this->variants()
            ->whereNotNull('size')
            ->select('size')
            ->distinct()
            ->pluck('size')
            ->toArray();
    }
}
