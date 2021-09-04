<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringTask extends Model
{
    use HasFactory;

    //------------------//
    // MODEL PROPERTIES // 
    //------------------//

    // Mass assignable attributes
    protected $fillable = [
        'frequency',
        'interval',
        'occurrences_left',
        'end_date'
    ];

    protected $attributes = [
        'interval' => 1
    ];

    protected $casts = [
        'end_date' => 'date'   // Convert date to Carbon instance
    ];

    public $timestamps = false;

    //---------------//
    // RELATIONSHIPS // 
    //---------------//

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
