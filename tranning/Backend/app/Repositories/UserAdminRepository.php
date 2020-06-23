<?php

namespace Backend\Repositories;

use Backend\Enums\UserRole;
use Backend\Post;
use Backend\Repositories\SettingRepositoryInterface;
use Backend\Role;
use Backend\User;

class UserAdminRepository implements UserAdminRepositoryInterface
{
    private $setting;
    public function __construct(SettingRepositoryInterface $setting)
    {
        $this->setting = $setting->userAdminPage();
    }
    public function get($user_id)
    {
        return User::find($user_id);
    }

    public function all()
    {
        return User::where('role_id', '<>', UserRole::Member)->orderBy('created_at', 'desc')->paginate($this->setting);
    }

    public function delete($user_id)
    {
        $user = User::find($user_id);
        if ($user->role_id == UserRole::Admin) {
            return false;
        }
        $posts = Post::where('user_id', $user_id)->get();
        foreach ($posts as $post) {
            Post::destroy($post->id);
        }
        return $user->delete();
    }

    public function update($user_id, $user_data)
    {
        return User::where('id', $user_id)->update([
            'name' => $user_data->name,
            'email' => $user_data->email,
            'role_id' => $user_data->role_id,
        ]);
    }
    public function create($user_data)
    {
        $user = new User();
        $user->email = $user_data->email;
        $user->password = bcrypt(preg_replace('/\s+/', '', $user_data->password));
        $user->name = $user_data->name;
        $user->role_id = $user_data->role_id;
        $user->save();
        return $user;
    }
    public function getRoles()
    {
        return Role::where('id', '<>', UserRole::Member)->get();
    }

    public function searchUser($query)
    {
        return User::where('name', 'LIKE', "%$query->name%")
            ->where('email', 'LIKE', "%$query->email%")
            ->where('role_id', 'LIKE', "%$query->role_id%")
            ->where('role_id', '<>', UserRole::Member)
            ->orderBy('created_at', 'desc')
            ->paginate($this->setting)->appends(array('name' => $query->name, 'email' => $query->email, 'role_id' => $query->role_id));
    }

}
