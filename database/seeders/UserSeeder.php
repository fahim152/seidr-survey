<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'hotboy@seidrdynamics.com',
            'password' => bcrypt('hotboy@1234'), // Hash the password
        ]);
    }
}

