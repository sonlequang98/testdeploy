<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'adminn@gmail.com',
            'password' => bcrypt('admin'),
            'role_id' => 3
        ]);
    }
}
