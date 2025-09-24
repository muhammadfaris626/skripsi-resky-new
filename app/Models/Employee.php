<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'position', 'phone', 'email'
    ];

    public function targets(): HasMany {
        return $this->hasMany(Target::class);
    }

    public function sales(): HasMany {
        return $this->hasMany(Sale::class);
    }
}
