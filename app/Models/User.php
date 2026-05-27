<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'login',
        'password',
        'full_name',
        'phone',
        'email',
        'is_admin',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function hasActiveApplicationForCourse($courseId)
{
    return $this->applications()
        ->where('course_id', $courseId)
        ->whereIn('status', ['new', 'in_progress'])
        ->exists();
}
}