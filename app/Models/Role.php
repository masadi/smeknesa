<?php

namespace App\Models;

use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    public $guarded = [];
    
    public function abilities()
    {
        return $this->hasMany(Ability::class, 'role_id', 'id');
    }
}
