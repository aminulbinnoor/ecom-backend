<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = "admins";

    public function appointments()
    {
      return $this->belongsToMany(Appointment::class,'appointment_admin');
    }
}
