<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    //------------------//
    // MODEL PROPERTIES // 
    //------------------//

    // Mass assignable attributes
    protected $fillable = [
        'name',
        'bucket',
        'area',
        'description',
        'scheduled_for',
        'completed',
        'color'
    ];

    // Default attribute values 
    protected $attributes = [
        'completed' => false,
        'tracked' => true,
        'color' => '000000',
        'description' => null
    ];

    protected $appends = [
        'duration'
    ];

    protected $casts = [
        'completed' => 'boolean',   // Convert 1 and 0 into true and false
        'tracked' => 'boolean',
        'scheduled_for' => 'date'   // Convert date to Carbon instance
    ];


    public function getDurationAttribute()
    {
        return $this->timeUnits->sum('duration');
    }

    //--------------//
    // LOCAL SCOPES // 
    //--------------//

    public function scopeActive($query)
    {
        return $query->where('completed', false);
    }

    public function scopeTracked($query)
    {
        return $query->where('tracked', true);
    }

    //---------------//
    // RELATIONSHIPS // 
    //---------------//

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function timeUnits()
    {
        return $this->hasMany(TimeUnit::class);
    }

    public function recurring()
    {
        return $this->hasOne(RecurringTask::class);
    }
}
