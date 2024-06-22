<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $table = 'otp';
    protected $fillable = ['phone','otp','verified'];
}
