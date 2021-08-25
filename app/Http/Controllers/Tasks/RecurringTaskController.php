<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\RecurringTaskRequest;
use App\Modules\Tasks\Repositories\RecurringTaskRepository;

class RecurringTaskController extends Controller
{

    private $repository;

    public function __construct(RecurringTaskRepository $repository)
    {
        $this->repository = $repository;
    }

    public function store(RecurringTaskRequest $request, $id)
    {
        $task = $this->repository->createRecurringTask($request->all(), $id, $request->user());

        return response($task);
    }

    public function update(RecurringTaskRequest $request, $id)
    {
        try {

            $task = $this->repository->updateRecurringTask($request->all(), $id, $request->user());

            return response($task);
        } catch (\Exception $e) {

            return response(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
