<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\ProductCategory;

class Product extends Model
{
    protected $table = "products";

    public function category()
    {
        return $this->hasOne(ProductCategory::class, 'id','product_category_id');
    }

    public function rooms()
    {
      return $this->belongsToMany(Room::class,'product_room');
    }

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

    public function getVariationsAttribute()
    {
      if($this->attributes['variations']) {
        return json_decode($this->attributes['variations']);
      }else{
        return [];
      }
    }

    public function getSpecificationDimensionsAttribute()
    {
      if($this->attributes['specification_dimensions']) {
        return json_decode($this->attributes['specification_dimensions']);
      }else{
        return [];
      }
    }

    public function getSpecificationDetailsAttribute()
    {
      if($this->attributes['specification_details']) {
        return json_decode($this->attributes['specification_details']);
      }else{
        return [];
      }
    }

}
