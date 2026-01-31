<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'category_id',
        'name',
        'price',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    protected $appends = ['stock', 'sizes']; // Ensure these JSON fields exist

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all transactions for this product.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get variants for this product.
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Accessor for total stock.
     */
    public function getStockAttribute()
    {
        return $this->variants()->sum('stock');
    }

    /**
     * Accessor for comma-separated sizes.
     */
    public function getSizesAttribute()
    {
        return $this->variants()->pluck('size')->join(', ');
    }
}
