<?php

namespace App\Modules\Tasks\Repositories;

use App\Models\Task;
use App\Models\User;

class TimeUnitRepository
{
    public function createTimeUnit($values, $task_id, User $user)
    {
        $task = $user->tasks()->findOrFail($task_id);

        return $task->timeUnits()->create($values);
    }

    // public function updateTimeUnit($values, $task_id, User $user)
    // {
    //     $task = $user->time_units()->findOrFail($task_id);
    //     $task->update($values);
    //     return $task;
    // }

    // public function deleteTimeUnit($task_id, User $user)
    // {
    //     $task = $user->time_units()->findOrFail($task_id);

    //     $task->delete();
    // }
}
