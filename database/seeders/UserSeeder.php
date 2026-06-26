<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * 3 個 demo 使用者，密碼都是 "password"
     * email_verified_at 一定要設，不然 verified middleware 會擋 /user_profile
     */
    public function run()
    {
        $now = now();

        User::create([
            'name'              => 'Alice',
            'email'             => 'alice@example.com',
            'password'          => Hash::make('password'),
            'email_verified_at' => $now,
        ]);

        User::create([
            'name'              => 'Bob',
            'email'             => 'bob@example.com',
            'password'          => Hash::make('password'),
            'email_verified_at' => $now,
        ]);

        User::create([
            'name'              => 'Charlie',
            'email'             => 'charlie@example.com',
            'password'          => Hash::make('password'),
            'email_verified_at' => $now,
        ]);
    }
}
