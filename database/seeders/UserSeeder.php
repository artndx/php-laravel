<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'moderator',
            'email'=> 'moderator@mail.ru',
            'password' => Hash::make('123456'),
            'role'=> 'moderator',
        ]);

        User::create([
            'name' => 'artur',
            'email'=> 'artur@mail.ru',
            'password' => Hash::make('qwerty'),
            'role'=> 'reader',
        ]);
    }
}
