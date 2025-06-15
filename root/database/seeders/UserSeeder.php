<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 管理者
        User::create([
            'name' => '管理者ユーザー',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'),
            'role' => 'admin',
            'company_id' => 1,
        ]);

        // スタッフ
        User::create([
            'name' => 'スタッフユーザー',
            'email' => 'staff@example.com',
            'password' => Hash::make('staff'),
            'role' => 'staff',
            'company_id' => 1,
        ]);

        // ユーザーを作成（それぞれのユーザーは関連する会社を持つ）
        User::factory(100)->create();

    }
}
