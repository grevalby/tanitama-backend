<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Grevalby',
            'email' => 'grevalby@gmail.com',
            'password' => bcrypt('password'),
            'photo' => 'https://storage.googleapis.com/tanitama_bucket/avatar/default_avatar.png'
        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => bcrypt('password'),
            'photo' => 'https://storage.googleapis.com/tanitama_bucket/avatar/default_avatar.png'
        ]);
    }
}
