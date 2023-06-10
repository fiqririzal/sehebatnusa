<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Fiqri Rizal Zulmi',
            'email' => 'Fiqririzal@gmail.com',
            'password' => Hash::make('admin'),
        ]);
    }
}
