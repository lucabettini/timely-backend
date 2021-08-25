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

    public function test_get_all_tasks()
    {
        $user = User::factory()->has(Task::factory()->count(3))->create();


        $response = $this->actingAs($user)->get('/api/tasks');

        $response->assertStatus(200);
    }
}
