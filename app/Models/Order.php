<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_address',
        'customer_city',
        'total_amount',
        'payment_method',
        'status',
        'failure_reason',
        'failure_details',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'delivered_at' => 'datetime',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusArabicAttribute(): string
    {
        return match ($this->status) {
            'preparing' => 'قيد التجهيز',
            'out_for_delivery' => 'قيد التوصيل',
            'delivered' => 'تم التوصيل',
            'failed' => 'فشل التوصيل',
            default => 'غير محدد',
        };
    }
}
