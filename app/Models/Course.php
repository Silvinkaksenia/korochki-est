<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'duration_hours',
        'price',
        'start_date',
        'max_students',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    // Новый метод для получения доступных мест
    public function getAvailableSpotsAttribute()
    {
        if (!$this->max_students) {
            return null; // Нет ограничения
        }
        
        $occupied = $this->applications()
            ->whereIn('status', ['new', 'in_progress'])
            ->count();
            
        return max(0, $this->max_students - $occupied);
    }

    // Проверка доступности курса
    public function getIsAvailableAttribute()
{
    if (!$this->is_active) {
        return false;
    }
    
    // Проверяем, установлена ли дата начала
    if (!$this->start_date) {
        return false;
    }
    
    if ($this->start_date < now()) {
        return false;
    }
    
    if ($this->max_students && $this->available_spots <= 0) {
        return false;
    }
    
    return true;
}
}