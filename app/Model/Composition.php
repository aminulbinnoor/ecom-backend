<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Composition extends Model
{
    protected $table = 'compositions';

    public function getSpecificationAttribute()
    {
        return json_decode($this->attributes['specification']);
    }

    public function getImagesAttribute()
    {
        return json_decode($this->attributes['images']);
    }


    public function rooms(){
      return $this->hasMany(Room::class);
    }
}
