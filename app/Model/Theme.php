<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Composition;

class Theme extends Model
{
    protected $table = 'themes';

    protected $appends = ['total_looks'];

    public function compositions(){
      return $this->hasMany(Composition::class);
    }

    /**
     * Get the comments for the blog post.
     */

   public function getImagesAttribute()
   {
      return json_decode($this->attributes['images']);
   }


    public function category()
    {
      return $this->belongsTo(Category::class);
    }

    public function getTotalLooksAttribute()
    {
        return Composition::where('theme_id',$this->attributes['id'])->count();
    }
}
