<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\TaskRequest;
use App\Modules\Tasks\Repositories\EditTaskRepository;
use Illuminate\Http\Request;

class EditTaskController extends Controller
{
    private $repository;

    public function __construct(EditTaskRepository $repository)
    {
        $this->repository = $repository;
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
