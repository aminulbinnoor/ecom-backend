<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use App\Model\CustomerFeedback;
use App\Model\User;
use Carbon\Carbon;

class Appointment extends Model
{
    protected $table = 'appointments';

    public function user()
    {
      return $this->belongsTo(User::class);
    }

    public function feedbacks()
    {
      return $this->hasMany(CustomerFeedback::class, 'appointment_id','id')->orderBy('created_at','desc');
    }

    public function admins()
    {
      return $this->belongsToMany(Admin::class,'appointment_admin');
    }

    public function getCreatedAtAttribute(){
      $carbondate = Carbon::parse($this->attributes['created_at']);
      $past = $carbondate->format('d-m-Y  h:i A');
      return $past;
    }
}
