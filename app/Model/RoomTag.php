<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RoomTag extends Model
{
    protected $table = "room_tags";

    public function getImagesAttribute()
    {
        return json_decode($this->attributes['images']);
    }
}
