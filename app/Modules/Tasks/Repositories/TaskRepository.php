<?php

namespace App\Modules\Tasks\Repositories;

use App\Models\Task;
use App\Models\User;

class TaskRepository
{
    public function getTaskById($id, User $user)
    {
        return $user->tasks->find($id);
    }

    public function getAllTasks(User $user)
    {
        return $user->tasks;
    }

    public function createTask($values, User $user)
    {
        return $user->tasks()->create($values);
    }
}
