<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'visitors_count',
        'whatsapp_clicks',
        'product_clicks',
        'sales_count',
        'returns_count',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'visitors_count' => 'integer',
            'whatsapp_clicks' => 'integer',
            'product_clicks' => 'integer',
            'sales_count' => 'integer',
            'returns_count' => 'integer',
        ];
    }
}
