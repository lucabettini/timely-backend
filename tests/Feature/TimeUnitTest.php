<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TimeUnit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TimeUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_time_unit()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();
        $values = [
            'start_time' => Carbon::now()->format('Y-m-d h:i:s'),
            'end_time' => Carbon::now()->addHour()->format('Y-m-d h:i:s')
        ];

        $response = $this->actingAs($user)->postJson("/api/tasks/$task->id/time-unit", $values);

        $response->assertStatus(200)->assertJson($values);
    }

    public function test_update_time_unit()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();
        $time_unit = TimeUnit::factory()->for($task)->create();
        $values = [
            'start_time' => Carbon::now()->format('Y-m-d h:i:s'),
            'end_time' => Carbon::now()->addHour()->format('Y-m-d h:i:s')
        ];

        $response = $this->actingAs($user)->putJson("/api/time_units/$time_unit->id", $values);
        $response->assertStatus(200)->assertJson($values);
    }
}
