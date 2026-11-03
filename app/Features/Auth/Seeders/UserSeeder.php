<?php

namespace App\Features\Auth\Seeders;

use App\Features\Auth\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => '青木 太郎',
                'email' => 'aoki@example.com',
                'password' => 'l4dKITK6QACH',
            ],
            [
                'name' => '馬場 次郎',
                'email' => 'baba@example.com',
                'password' => '4l10RraKuj7U',
            ],
            [
                'name' => '千浦 晶子',
                'email' => 'chiura@example.com',
                'password' => 'bJTF5nRPdiJ5',
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                ['name' => $user['name'], 'password' => $user['password']],
            );
        }
    }
}
