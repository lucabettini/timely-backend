<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    private $task_structure = [
        "id",
        "created_at",
        "updated_at",
        "user_id",
        "name",
        "bucket",
        "area",
        "description",
        "scheduled_for",
        "completed",
        "color"
    ];

    public function test_get_all_tasks()
    {
        $user = User::factory()->hasTasks(3)->create();
        $response = $this->actingAs($user)->get('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => $this->task_structure,
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
        $areas = $tasks->pluck('area');
        $response = $this->actingAs($user)->get("/api/areas");

        $response->assertStatus(200)->assertJson([
            'areas' => $areas->all(),
        ]);
    }

    public function test_create_task()
    {
        $user = User::factory()->create();
        $values = [
            "name" => "John Doe's birthday",
            "bucket" => "Birthdays",
            "area" => "Family & friends",
            "description" => "Birthday party is at 20.00",
            "scheduled_for" => "2021-08-25"
        ];
        $response = $this->actingAs($user)->postJson('/api/tasks', $values);

        $response->assertStatus(200)->assertJson($values);
    }

    public function test_update_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();
        $values = [
            "name" => 'changed task',
            "bucket" => 'myBucket',
            "area" => 'myArea',
            "description" => null,
            "completed" => true,
            "scheduled_for" => '2021-08-26'
        ];
        $response = $this->actingAs($user)->putJson("/api/tasks/$task->id", $values);

        $response->assertStatus(200)->assertJson($values);
    }

    public function test_delete_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();
        $response = $this->actingAs($user)->deleteJson("/api/tasks/$task->id");

        $response->assertStatus(200)->assertJson([
            'message' => 'Task deleted successfully'
        ]);
    }
}
