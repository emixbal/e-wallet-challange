<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
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
        $adminRole = Role::where('name', 'Admin')->first();
        $userRole = Role::where('name', 'User')->first();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('aaaaaaaa'),
            'role_id' => $adminRole->id,
        ]);

        User::create([
            'name' => 'User',
            'email' => 'user@email.com',
            'password' => Hash::make('aaaaaaaa'),
            'role_id' => $userRole->id,
        ]);
    }
}
