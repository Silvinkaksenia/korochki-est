@extends('layouts.app')

@section('title', 'Все курсы')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-book"></i> Все доступные курсы</h4>
                    <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left"></i> На главную
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Форма фильтрации -->
                <div class="card mb-4 bg-light">
                    <div class="card-body">
                        <form method="GET" action="{{ route('courses.index') }}" id="filter-form">
                            <div class="row g-3">
                                <!-- Поиск по названию -->
                                <div class="col-md-12">
                                    <label for="search" class="form-label">Поиск по названию</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="search" 
                                           name="search" 
                                           value="{{ request('search') }}"
                                           placeholder="Введите название курса...">
                                </div>

                                <!-- Фильтр по цене
                                <div class="col-md-6">
                                    <label for="price_min" class="form-label">Цена от (₽)</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="price_min" 
                                           name="price_min" 
                                           value="{{ request('price_min') }}"
                                           min="0"
                                           step="100">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="price_max" class="form-label">Цена до (₽)</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="price_max" 
                                           name="price_max" 
                                           value="{{ request('price_max') }}"
                                           min="0"
                                           step="100">
                                </div>

                                 --Фильтр по длительности--
                                <div class="col-md-6">
                                    <label for="duration_min" class="form-label">Длительность от (часов)</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="duration_min" 
                                           name="duration_min" 
                                           value="{{ request('duration_min') }}"
                                           min="1">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="duration_max" class="form-label">Длительность до (часов)</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="duration_max" 
                                           name="duration_max" 
                                           value="{{ request('duration_max') }}"
                                           min="1">
                                </div> -->

                                <!-- Сортировка -->
                                <div class="col-md-4">
                                    <label for="sort" class="form-label">Сортировать по</label>
                                    <select class="form-select" id="sort" name="sort">
                                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Названию</option>
                                        <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Цене</option>
                                        <option value="duration_hours" {{ request('sort') == 'duration_hours' ? 'selected' : '' }}>Длительности</option>
                                        <option value="start_date" {{ request('sort') == 'start_date' ? 'selected' : '' }}>Дате начала</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="direction" class="form-label">Направление</label>
                                    <select class="form-select" id="direction" name="direction">
                                        <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>По возрастанию</option>
                                        <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>По убыванию</option>
                                    </select>
                                </div>

                                <div class="col-md-4 d-flex align-items-end">
                                    <div class="d-grid gap-2 w-100">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-search"></i> Применить фильтры
                                        </button>
                                        <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-x-circle"></i> Сбросить
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if($courses->count() > 0)
                    <!-- Количество найденных курсов -->
                    <div class="mb-3">
                        <p class="text-muted">
                            Найдено курсов: <strong>{{ $courses->total() }}</strong>
                        </p>
                    </div>

                    <div class="row g-4">
                        @foreach($courses as $course)
                            <div class="col-md-4">
                                <div class="card h-100 course-card shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $course->name }}</h5>
                                        <p class="card-text">{{ Str::limit($course->description, 100) }}</p>
                                        <div class="mb-3">
                                            @if($course->start_date)
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar"></i> 
                                                    Начало: {{ $course->start_date->format('d.m.Y') }}
                                                </small>
                                            @endif
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="text-muted">
                                                <i class="bi bi-clock"></i> {{ $course->duration_hours }} ч.
                                            </span>
                                            <span class="fw-bold text-primary">
                                                {{ number_format($course->price, 0, ',', ' ') }} ₽
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-info">
                                                {{ $course->applications_count }} заявок
                                            </span>
                                            @if($course->max_students && $course->available_spots <= 3)
                                                <span class="badge bg-warning">
                                                    Осталось {{ $course->available_spots }} мест
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <a href="{{ route('course.show', $course) }}" class="btn btn-primary w-100">
                                            <i class="bi bi-info-circle"></i> Подробнее
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4">
                        {{ $courses->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-book display-1 text-muted"></i>
                        <h3 class="mt-3">Нет доступных курсов</h3>
                        <p class="text-muted">
                            @if(request()->anyFilled(['search', 'price_min', 'price_max', 'duration_min', 'duration_max']))
                                По вашему запросу ничего не найдено. Попробуйте изменить параметры фильтрации.
                            @else
                                В данный момент нет активных курсов для записи.
                            @endif
                        </p>
                        @if(request()->anyFilled(['search', 'price_min', 'price_max', 'duration_min', 'duration_max']))
                            <a href="{{ route('courses.index') }}" class="btn btn-primary mt-3">
                                <i class="bi bi-x-circle"></i> Сбросить фильтры
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">
                                <i class="bi bi-arrow-left"></i> Вернуться на главную
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .course-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        border-radius: 10px;
    }
    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .course-card .card-footer {
        border-top: none;
        background-color: transparent;
        padding-top: 0;
    }
    .badge {
        padding: 0.5em 1em;
        font-weight: 500;
    }
    /* Стили для активных фильтров */
    .filter-badge {
        background-color: #e9ecef;
        color: #495057;
        padding: 0.5em 1em;
        border-radius: 20px;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .filter-badge .btn-close {
        font-size: 0.8rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Автоматическая отправка формы при изменении сортировки
    document.getElementById('sort')?.addEventListener('change', function() {
        document.getElementById('filter-form').submit();
    });
    
    document.getElementById('direction')?.addEventListener('change', function() {
        document.getElementById('filter-form').submit();
    });
</script>
@endpush