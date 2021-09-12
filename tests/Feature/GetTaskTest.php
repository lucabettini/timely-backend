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

    public function test_get_areas()
    {
        $user = User::factory()->create();
        Task::factory()->for($user)->create([
            'area' => 'area1'
        ]);
        Task::factory()->for($user)->count(3)->create([
            'area' => 'area2'
        ]);
        $response = $this->actingAs($user)->get("/api/areas");

        $response->assertStatus(200)->assertJson([
            'data' => ['area1', 'area2']
        ]);
    }

    public function test_get_area()
    {
        $user = User::factory()->create();
        Task::factory()->for($user)->count(3)->create([
            'area' => 'area1',
            'bucket' => 'bucket1',
            'completed' => true
        ]);
        Task::factory()->for($user)->count(3)->create([
            'area' => 'area1',
            'bucket' => 'bucket2',
            'completed' => false
        ]);
        Task::factory()->for($user)->count(3)->create([
            'area' => 'area2',
        ]);
        $response = $this->actingAs($user)->get("/api/area/?area=area1");

        $response->assertStatus(200)->assertJson([
            'data' => [
                'bucket1' => [
                    'completed' => 3,
                    'not_completed' => 0
                ],
                'bucket2' => [
                    'completed' => 0,
                    'not_completed' => 3
                ],
            ]
        ]);
    }

    public function test_get_tasks_by_bucket()
    {

        $user = User::factory()->create();
        Task::factory()->for($user)->count(3)->create([
            'area' => 'area1',
            'bucket' => 'bucket1',
        ]);
        $response = $this->actingAs($user)->get('/api/bucket/?area=area1&bucket=bucket1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->task_structure,
                ]
            ]);

        foreach ($response['data'] as $task) {
            $this->assertEquals('area1', $task['area']);
            $this->assertEquals('bucket1', $task['bucket']);
        }
    }
}
