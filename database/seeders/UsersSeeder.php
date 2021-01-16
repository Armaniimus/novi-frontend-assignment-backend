<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
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
            'password' => password_hash('123', PASSWORD_DEFAULT),
            'role_id' => 1
        ]);

        DB::table('users')->insert([
            'name' => 'user',
            'password' => password_hash('123', PASSWORD_DEFAULT),
            'role_id' => 2
        ]);

        DB::table('users')->insert([
            'name' => 'novi-user',
            'password' => password_hash('novi-frontend', PASSWORD_DEFAULT),
            'role_id' => 3
        ]);

        DB::table('users')->insert([
            'name' => 'admin-user',
            'password' => password_hash('novi-frontend', PASSWORD_DEFAULT),
            'role_id' => 4
        ]);
    }
}
