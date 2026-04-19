<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('SUPER_ADMIN_SAP', '10001')],
            [
                'name' => env('SUPER_ADMIN_NAME', 'Super Admin'),
                'role' => User::ROLE_SUPER_ADMIN,
                'password' => env('SUPER_ADMIN_PASSWORD', 'admin123'),
                'email_verified_at' => now(),
            ]
        );
    }
}
