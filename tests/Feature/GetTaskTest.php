<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetTaskTest extends TestCase
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
}
