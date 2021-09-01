<?php

namespace Tests\Feature;

use App\Models\RecurringTask;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RecurringTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_recurring_task()
    {
        $user = User::factory()->hasTasks()->create();
        $id = $user->tasks->first()->id;

        $response = $this->actingAs($user)->postJson("/api/tasks/$id/recurring", [
            'frequency' => 'year',
            'interval' => 5,
            'occurrences' => 2
        ]);

        $response->assertStatus(200)->assertJsonPath('data.recurring', [
            'frequency' => 'year',
            'interval' => 5,
            'occurrences' => 2,
            'end_date' => null
        ]);
    }

    public function test_update_recurring_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->hasRecurring()->create();
        $id = $task->id;

        $response = $this->actingAs($user)->putJson("/api/tasks/$id/recurring", [
            'frequency' => 'year',
            'interval' => 5,
            'occurrences' => 2,
            'end_date' => null
        ]);

        $response->assertStatus(200)->assertJsonPath('data.recurring', [
            'frequency' => 'year',
            'interval' => 5,
            'occurrences' => 2,
            'end_date' => null
        ]);
    }

    public function test_delete_recurring_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->hasRecurring()->create();
        $id = $task->id;

        $response = $this->actingAs($user)->deleteJson("/api/tasks/$id/recurring");

        $response->assertStatus(200)->assertJson([
            'message' => 'Recurrence deleted successfully'
        ]);
    }
}
