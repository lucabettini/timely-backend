<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;



class User extends Authenticatable
{
    use HasFactory;

    //------------------//
    // MODEL PROPERTIES // 
    //------------------//

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // Attributes hidden in JSON serialization
    protected $hidden = [
        'password'
    ];

    //---------------//
    // RELATIONSHIPS // 
    //---------------//

    public function task()
    {
        return $this->hasMany(Task::class);
    }

    public function timeUnit()
    {
        return $this->hasManyThrough(TimeUnit::class, Task::class);
    }
}
