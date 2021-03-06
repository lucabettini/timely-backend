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

    public function getOpen(User $user)
    {
        return $user->tasks()->active()->tracked()->get();
    }

    public function getOverdue(User $user)
    {
        return $user->tasks()->active()->overdue($user->timezone)->get();
    }

    public function getToday(User $user)
    {
        return $user->tasks()
            ->whereDate('scheduled_for', Carbon::today($user->timezone))
            ->get();
    }

    public function getTomorrow(User $user)
    {
        return $user->tasks()
            ->whereDate('scheduled_for', Carbon::tomorrow($user->timezone))
            ->get();
    }

    public function getWeek(User $user)
    {
        return $user->tasks()
            ->whereDate('scheduled_for', '>=', Carbon::today($user->timezone))
            ->whereDate('scheduled_for', '<=', Carbon::today($user->timezone)->addDays(7))
            ->oldest('scheduled_for')
            ->get();
    }

    public function getAreas(User $user)
    {
        return $user->tasks->pluck('area')->unique()->values();
    }

    public function getArea(User $user, $area)
    {
        return $user->tasks
            ->where('area', $area)
            ->groupBy('bucket')
            ->map(function ($bucket) {
                return [
                    'completed' => $bucket->where('completed', true)->count(),
                    'not_completed' => $bucket->where('completed', false)->count()
                ];
            });
    }

    public function getByBucket(User $user, $area, $bucket_name)
    {
        return $user->tasks()
            ->where('area', $area)
            ->where('bucket', $bucket_name)
            ->get();
    }
}
