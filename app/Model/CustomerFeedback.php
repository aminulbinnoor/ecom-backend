<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Appointment;
use Carbon\Carbon;

class CustomerFeedback extends Model
{
  protected $table = 'customer_feedback';

  // public function appointments()
  // {
  //     return $this->belongsTo(Appointment::class);
  // }

  public function getCreatedAtAttribute(){
    $carbondate = Carbon::parse($this->attributes['created_at']);
    $past = $carbondate->format('d-m-Y  h:i A');
    return $past;
  }
}
