<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(['fullname' => 'Admin Admin', 'email' => 'admin@admin.com','email_verified_at' => now(), 'password' => bcrypt('secret'),'remember_token' => Str::random(10),]);
        User::create(['fullname' => 'John Doe', 'email' => 'johndoe@admin.com','email_verified_at' => now(), 'password' => bcrypt('secret'),'remember_token' => Str::random(10),]);
    }
}
