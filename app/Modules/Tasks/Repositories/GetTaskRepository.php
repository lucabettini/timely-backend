<?php

namespace App\Modules\Tasks\Repositories;

use App\Models\User;

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
        $tasks = $user->tasks;
        return $tasks->pluck('area');
    }

    public function getOpen(User $user)
    {
        return $user->tasks()->active()->tracked()->get();
    }

    public function getOverdue(User $user)
    {
        return $user->tasks()->overdue()->get();
    }
}
