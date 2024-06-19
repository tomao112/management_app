<?php

namespace Database\Factories;

use App\Models\BreakTime;
use App\Models\Stamping;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BreakTime>
 */
class BreakTimeFactory extends Factory
{
    protected $model = BreakTime::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = $this->faker->date;
        $startTime = $this->faker->time('H:i:s');
        $endTime = $this->faker->time('H:i:s');

        return [
            'stamping_id' => Stamping::factory(), // Stampingのダミーデータを生成
            'start_time' => Carbon::parse("$date $startTime"),
            'end_time' => Carbon::parse("$date $endTime"),
        ];
    }
}
