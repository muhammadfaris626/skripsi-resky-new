<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'date',
        'employee_id',
        'total_amount',
        'payment_method'
    ];

    protected $casts = [
        'date' => 'date',
        'total_amount' => 'decimal:0',
    ];

    public function employee(): BelongsTo {
        return $this->belongsTo(Employee::class);
    }

    public function itemSales(): HasMany
    {
        return $this->hasMany(ItemSale::class);
    }
}
