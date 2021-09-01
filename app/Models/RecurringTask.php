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
        'occurrences',
        'end_date'
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
