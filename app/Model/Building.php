<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $table = 'buildings';

    public function getFeatureImageAttribute()
    {
      if($this->attributes['feature_image']) {
        return json_decode($this->attributes['feature_image']);
      }else{
        return [];
      }

    }

    public function getImagesAttribute()
    {
      if($this->attributes['images']) {
        return json_decode($this->attributes['images']);
      }else{
        return [];
      }
    }
}
