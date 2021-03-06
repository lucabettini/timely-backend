<?php

namespace App\Modules\Tasks\Repositories;

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

    public function editBucketName($old_name, $new_name, $area, User $user)
    {
        DB::table('tasks')
            ->where('user_id', $user->id)
            ->where('bucket', $old_name)
            ->where('area', $area)
            ->update(['bucket' => $new_name]);
    }

    public function deleteByBucket($bucket, $area, User $user)
    {
        DB::table('tasks')
            ->where('user_id', $user->id)
            ->where('area', $area)
            ->where('bucket', $bucket)
            ->delete();
    }

    public function editAreaName($old_name, $new_name, User $user)
    {
        DB::table('tasks')
            ->where('user_id', $user->id)
            ->where('area', $old_name)
            ->update(['area' => $new_name]);
    }
}
