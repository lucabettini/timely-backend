<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\RecurringTaskRequest;
use App\Http\Resources\TaskResource;
use App\Modules\Tasks\Repositories\RecurringTaskRepository;
use App\Modules\Tasks\Services\CompleteRecurringTaskService;
use Illuminate\Http\Request;

class RecurringTaskController extends Controller
{

    private $repository;
    private $service;

    public function __construct(RecurringTaskRepository $repository, CompleteRecurringTaskService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function store(RecurringTaskRequest $request, $id)
    {
        $task = $this->repository->createRecurringTask($request->all(), $id, $request->user());

        return new TaskResource($task);
    }

    public function update(RecurringTaskRequest $request, $id)
    {
        try {
            $task = $this->repository->updateRecurringTask($request->all(), $id, $request->user());

            return new TaskResource($task);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], 400);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $this->repository->deleteRecurringTask($id, $request->user());
            return response([
                'message' => 'Recurrence deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], 400);
        }
    }

    public function complete(Request $request, $id)
    {
        try {
            $this->service->complete($request->user(), $id);
            return response([
                'message' => 'Success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
