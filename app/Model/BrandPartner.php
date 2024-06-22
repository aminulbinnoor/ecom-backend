<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BrandPartner extends Model
{
    protected $table = 'brand_partners';

    public function getImagesAttribute()
    {
      if($this->attributes['images']) {
        return json_decode($this->attributes['images']);
      }
    }
}
