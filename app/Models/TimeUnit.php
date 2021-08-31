<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeUnit extends Model
{
    use HasFactory;

    // Mass assignable attributes
    protected $fillable = [
        'start_time',
        'end_time'
    ];

    protected $appends = [
        'duration'
    ];

    protected $casts = [
        // Convert datetime to Carbon instance
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    //--------------//
    // LOCAL SCOPES // 
    //--------------//

    public function scopeOpen($query)
    {
        return $query->whereNull('end_time');
    }

    public function scopeClosed($query)
    {
        return $query->whereNotNull('end_time')->whereNotNull('start_time');
    }

    public function scopeUntracked($query)
    {
        return $query->whereNull('start_time');
    }


    //-----------//
    // ACCESSORS // 
    //-----------//
    public function getDurationAttribute()
    {
        if ($this->end_time and $this->start_time) {
            return $this->start_time->diffInSeconds($this->end_time);
        } else {
            return 'null';
        }
    }


    //---------------//
    // RELATIONSHIPS // 
    //---------------//

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
