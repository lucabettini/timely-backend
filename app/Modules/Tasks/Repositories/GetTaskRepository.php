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
        $areas_with_buckets_details = $user->tasks->groupBy('area')->map(function ($area) {
            return $area->groupBy('bucket')->map(function ($bucket) {
                return [
                    'completed' => $bucket->where('completed', true)->count(),
                    'not_completed' => $bucket->where('completed', false)->count()
                ];
            });
        });
        return $areas_with_buckets_details;
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
        return $user->tasks()
            ->whereDate('scheduled_for', '>=', Carbon::today())
            ->whereDate('scheduled_for', '<=', Carbon::today()->addDays(7))
            ->oldest('scheduled_for')
            ->get();
    }

    public function getByBucket(User $user, $area, $bucket_name)
    {
        return $user->tasks()
            ->where('area', $area)
            ->where('bucket', $bucket_name)
            ->get();
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
