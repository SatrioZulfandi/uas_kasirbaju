<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'size',
        'order_id',
        'quantity',
        'total_price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'total_price' => 'decimal:2',
    ];

    /**
     * Get the user who created this transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product for this transaction.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get formatted total price.
     */
    public function getFormattedTotalPriceAttribute()
    {
        return 'Rp ' . number_format((float) $this->total_price, 0, ',', '.');
    }
}
