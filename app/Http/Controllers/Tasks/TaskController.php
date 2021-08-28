<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\TaskRequest;
use App\Modules\Tasks\Repositories\TaskRepository;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    private $repository;

    public function __construct(TaskRepository $repository)
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

    public function store(TaskRequest $request)
    {

        $task = $this->repository->createTask($request->all(), $request->user());

        return response($task);
    }

    public function update(TaskRequest $request, $id)
    {
        $task = $this->repository->updateTask($request->all(), $id, $request->user());

        return response($task);
    }

    public function destroy(Request $request, $id)
    {
        $this->repository->deleteTask($id, $request->user());

        return response([
            'message' => 'Task deleted successfully'
        ]);
    }
}
