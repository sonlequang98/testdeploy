<?php

namespace Backend\Repositories;

use Backend\Enums\UserDefault;
use Backend\Enums\UserRole;
use Backend\Repositories\SettingRepositoryInterface;
use Backend\User;

class MemberRepository implements MemberRepositoryInterface
{
    private $setting;
    public function __construct(SettingRepositoryInterface $setting)
    {
        $this->setting = $setting->memberPage();
    }
    public function get($member_id)
    {
        return User::find($member_id);
    }

    public function all()
    {
        return User::where('role_id', '=', UserRole::Member)->orderBy('created_at', 'desc')->paginate($this->setting);
    }

    public function delete($member_id)
    {
        $user = User::find($member_id);
        return $user->delete();
    }

    public function update($member_id, $member_data)
    {
        return User::where('id', $member_id)->update([
            'name' => $member_data->name,
            'email' => $member_data->email,
            'phone' => $member_data->phone,
            'date_of_birth' => date("Y-m-d", strtotime($member_data->date_of_birth)),
            'active' => $member_data->active,
            'gender' => $member_data->gender,
        'address' => $member_data->address,
        ]);
    }
    public function create($member_data)
    {
        $user = new User();
        $user->name = $member_data->name;
        $user->email = $member_data->email;
        $user->phone = $member_data->phone;
        $user->date_of_birth = date("Y-m-d", strtotime(str_replace('-', '/', $member_data->date_of_birth)));
        $user->gender = $member_data->gender;
        $user->address = $member_data->address;
        $user->active = $member_data->active;
        $user->role_id = UserRole::Member;
        $user->password = bcrypt(UserDefault::password);
        $user->save();
        return $user;
    }

    public function search($query)
    {
        return User::where('name', 'LIKE', "%$query->name%")
            ->where('email', 'LIKE', "%$query->email%")
            ->where('active', 'LIKE', "%$query->active%")
            ->where('role_id', '=', UserRole::Member)
            ->orderBy('created_at','desc')
            ->paginate($this->setting)->appends(array('email' => $query->email, 'active' => $query->active, 'name' => $query->name));
    }
}
