<?php

namespace Backend;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title', 'content', 'status', 'category_id', 'release_number_id', 'category_parent_id', 'image_id', 'poster',
    ];
    public function release_number()
    {
        return $this->belongsTo('Backend\Release_number');
    }
    public function user()
    {
        return $this->belongsTo('Backend\User');
    }
}
