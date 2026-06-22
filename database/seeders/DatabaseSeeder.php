<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Happy Cake',
            'email' => 'admin@happycake.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        User::create([
            'name' => 'Nguyen Van A',
            'email' => 'user@happycake.com',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);
    }
}
