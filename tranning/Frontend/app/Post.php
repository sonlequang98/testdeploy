<?php

namespace Frontend;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
    	'title','content','status','category_id','release_number_id','category_parent_id','image_id','poster'
    ];
    public function release_number()
    {
        return $this->belongsTo('Frontend\Release_number');
    }
    public function user()
    {
        return $this->belongsTo('Frontend\User');
    }
    public function image()
    {
        return $this->belongsTo('Frontend\image');
    }
}
