<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'birthday' => '1990-01-01',
                'gender' => 'male',
                'password' => Hash::make('adminadmin'),
                'profession' => 'Admin',
                'role_id' => $adminRole->id
            ]
        );

        $admin->save();

    }
}
