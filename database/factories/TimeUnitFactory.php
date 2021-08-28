<?php

namespace Database\Factories;

use App\Models\TimeUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimeUnitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TimeUnit::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'start_time' => $this->faker->date(),
            'end_time' => $this->faker->date()
        ];
    }
}
