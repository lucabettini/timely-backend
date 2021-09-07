<?php

namespace App\Modules\Tasks\Services;

use App\Models\Task;
use App\Models\User;
use App\Modules\Tasks\Repositories\EditTaskRepository;
use App\Modules\Tasks\Repositories\GetTaskRepository;
use App\Modules\Tasks\Repositories\RecurringTaskRepository;
use Carbon\Carbon;
use Exception;

class CompleteRecurringTaskService
{

    private $get_task_repository;
    private $edit_task_repository;
    private $recurring_task_repository;

    public function __construct(
        GetTaskRepository $get_task_repository,
        EditTaskRepository $edit_task_repository,
        RecurringTaskRepository $recurring_task_repository
    ) {
        $this->get_task_repository = $get_task_repository;
        $this->edit_task_repository = $edit_task_repository;
        $this->recurring_task_repository = $recurring_task_repository;
    }


    private function create_date($frequency, $scheduled_for, $interval)
    {
        switch ($frequency) {
            case 'day':
                return $scheduled_for->addDays($interval);
            case 'week':
                return $scheduled_for->addWeeks($interval);
            case 'month':
                return $scheduled_for->addMonths($interval);
            case 'year':
                return $scheduled_for->addYears($interval);
        }
    }

    public function complete(User $user, $task_id)
    {
        // Get task and recurrent task 
        $task = $this->get_task_repository->getTaskById($task_id, $user);
        $recurring_task = $task->recurring;

        if (is_null($recurring_task)) {
            throw new Exception('This task is not recurring');
        }

        // Mark as completed and remove FK
        $this->edit_task_repository->setCompleted($task_id, true, $user);
        $this->recurring_task_repository->removeOccurrence($recurring_task);

        // If this is the last occurrence left, set to 0 and return
        if (!is_null($recurring_task->occurrences_left) and $recurring_task->occurrences_left <= 1) {
            $recurring_task->occurrences_left = 0;
            $recurring_task->save();
            return;
        }

        // If the task should repeat ad infinitum or the end date has yet to come, 
        // create a new task with the right date and set the FK. 
        if (
            is_null($recurring_task->end_date) or
            $recurring_task->end_date->getTimestamp() > Carbon::today()->getTimestamp()
        ) {
            $new_date = $this->create_date($recurring_task->frequency, $task->scheduled_for, $recurring_task->interval);
            $values = [
                'name' => $task->name,
                'bucket' => $task->bucket,
                'area' => $task->area,
                'description' => $task->description,
                'scheduled_for' => $new_date,
                'completed' => false,
                'color'  => $task->color
            ];

            $new_task = $this->edit_task_repository->createTask($values, $user);

            // This will also subtract 1 to occurences_left if specified and set the FK
            $this->recurring_task_repository->addOccurence($new_task, $recurring_task);
        }
    }
}
