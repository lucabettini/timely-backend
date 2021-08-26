<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\TimeUnitRequest;
use App\Modules\Tasks\Repositories\TimeUnitRepository;
use Illuminate\Http\Request;

class TimeUnitController extends Controller
{
    private $repository;

    public function __construct(TimeUnitRepository $repository)
    {
        $this->repository = $repository;
    }

    public function store(TimeUnitRequest $request, $task_id)
    {

        $time_unit = $this->repository->createTimeUnit($request->all(), $task_id, $request->user());

        return response($time_unit);
    }
}