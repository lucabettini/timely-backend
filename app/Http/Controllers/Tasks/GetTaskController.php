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

        // We don't return the resource but the task itself 
        // with all the timeUnits eager loaded inside
        return response($task);
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

    public function getToday(Request $request)
    {
        $tasks = $this->repository->getToday($request->user());

        return TaskResource::collection($tasks);
    }

    public function getTomorrow(Request $request)
    {
        $tasks = $this->repository->getTomorrow($request->user());

        return TaskResource::collection($tasks);
    }

    public function getWeek(Request $request)
    {
        $tasks = $this->repository->getWeek($request->user());

        return TaskResource::collection($tasks);
    }

    public function getAreas(Request $request)
    {
        $areas = $this->repository->getAreas($request->user());

        return response([
            'data' => $areas,
        ]);
    }

    public function getArea(Request $request, $area_param)
    {
        $area = $this->repository->getArea($request->user(), $area_param);

        return response([
            'data' => $area
        ]);
    }

    public function getByBucket(Request $request, $area, $bucket)
    {
        $tasks = $this->repository->getByBucket($request->user(), $area, $bucket);

        return TaskResource::collection($tasks);
    }
}
