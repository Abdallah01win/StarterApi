<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::firstOrCreate([
            'name' => 'Super Admin',
            'email' => env('SUPERADMIN_EMAIL', 'superadmin@example.com'),
            'password' => Hash::make(env('SUPERADMIN_PASSWORD', '123456789')),
            'role' => UserRole::SUPERADMIN,
        ]);

        $superAdmin->assignRole('super-admin');
    }
}
