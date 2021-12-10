<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        \App\User::truncate();
        $data = [
            [
                "email" => 'admin@admin.com',
                "password" => bcrypt('password'),
                "first_name" => "Naveed",
                "last_name" => "Hassan",
                "role_code" => 1,
                "email_verified_at" => date('Y-m-d H:i:s'),
            ],
        ];
        foreach ($data as $rec) {
            \App\User::create($rec);
        }
    }
}
