<?php

namespace App\Modules\Tasks\Repositories;

use App\Models\RecurringTask;
use App\Models\Task;
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
            throw new Exception('This task is not recurring');
        }

        $task->recurring()->update($values);

        return $user->tasks()->with('recurring')->find($task_id);
    }

    public function deleteRecurringTask($task_id, $user)
    {
        $task = $user->tasks()->with('recurring')->findOrFail($task_id);

        // Throw exception if recurring task does not exists
        if (!$task->recurring) {
            throw new Exception('This task is not recurring');
        }

        $task->recurring()->delete();
    }

    public function addOccurence(Task $task, RecurringTask $recurring_task)
    {
        if (!is_null($recurring_task->occurrences_left)) {
            $recurring_task->occurrences_left = $recurring_task->occurrences_left - 1;
        }
        $recurring_task->task()->associate($task);
        $recurring_task->save();
    }

    public function removeOccurrence(RecurringTask $recurring_task)
    {
        $recurring_task->task()->dissociate();

        $recurring_task->save();
    }
}
