<?php

namespace App\Modules\Tasks\Repositories;

use Exception;

class RecurringTaskRepository
{
    public function createRecurringTask($values, $task_id, $user)
    {

        $task = $user->tasks()->findOrFail($task_id);
        $task->recurring()->create($values);

        return $user->tasks()->with('recurring')->find($task_id);
    }

    public function updateRecurringTask($values, $task_id, $user)
    {
        $task = $user->tasks()->with('recurring')->findOrFail($task_id);

        // Throw exception if recurring task does not exists
        if (!$task->recurring) {
            throw new Exception('This task is not recurring', 400);
        }

        $task->recurring()->update($values);

        return $user->tasks()->with('recurring')->find($task_id);
    }
}
