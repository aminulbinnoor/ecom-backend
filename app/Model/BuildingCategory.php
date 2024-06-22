<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BuildingCategory extends Model
{
  protected $table = 'building_categories';

  public function getImagesAttribute()
  {
    if($this->attributes['images']) {
      return json_decode($this->attributes['images']);
    }
  }
}
