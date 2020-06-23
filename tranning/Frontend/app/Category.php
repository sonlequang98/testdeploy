<?php

namespace Frontend;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable=[
    	'category_name','global_display','menu_display','parent_id'
    ];
    public function categorychilds()
    {
        return $this->hasMany('Frontend\Category','parent_id');
    }
}
