<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TimeUnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'task_id' => $this->task_id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'duration' => $this->duration
        ];
    }
}
