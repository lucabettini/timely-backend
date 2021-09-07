<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->text(40),
            'bucket' => $this->faker->text(20),
            'area' => $this->faker->text(20),
            'scheduled_for' => $this->faker->date(),
            'completed' => false,
            'tracked' => true,
            'color' => '#68ba9f'
        ];
    }
}
