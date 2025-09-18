<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'total_amount' => 'decimal:0',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
