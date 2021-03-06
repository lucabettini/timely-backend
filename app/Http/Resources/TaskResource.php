<?php

namespace App\Http\Resources;

use App\Http\Requests\Tasks\RecurringTaskRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'name' => $this->name,
            'bucket' => $this->bucket,
            'area' => $this->area,
            'description' => $this->description,
            'scheduled_for' => $this->scheduled_for,
            'completed' => $this->completed,
            'tracked' => $this->tracked,
            'duration' => $this->duration,
            'time_units' => $this->timeUnitsCount,
            'recurring' => new RecurringTaskResource($this->recurring)
        ];
    }
}
