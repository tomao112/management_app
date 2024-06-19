<?php
namespace Database\Factories;

use App\Models\Stamping;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class StampingFactory extends Factory
{
    protected $model = Stamping::class;

    public function definition()
    {
        // ユーザーをランダムに取得
        $user = User::inRandomOrder()->first();

        // 出勤時間を特定の時刻で指定（例: 09:00:00）
        $clockInTime = '09:00:00';

        // 退勤時間を特定の時刻で指定（例: 18:00:00）
        $clockOutTime = '18:00:00';

        // 今日の日付を取得
        $date = Carbon::today()->format('Y-m-d');

        return [
            'user_id' => $user->id,
            'date' => $date,
            'clock_in' => Carbon::parse("$date $clockInTime"),
            'clock_out' => Carbon::parse("$date $clockOutTime"),
        ];
    }
}


