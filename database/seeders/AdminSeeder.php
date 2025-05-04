<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::updateOrcreate([
            'name' => 'Admin',
            'email' => 'admin@example.com', // Change this email!
            'password' => Hash::make('admin123'), // Change this password!

        ]);
    }
} 