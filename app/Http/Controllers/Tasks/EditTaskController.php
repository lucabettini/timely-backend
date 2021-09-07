<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\TaskRequest;
use App\Modules\Tasks\Repositories\EditTaskRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    public function complete(Request $request, $id)
    {
        $this->repository->setCompleted($id, true, $request->user());
        return response([
            'message' => 'Task completed successfully'
        ]);
    }

    public function makeIncomplete(Request $request, $id)
    {
        $this->repository->setCompleted($id, false, $request->user());
        return response([
            'message' => 'Task set as incomplete'
        ]);
    }

    public function editBucketName(Request $request)
    {
        $validated = $request->validate([
            'old_name' => 'string|required',
            'new_name' => [
                'string',
                'required',
            ]
        ]);

        try {
            $this->repository->editBucketName($validated['old_name'], $validated['new_name'], $request->user());
            return response([
                'message' => 'Bucket name changed'
            ]);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], 400);
        }
    }

    public function deleteByBucket(Request $request)
    {
        $validated = $request->validate([
            'bucket' => [
                'string',
                'required',
                'exists:tasks,bucket'
            ]
        ]);

        $this->repository->deleteByBucket($validated['bucket'], $request->user());
        return response([
            'message' => 'Bucket deleted successfully'
        ]);
    }
}
