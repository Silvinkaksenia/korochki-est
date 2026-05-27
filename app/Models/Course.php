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

    /**
     * Получение количества доступных мест
     */
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

    /**
     * Проверка доступности курса
     */
    public function getIsAvailableAttribute()
    {
        if (!$this->is_active) {
            return false;
        }
        
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

    /**
     * Применяет фильтры поиска к запросу
     */
    public function scopeFilter($query, $filters)
    {
        // Поиск по названию или описанию
        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('description', 'like', "%{$filters['search']}%");
            });
        }

        // Фильтр по цене (от и до)
        if (isset($filters['price_min']) && $filters['price_min'] !== '') {
            $query->where('price', '>=', $filters['price_min']);
        }

        if (isset($filters['price_max']) && $filters['price_max'] !== '') {
            $query->where('price', '<=', $filters['price_max']);
        }

        // Фильтр по длительности
        if (isset($filters['duration_min']) && $filters['duration_min'] !== '') {
            $query->where('duration_hours', '>=', $filters['duration_min']);
        }

        if (isset($filters['duration_max']) && $filters['duration_max'] !== '') {
            $query->where('duration_hours', '<=', $filters['duration_max']);
        }

        // Фильтр по статусу активности (для админки)
        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', $filters['is_active']);
        }

        // Фильтр по дате начала (для админки)
        if (!empty($filters['date_from'])) {
            $query->whereDate('start_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('start_date', '<=', $filters['date_to']);
        }

        // Фильтр "только доступные" для пользователей
        if (!empty($filters['only_available']) && $filters['only_available']) {
            $query->where('is_active', true)
                  ->where('start_date', '>=', now())
                  ->where(function($q) {
                      $q->whereNull('max_students')
                        ->orWhereHas('applications', function($subQ) {
                            // Сложная логика для доступных мест
                        });
                  });
        }

        return $query;
    }

    /**
     * Сортировка результатов
     */
    public function scopeSort($query, $sortField = 'name', $sortDirection = 'asc')
    {
        $allowedFields = ['name', 'price', 'duration_hours', 'start_date', 'created_at'];
        
        if (in_array($sortField, $allowedFields)) {
            $query->orderBy($sortField, $sortDirection === 'desc' ? 'desc' : 'asc');
        }
        
        return $query;
    }

    /**
     * Получить только доступные для записи курсы
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)
                     ->where('start_date', '>=', now())
                     ->where(function($q) {
                         $q->whereNull('max_students')
                           ->orWhereHas('applications', function($subQ) {
                               // Логика для доступных мест
                           });
                     });
    }
}