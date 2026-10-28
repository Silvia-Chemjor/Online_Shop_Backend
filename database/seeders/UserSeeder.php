<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=>'Sasha Doe',
            'email'=> 'chemjorsilvia@gmail.com',
            'password' => '123456',
            'is_active'=>'1',
            'role_id'=>'1',
            'email_verified_at'=>now()
        ]);
    }
}
