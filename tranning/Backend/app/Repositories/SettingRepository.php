<?php

namespace Backend\Repositories;

use DB;

class SettingRepository implements SettingRepositoryInterface
{
    public function all()
    {
        return DB::table('settings')->take(1)->get();
    }
    public function update($id,$data)
    {
        return DB::table('settings')->where('id',$id)->update([
            'release_number_page'   =>  $data->release_number_page,
            'user_admin_page'   =>  $data->user_admin_page,
            'post_page' =>  $data->post_page,
            'member_page'   =>  $data->member_page,
            'img_page'  => $data->img_page
        ]);
    }
    public function releaseNumberPage()
    {
        return DB::table('settings')->take(1)->pluck('release_number_page')->first();
    }
    public function postPage()
    {
        return DB::table('settings')->take(1)->pluck('post_page')->first();
    }
    public function userAdminPage()
    {
        return DB::table('settings')->take(1)->pluck('user_admin_page')->first();
    }
    public function memberPage()
    {
        return DB::table('settings')->take(1)->pluck('member_page')->first();
    }
    public function imgPage()
    {
        return DB::table('settings')->take(1)->pluck('img_page')->first();
    }

}
