<?php

namespace Backend\Repositories;

interface SettingRepositoryInterface
{
    public function all();
    public function update($id,$data);
    public function releaseNumberPage();
    public function postPage();
    public function userAdminPage();
    public function memberPage();
    public function imgPage();
}
