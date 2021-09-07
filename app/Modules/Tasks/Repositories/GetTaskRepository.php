<?php

namespace App\Modules\Tasks\Repositories;

use App\Models\User;
use Carbon\Carbon;

class GetTaskRepository
{
    public function getTaskById($id, User $user)
    {
        return $user->tasks()->find($id);
    }

    public function getAllTasks(User $user)
    {
        return $user->tasks;
    }

    public function getAreas(User $user)
    {
        $tasks = $user->tasks->groupBy('area');

        $areas_with_buckets = $tasks->map(function ($area) {
            $buckets = $area->map(function ($task) {
                return $task['bucket'];
            });
            return $buckets->all();
        });

        return $areas_with_buckets;
    }

    public function getOpen(User $user)
    {
        return $user->tasks()->active()->tracked()->get();
    }

    public function getOverdue(User $user)
    {
        return $user->tasks()->active()->overdue()->get();
    }

    public function getWeek(User $user)
    {
        // This will include also overdue tasks
        return $user->tasks()->active()
            ->whereDate('scheduled_for', '<=', Carbon::today()->addDays(7))->get();
    }

    public function getInactiveByArea(User $user, $area)
    {
        return $user->tasks()->where('completed', true)->where('area', $area)->get();
    }

    public function getActiveByArea(User $user, $area)
    {
        return $user->tasks()->active()->where('area', $area)->get();
    }
}
