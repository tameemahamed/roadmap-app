<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::insert([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'role' => 'admin',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10)
        ]);
        for($i=1;$i<6;$i++){
            User::insert([
                'name' => fake()->name(),
                'username' => 'editor'.$i,
                'email' => 'editor'.$i.'@editor.com',
                'email_verified_at' => now(),
                'role' => 'editor',
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10)
            ]);
        }
        User::factory(15)->create();
    }
}
