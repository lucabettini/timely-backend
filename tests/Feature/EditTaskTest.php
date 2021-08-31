<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditTaskTest extends TestCase
{

    use RefreshDatabase;

    public function test_create_task()
    {
        $user = User::factory()->create();
        $values = [
            "name" => "John Doe's birthday",
            "bucket" => "Birthdays",
            "area" => "Family & friends",
            "description" => "Birthday party is at 20.00",
            "scheduled_for" => Carbon::tomorrow()->toISOString()
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
            "scheduled_for" => Carbon::tomorrow()->toISOString()
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
