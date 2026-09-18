<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
        'is_primary',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * الحصول على رابط الصورة الصحيح عبر مسار /img/ الذي يتجاوز مشكلة IIS
     * يحول storage/products/xxx.jpg إلى /img/products/xxx.jpg
     */
    public function getImageUrlAttribute(): string
    {
        $path = $this->attributes['image_path'] ?? '';
        // إزالة storage/ من بداية المسار لأن route /img/ بيدور في storage/app/public/
        $cleanPath = preg_replace('#^storage/#', '', $path);
        return url('/img/' . $cleanPath);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
