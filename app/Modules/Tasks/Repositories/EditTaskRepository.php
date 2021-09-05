<?php

namespace App\Modules\Tasks\Repositories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EditTaskRepository
{
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

    public function setCompleted($task_id, $boolean, User $user)
    {
        $task = $user->tasks()->findOrFail($task_id);

        $task->completed = $boolean;
        $task->save();
    }

    public function editBucketName($old_name, $new_name, User $user)
    {
        DB::table('tasks')
            ->where('user_id', $user->id)
            ->where('bucket', $old_name)
            ->update(['bucket' => $new_name]);
    }

    public function deleteByBucket($bucket, User $user)
    {
        DB::table('tasks')
            ->where('user_id', $user->id)
            ->where('bucket', $bucket)
            ->delete();
    }
}
