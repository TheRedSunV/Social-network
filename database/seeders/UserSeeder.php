<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    const USER_NAME = 'user_name_';
    const PASSWORD = 'password123';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i <= 10; $i++){
            User::factory()->create([
                'id' => $i,
                'name' => self::USER_NAME . $i,
                'email' => self::USER_NAME . $i . '@mail.com',
                'password' => bcrypt(self::PASSWORD),
                'api_token' => Str::random(60),
            ]);
        };
    }
}
