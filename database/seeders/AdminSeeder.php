<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Default Laravel admin for /admin/login (guard: admin, table: admins).
     */
    public function run(): void
    {
        Admin::query()->updateOrCreate(
            ['username' => 'admin'],
            ['password' => Hash::make('password')],
        );
    }
}
