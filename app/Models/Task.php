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
        'color' => '000000',
        'description' => null
    ];

    // Convert 1 and 0 into true and false
    protected $casts = [
        'completed' => 'boolean'
    ];

    //--------------//
    // LOCAL SCOPES // 
    //--------------//

    public function scopeOpen($query)
    {
        return $query->where('completed', false);
    }

    //---------------//
    // RELATIONSHIPS // 
    //---------------//

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function timeUnit()
    {
        return $this->hasMany(TimeUnit::class);
    }

    public function recurring()
    {
        return $this->hasOne(RecurringTask::class);
    }
}
