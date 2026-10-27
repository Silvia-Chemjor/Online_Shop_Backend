<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name'=>'ADMINISTRATOR',
            'description'=>'This is an administrator role'
        ]);
        Role::create([
            'name'=>'CUSTOMER',
            'description'=>'This is an customer role'
        ]);        
    }
}
