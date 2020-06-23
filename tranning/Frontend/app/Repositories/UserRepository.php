<?php

namespace Frontend\Repositories;

use Frontend\User;

class UserRepository
{
    public function update($id, $data)
    {
        $date_of_birth = $data->date_of_birth;
        return User::where('id', $id)->update([
            'name' => $data->editName,
            'password' => bcrypt(preg_replace('/\s+/', '', $data->editPassword)), 
            'gender' => $data->gender,
            'date_of_birth' => $date_of_birth,
            'address' => $data->address,
        ]);
    }

    public function create($data)
    {
        $user = new User();
        $dob = $data->dob;
        $user->email = preg_replace('/\s+/', '', $data->email);
        $user->name = $data->name;
        $user->gender = $data->gender;
        $user->role_id = 1;
        $user->password = bcrypt(preg_replace('/\s+/', '', $data->password));
        $user->date_of_birth = $dob;
        if ($user->save()) {
            return $user;
        }
        return false;
    }
}
