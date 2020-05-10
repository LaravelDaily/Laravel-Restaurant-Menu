<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                'name'           => 'Admin',
                'email'          => 'admin@admin.com',
                'password'       => '$2y$10$c/yBys1gkgCcwpY7qiyX4..6Q5Cd1J6FtKkr0Bl6FLk16A/0xmnY.',
                'remember_token' => null,
            ],
        ];

        User::insert($users);
    }
}
