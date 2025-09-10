<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Target extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'month', 'sale_target'];

    protected $casts = ['sale_target' => 'decimal:2'];

    public function employee(): BelongsTo {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    // Scope untuk filter
    public function scopeFilterByEmployee($query, $employeeId)
    {
        return $employeeId ? $query->where('employee_id', $employeeId) : $query;
    }

    public function scopeFilterByMonth($query, $month)
    {
        return $month ? $query->where('month', $month) : $query;
    }

    public function scopeFilterByYear($query, $year)
    {
        return $year ? $query->whereYear('month', $year) : $query;
    }

    public function scopeSearch($query, $search)
    {
        return $search ? $query->whereHas('employee', function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        }) : $query;
    }
}
