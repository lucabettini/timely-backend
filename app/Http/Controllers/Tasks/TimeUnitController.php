<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Resources\TimeUnitResource;
use App\Modules\Tasks\Repositories\TimeUnitRepository;
use Illuminate\Http\Request;

class TimeUnitController extends Controller
{
    private $repository;

    public function __construct(TimeUnitRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getStarted(Request $request)
    {
        $time_unit = $this->repository->getStarted($request->user());
        if ($time_unit) {
            return new TimeUnitResource($time_unit);
        } else {
            return response(null, 200);
        }
    }

    public function store(Request $request, $task_id)
    {
        $request->validate([
            'start_time' => 'date|required',
            'end_time' => 'date|nullable'
        ]);
        $time_unit = $this->repository->createTimeUnit($request->all(), $task_id, $request->user());

        return new TimeUnitResource($time_unit);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'start_time' => 'date|required',
            'end_time' => 'date|nullable'
        ]);
        $time_unit = $this->repository->updateTimeUnit($request->all(), $id, $request->user());

        return new TimeUnitResource($time_unit);
    }

    public function destroy(Request $request, $id)
    {
        $this->repository->deleteTimeUnit($id, $request->user());

        return response([
            'message' => 'Time unit deleted successfully'
        ]);
    }
}
