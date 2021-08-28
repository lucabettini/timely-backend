<?php

namespace App\Modules\Tasks\Repositories;

use App\Models\User;

class TaskRepository
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

    public function createTask($values, User $user)
    {
        return $user->tasks()->create($values);
    }

    public function updateTask($values, $task_id, User $user)
    {
        $task = $user->tasks()->findOrFail($task_id);
        $task->update($values);
        return $task;
    }

    public function deleteTask($task_id, User $user)
    {
        $task = $user->tasks()->findOrFail($task_id);

        $task->delete();
    }
}
