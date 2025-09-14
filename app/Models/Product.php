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
        'purchase_price' => 'decimal:0',
        'selling_price' => 'decimal:0',
        'stock' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Accessors
    public function getFormattedPurchasePriceAttribute()
    {
        return number_format($this->purchase_price);
    }

    public function getFormattedSellingPriceAttribute()
    {
        return number_format($this->selling_price);
    }

    public function getProfitMarginAttribute()
    {
        if ($this->purchase_price > 0) {
            return round((($this->selling_price - $this->purchase_price) / $this->purchase_price) * 100);
        }
        return 0;
    }

    public function getProfitAmountAttribute()
    {
        return $this->selling_price - $this->purchase_price;
    }

    public function getTotalValueAttribute()
    {
        return $this->stock * $this->selling_price;
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock == 0) {
            return 'Out of Stock';
        } elseif ($this->stock <= 10) {
            return 'Low Stock';
        }
        return 'In Stock';
    }

    public function getStockStatusColorAttribute()
    {
        if ($this->stock == 0) {
            return 'red';
        } elseif ($this->stock <= 10) {
            return 'yellow';
        }
        return 'green';
    }

    public function getStockStatusBadgeAttribute()
    {
        $color = $this->stock_status_color;
        $status = $this->stock_status;

        $colorClasses = [
            'red' => 'bg-red-100 text-red-800',
            'yellow' => 'bg-yellow-100 text-yellow-800',
            'green' => 'bg-green-100 text-green-800',
        ];

        return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' .
               $colorClasses[$color] . '">' . $status . '</span>';
    }

    // Scopes
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 10);
    }

    public function scopeLowStock($query)
    {
        return $query->whereBetween('stock', [1, 10]);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock', 0);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('product_code', 'like', '%' . $search . '%')
              ->orWhere('product_name', 'like', '%' . $search . '%');
        });
    }
}
