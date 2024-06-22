<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_categories';

    public function getImagesAttribute()
    {
        return json_decode($this->attributes['images']);
    }
    public function getBannerImageAttribute()
    {
        return json_decode($this->attributes['banner_image']);
    }
}
