<?php

namespace App\MOdel;

use Illuminate\Database\Eloquent\Model;

class Appriciation extends Model
{

  protected $table = "appriciations";
  public function getThumbnailImageAttribute()
  {
    if($this->attributes['thumbnail_image']) {
      return json_decode($this->attributes['thumbnail_image']);
    }else{
      return [];
    }

  }
}
