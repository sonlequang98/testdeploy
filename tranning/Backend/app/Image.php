<?php

namespace Backend;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
    	'name','upload_path'
    ];

    public function release_numbers()
    {
    	return $this->hasMany('Backend\Image');
    }
}
