<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'Developer',
            'slug' => 'developer',
            'color' => '#19b18e',
            'description' => 'System Developer',
        ]);
        Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'color' => '#9a19b1',
            'description' => 'System Admin',
        ]);
        Role::create([
            'name' => 'Super Uploader',
            'slug' => 'super-uploader',
            'color' => 'rgb(255, 165, 0)',
            'description' => 'Super Uploader',
        ]);
        Role::create([
            'name' => 'Uploader',
            'slug' => 'uploader',
            'color' => 'rgb(255, 165, 0)',
            'description' => 'Uploader',
        ]);
        Role::create([
            'name' => 'Member PRO',
            'slug' => 'member-pro',
            'color' => 'rgb(84, 0, 0)',
            'description' => 'Member PRO',
        ]);
        Role::create([
            'name' => 'Member',
            'slug' => 'member',
            'color' => '#000',
            'description' => 'Member',
        ]);
    }
}
