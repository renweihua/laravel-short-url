<?php

namespace App\Modules\ShortUrl\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => '小丑路人',
            'email' => 'admin@小丑路人.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123456'),
            'role' => 'admin',
        ]);
    }
}
