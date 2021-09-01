<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Modules\Tasks\Repositories\GetTaskRepository;
use Illuminate\Http\Request;

class GetTaskController extends Controller
{
    private $repository;

    public function __construct(GetTaskRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(Request $request)
    {
        $tasks = $this->repository->getAllTasks($request->user());

        return TaskResource::collection($tasks);
    }

    public function getById(Request $request, $id)
    {
        $task = $this->repository->getTaskById($id, $request->user());

        return new TaskResource($task);
    }

    public function getAreas(Request $request)
    {
        $areas = $this->repository->getAreas($request->user());

        return response([
            'areas' => $areas,
        ]);
    }

    public function getOpen(Request $request)
    {
        $tasks = $this->repository->getOpen($request->user());

        return TaskResource::collection($tasks);
    }

    public function getOverdue(Request $request)
    {
        $tasks = $this->repository->getOverdue($request->user());

        return TaskResource::collection($tasks);
    }

    public function getWeek(Request $request)
    {
        $tasks = $this->repository->getWeek($request->user());

        return TaskResource::collection($tasks);
    }
}
