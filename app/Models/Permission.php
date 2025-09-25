<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Permission as CustomPermission;

class Permission extends CustomPermission
{
    use HasFactory;

    public function roleHasPermissions(): HasMany {
        return $this->hasMany(RoleHasPermission::class);
    }
}
