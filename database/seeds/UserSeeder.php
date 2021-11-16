<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('slug','developer')->first();
        $user = User::create([
            'name' => 'Developer',
            'username' => 'Developer',
            'email' => 'dev@gmail.com',
            'password' => Hash::make('secret'),
            'avatar' => 'default-avatar.png',
            'email_verified_at' => \Carbon\Carbon::now(),
        ]);
        $user->roles()->sync($role->id);

        $role = Role::where('slug','admin')->first();
        $user = User::create([
            'name' => 'Admin',
            'username' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'avatar' => 'default-avatar.png',
            'email_verified_at' => \Carbon\Carbon::now(),
        ]);
        $user->roles()->sync($role->id);
    }
}
