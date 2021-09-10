<?php

namespace App\Modules\Tasks\Repositories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TimeUnitRepository
{
    public function getStarted(User $user)
    {
        return $user->time_units()->firstWhere('end_time', '=', null);
    }


    public function createTimeUnit($values, $task_id, User $user)
    {
        $task = $user->tasks()->findOrFail($task_id);

        return $task->timeUnits()->create($values);
    }

    public function updateTimeUnit($values, $id, User $user)
    {
        $time_unit = $user->time_units()->findOrFail($id);
        $time_unit->update($values);
        return $time_unit;
    }

    public function deleteTimeUnit($id, User $user)
    {
        $task = $user->time_units()->findOrFail($id);

        $task->delete();
    }
}
