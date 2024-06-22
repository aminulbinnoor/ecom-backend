<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\OrderProduct;
use Carbon\Carbon;
use App\User;

class Order extends Model
{
    protected $table = "orders";

    public function products()
    {
        return $this->hasMany(OrderProduct::class, 'order_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtAttribute(){
        $carbondate = Carbon::parse($this->attributes['created_at']);
        $past = $carbondate->format('d-m-Y  h:i A');
        return $past;
    }
}
