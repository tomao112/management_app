<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Stamping;
use App\Models\BreakTime;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
        // 既存のユーザーを取得
        $users = User::all();

        // 各ユーザーに対してStampingを作成
        foreach ($users as $user) {
            Stamping::factory(10)->create([
                'user_id' => $user->id,
            ])->each(function ($stamping) {
                // 各Stampingに対して複数のBreakTimeを作成
                BreakTime::factory(rand(1, 3))->create(['stamping_id' => $stamping->id]);
            });
        }
    }
}
