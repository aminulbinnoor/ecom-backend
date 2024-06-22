<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BuildingSubcategory extends Model
{
    protected $fillable = 'building_subcategories';

    public function getImagesAttribute()
    {
      if($this->attributes['images']) {
        return json_decode($this->attributes['images']);
      }
    }
}
