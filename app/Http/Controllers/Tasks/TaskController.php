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

        return response([$task]);
    }

    public function store(TaskRequest $request)
    {

        $task = $this->repository->createTask($request->all(), $request->user());

        return response([$task]);
    }
}
