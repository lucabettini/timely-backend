<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TimeUnit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TimeUnitTest extends TestCase
{
    use RefreshDatabase;


    public function test_get_started_time_unit()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();
        $time_unit = TimeUnit::factory()->for($task)->state([
            'end_time' => null
        ])->create();
        TimeUnit::factory()->for($task)->count(15)->create();
        $response = $this->actingAs($user)->getJson("/api/time_unit");

        $response->assertStatus(200);
        $this->assertEquals($time_unit->id, $response['data']['id']);
    }

    public function test_create_time_unit()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();
        $values = [
            'start_time' => Carbon::now()->floorSecond()->toISOString(),
            'end_time' => Carbon::now()->addHour()->floorSecond()->toISOString()
        ];
        $response = $this->actingAs($user)->postJson("/api/tasks/$task->id/time_unit", $values);

        $response->assertStatus(201)->assertJson(
            fn (AssertableJson $json) =>
            $json->where('data.start_time', $values['start_time'])
                ->where('data.end_time', $values['end_time'])
                ->where('data.task_id', $task->id)
                ->etc()
        );
    }

    public function test_update_time_unit()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();
        $time_unit = TimeUnit::factory()->for($task)->create();
        $values = [
            'start_time' => Carbon::now()->floorSecond()->toISOString(),
            'end_time' => Carbon::now()->addHour()->floorSecond()->toISOString()
        ];
        $response = $this->actingAs($user)->putJson("/api/time_unit/$time_unit->id", $values);

        $response->assertStatus(200)->assertJson(
            fn (AssertableJson $json) =>
            $json->where('data.start_time', $values['start_time'])
                ->where('data.end_time', $values['end_time'])
                ->where('data.task_id', $task->id)
                ->etc()
        );
    }

    public function test_delete_time_unit()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();
        $time_unit = TimeUnit::factory()->for($task)->create();
        $response = $this->actingAs($user)->deleteJson("/api/time_unit/$time_unit->id");

        $response->assertStatus(200)->assertJson([
            'message' => 'Time unit deleted successfully'
        ]);
    }
}
