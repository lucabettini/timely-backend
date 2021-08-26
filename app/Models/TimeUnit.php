<?php

namespace App\Models;

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

    //---------------//
    // RELATIONSHIPS // 
    //---------------//

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}