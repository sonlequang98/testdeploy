<?php

namespace Backend;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
    	'release_number_page','user_admin_page','post_page','member_page','img_page'
    ];
}
