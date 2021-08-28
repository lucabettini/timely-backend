<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
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

        return response($tasks);
    }

    public function getById(Request $request, $id)
    {
        $task = $this->repository->getTaskById($id, $request->user());

        return response($task);
    }

    public function getAreas(Request $request)
    {
        $areas = $this->repository->getAreas($request->user());

        return response([
            'areas' => $areas,
        ]);
    }
}
