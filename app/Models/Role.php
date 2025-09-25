<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as CustomRole;

class Role extends CustomRole
{
    use HasFactory;
}
