<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // 管理者
        Admin::create([
            'name' => '管理者ユーザー',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'),
            'company_id' => 1,
        ]);
    }
}
