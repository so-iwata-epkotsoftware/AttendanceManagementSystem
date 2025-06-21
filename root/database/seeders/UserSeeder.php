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
        // スタッフ
        User::create([
            'name' => 'スタッフユーザー',
            'email' => 'staff@example.com',
            'password' => Hash::make('staff'),
            'company_id' => Company::inRandomOrder()->value('id'),
            'admin_id' => 1,
        ]);

        // ユーザーを作成（それぞれのユーザーは関連する会社を持つ）
        User::factory(100)->create();

    }
}
