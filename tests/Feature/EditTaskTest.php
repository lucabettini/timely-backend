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

    public function test_complete_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();
        $response = $this->actingAs($user)->patchJson("/api/tasks/$task->id/complete");

        $response->assertStatus(200)->assertJson([
            'message' => 'Task completed successfully'
        ]);
    }

    public function test_make_incomplete_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->state([
            'completed' => true
        ])->create();
        $response = $this->actingAs($user)->patchJson("/api/tasks/$task->id/incomplete");

        $response->assertStatus(200)->assertJson([
            'message' => 'Task set as incomplete'
        ]);
    }

    public function test_edit_area_name()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->state([
            'area' => 'someArea'
        ])->create();
        $values = [
            'old_name' => 'someArea',
            'new_name' => 'anotherName',
        ];

        $response = $this->actingAs($user)->patchJson("/api/area", $values);
        $task->refresh();

        $this->assertEquals('anotherName', $task->area);
        $response->assertStatus(200)->assertJson([
            'message' => 'Area name changed'
        ]);
    }

    public function test_edit_bucket_name()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->state([
            'bucket' => 'someName',
            'area' => 'someArea'
        ])->create();
        $values = [
            'old_name' => 'someName',
            'new_name' => 'anotherName',
            'area' => 'someArea'
        ];

        $response = $this->actingAs($user)->patchJson("/api/bucket", $values);
        $task->refresh();

        $this->assertEquals('anotherName', $task->bucket);
        $response->assertStatus(200)->assertJson([
            'message' => 'Bucket name changed'
        ]);
    }

    public function test_delete_by_bucket()
    {
        $user = User::factory()->create();
        Task::factory()->for($user)->state([
            'area' => 'someArea',
            'bucket' => 'someName'
        ])->create();

        $response = $this->actingAs($user)->deleteJson("/api/bucket/?area=someArea&bucket=someBucket");

        $response->assertStatus(200)->assertJson([
            'message' => 'Bucket deleted successfully'
        ]);
    }
}
