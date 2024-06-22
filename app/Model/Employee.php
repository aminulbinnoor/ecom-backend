<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Role;

class Employee extends Model
{
    protected $table = "admins";

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
