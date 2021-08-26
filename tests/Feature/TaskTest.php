<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_tasks()
    {
        $user = User::factory()->hasTasks(3)->create();

        $response = $this->actingAs($user)->get('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
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
                ],
            ]);
    }

    public function test_get_task_by_id()
    {
        $user = User::factory()->hasTasks(3)->create();

        $id = $user->tasks->first()->id;

        $response = $this->actingAs($user)->get("/api/tasks/$id");

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
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
                ],
            ]);
    }

    public function test_create_task()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/tasks', [
            "name" => "John Doe's birthday",
            "bucket" => "Birthdays",
            "area" => "Family & friends",
            "description" => "Birthday party is at 20.00",
            "scheduled_for" => "2021-08-25"
        ]);

        $response->assertStatus(200);
    }
}
