<?php

use Illuminate\Database\Seeder;

class RolesTabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name'  =>  'member',
        ]);
        DB::table('roles')->insert([
            'name'  =>  'contributor',
        ]);
        DB::table('roles')->insert([
            'name'  =>  'editor',
        ]);
        DB::table('roles')->insert([
            'name'  =>  'admin',
        ]);
    }
}
