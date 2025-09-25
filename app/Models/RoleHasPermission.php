<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleHasPermission extends Model
{
    protected $table = 'role_has_permissions';

    // Jika tidak ada kolom id
    public $incrementing = false;
    protected $primaryKey = null;
    public $timestamps = false;

    protected $fillable = [
        'permission_id',
        'role_id',
    ];

    public function permission(): BelongsTo {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
