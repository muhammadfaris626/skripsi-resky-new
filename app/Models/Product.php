<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'product_code',
        'product_name',
        'purchase_price',
        'selling_price',
        'stock'
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'stock' => 'integer'
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Accessors
    public function getProfitAttribute()
    {
        return $this->selling_price - $this->purchase_price;
    }

    public function getProfitMarginAttribute()
    {
        return $this->purchase_price > 0
            ? (($this->selling_price - $this->purchase_price) / $this->purchase_price) * 100
            : 0;
    }

    // Scopes
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '<=', 0);
    }

    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('stock', '<=', $threshold)->where('stock', '>', 0);
    }
}
