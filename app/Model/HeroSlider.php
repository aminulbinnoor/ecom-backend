<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HeroSlider extends Model
{
    protected $table = 'hero_sliders';

    public function getImagesAttribute()
    {
      if($this->attributes['images']) {
        return json_decode($this->attributes['images']);
      }else{
        return [];
      }
    }
}
