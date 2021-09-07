<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetTaskTest extends TestCase
{
    use RefreshDatabase;

    private $task_structure = [
        "id",
        "name",
        "bucket",
        "area",
        "description",
        "scheduled_for",
        "completed",
        "color",
        "tracked",
        "duration",
        "time_units",
        "recurring"
    ];

    public function test_get_all_tasks()
    {
        $user = User::factory()->hasTasks(3)->create();
        $response = $this->actingAs($user)->get('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->task_structure,
                ]
            ]);
    }

    public function test_get_task_by_id()
    {
        $user = User::factory()->hasTasks(3)->create();
        $id = $user->tasks->first()->id;
        $response = $this->actingAs($user)->get("/api/tasks/$id");

        $response
            ->assertStatus(200)
            ->assertJsonStructure($this->task_structure);
    }

    public function test_get_areas()
    {
        $user = User::factory()->create();
        $tasks = Task::factory()->for($user)->count(5)->create();
        $response = $this->actingAs($user)->get("/api/areas");


        $areas = $tasks->groupBy('area')->map(function ($area) {
            $buckets = $area->map(function ($task) {
                return $task['bucket'];
            });
            return $buckets->all();
        });;
        $response->assertStatus(200)->assertJson([
            'data' => $areas->all(),
        ]);
    }

    public function test_get_open_tasks()
    {
        $user = User::factory()->hasTasks(5)->create();
        $response = $this->actingAs($user)->get("/api/tasks/open");

        $response->assertStatus(200)->assertJsonMissing([
            'completed' => true,
            'tracked' => false,
        ]);
    }

    public function test_get_overdue_tasks()
    {
        $user = User::factory()->hasTasks(20)->create();
        $response = $this->actingAs($user)->get("/api/tasks/overdue");

        $response->assertStatus(200)->assertJsonMissing([
            'completed' => true
        ]);

        $received_date = new DateTime($response['data'][0]['scheduled_for']);
        $this->assertLessThan(Carbon::today()->getTimestamp(), $received_date->getTimestamp());
    }

    public function test_get_inactive_by_area()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->state([
            'completed' => true
        ])->create();

        $response = $this->actingAs($user)->get("/api/tasks/archive/$task->area");

        $response->assertStatus(200)->assertJsonMissing([
            'completed' => false,
        ]);

        $this->assertEquals($task->area, $response['data'][0]['area']);
    }

    public function test_get_active_by_area()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();

        $response = $this->actingAs($user)->get("/api/tasks/active/$task->area");

        $response->assertStatus(200)->assertJsonMissing([
            'completed' => true,
        ]);

        $this->assertEquals($task->area, $response['data'][0]['area']);
    }
}
