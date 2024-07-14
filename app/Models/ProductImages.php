<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImages extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'image',
        'display_order'
    ];

    protected $casts = [
        'image' => 'array',
    ];
    function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
