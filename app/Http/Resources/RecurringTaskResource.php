<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecurringTaskResource extends JsonResource
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
            'frequency' => $this->frequency,
            'interval' => $this->interval,
            'occurrences' => $this->occurrences,
            'end_date' => $this->end_date
        ];
    }
}
