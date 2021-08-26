<?php

namespace Database\Factories;

use App\Models\RecurringTask;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecurringTaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RecurringTask::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'frequency' => 'year',
            'interval' => $this->faker->randomNumber(),
            'occurrences' => $this->faker->randomNumber(),
            'end_date' => $this->faker->date(),
        ];
    }
}
