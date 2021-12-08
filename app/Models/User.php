<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    //------------------//
    // MODEL PROPERTIES // 
    //------------------//

    protected $fillable = [
        'name',
        'email',
        'password',
        'timezone'
    ];

    // Attributes hidden in JSON serialization
    protected $hidden = [
        'password'
    ];

    //---------------//
    // RELATIONSHIPS // 
    //---------------//

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function recurring()
    {
        return $this->hasOneThrough(RecurringTask::class, Task::class);
    }

    public function time_units()
    {
        return $this->hasManyThrough(TimeUnit::class, Task::class);
    }
}
