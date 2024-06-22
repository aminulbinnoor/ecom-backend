<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = "rooms";

    /**
     * Get the post that owns the comment.
     */
    public function composition()
    {
        return $this->belongsTo(Composition::class);
    }

    public function products()
    {
      return $this->belongsToMany(Product::class,'product_room');
    }

    public function getFeatureImagesAttribute()
    {
        return json_decode($this->attributes['feature_images']);
    }

    public function getImagesAttribute()
    {
        return json_decode($this->attributes['images']);
    }

}
