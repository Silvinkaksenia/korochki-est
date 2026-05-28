@extends('layouts.app')

@section('title', 'Все курсы')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white p-3 p-md-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                    <h4 class="mb-0 fs-5 fs-md-4"><i class="bi bi-book"></i> Все доступные курсы</h4>
                    <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm flex-shrink-0">
                        <i class="bi bi-arrow-left"></i> На главную
                    </a>
                </div>
            </div>
            <div class="card-body p-3 p-md-4">
                <!-- Форма фильтрации -->
                <div class="card mb-4 bg-light">
                    <div class="card-body p-3 p-md-4">
                        <form method="GET" action="{{ route('courses.index') }}" id="filter-form">
                            <div class="row g-3">
                                <!-- Поиск по названию -->
                                <div class="col-12">
                                    <label for="search" class="form-label fw-semibold">Поиск по названию</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                                        <input type="text" 
                                               class="form-control" 
                                               id="search" 
                                               name="search" 
                                               value="{{ request('search') }}"
                                               placeholder="Введите название курса...">
                                    </div>
                                </div>

                                <!-- Сортировка -->
                                <div class="col-6 col-md-4">
                                    <label for="sort" class="form-label fw-semibold">Сортировать по</label>
                                    <select class="form-select form-select-sm" id="sort" name="sort">
                                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Названию</option>
                                        <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Цене</option>
                                        <option value="duration_hours" {{ request('sort') == 'duration_hours' ? 'selected' : '' }}>Длительности</option>
                                        <option value="start_date" {{ request('sort') == 'start_date' ? 'selected' : '' }}>Дате начала</option>
                                    </select>
                                </div>

                                <div class="col-6 col-md-4">
                                    <label for="direction" class="form-label fw-semibold">Направление</label>
                                    <select class="form-select form-select-sm" id="direction" name="direction">
                                        <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>По возрастанию ↑</option>
                                        <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>По убыванию ↓</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-semibold d-none d-md-block">&nbsp;</label>
                                    <div class="d-grid gap-2 d-flex flex-column flex-sm-row">
                                        <button type="submit" class="btn btn-primary flex-grow-1">
                                            <i class="bi bi-search"></i> Применить
                                        </button>
                                        <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary flex-grow-1">
                                            <i class="bi bi-x-circle"></i> Сбросить
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Активные фильтры (для мобильных) -->
                @if(request()->anyFilled(['search', 'sort', 'direction']) && request('sort') != 'name' || request('direction') != 'asc' || request('search'))
                    <div class="mb-3 d-flex flex-wrap gap-2">
                        <span class="small text-muted me-2">Активные фильтры:</span>
                        @if(request('search'))
                            <span class="filter-badge">
                                Поиск: "{{ request('search') }}"
                                <a href="{{ route('courses.index', array_merge(request()->except('search'), ['page' => 1])) }}" class="text-decoration-none ms-1">&times;</a>
                            </span>
                        @endif
                        @if(request('sort') && request('sort') != 'name')
                            <span class="filter-badge">
                                Сортировка: {{ request('sort') == 'price' ? 'по цене' : (request('sort') == 'duration_hours' ? 'по длительности' : 'по дате') }}
                                <a href="{{ route('courses.index', array_merge(request()->except(['sort', 'direction']), ['page' => 1])) }}" class="text-decoration-none ms-1">&times;</a>
                            </span>
                        @endif
                    </div>
                @endif

                @if($courses->count() > 0)
                    <!-- Количество найденных курсов -->
                    <div class="mb-3">
                        <p class="text-muted small mb-0">
                            <i class="bi bi-info-circle"></i> Найдено курсов: <strong>{{ $courses->total() }}</strong>
                        </p>
                    </div>

                    <div class="row g-3 g-md-4">
                        @foreach($courses as $course)
                            <div class="col-sm-6 col-lg-4">
                                <div class="card h-100 course-card shadow-sm border-0">
                                    <div class="card-body p-3 p-md-4">
                                        <h5 class="card-title fw-bold fs-6 fs-md-5 mb-2">{{ $course->name }}</h5>
                                        <p class="card-text text-muted small mb-3" style="text-align: justify;">{{ Str::limit($course->description, 80) }}</p>
                                        
                                        <div class="mb-3">
                                            @if($course->start_date)
                                                <small class="text-muted d-flex align-items-center gap-1">
                                                    <i class="bi bi-calendar3"></i> 
                                                    <span>Начало: {{ $course->start_date->format('d.m.Y') }}</span>
                                                </small>
                                            @endif
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="text-muted small d-flex align-items-center gap-1">
                                                <i class="bi bi-clock"></i> {{ $course->duration_hours }} ч.
                                            </span>
                                            <span class="fw-bold text-primary fs-5">
                                                {{ number_format($course->price, 0, ',', ' ') }} ₽
                                            </span>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                                                <i class="bi bi-people"></i> {{ $course->applications_count }} заявок
                                            </span>
                                            @if($course->max_students && $course->available_spots <= 3)
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">
                                                    <i class="bi bi-exclamation-triangle"></i> Осталось {{ $course->available_spots }} мест
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 pb-3 pt-0">
                                        <a href="{{ route('course.show', $course) }}" class="btn btn-outline-primary w-100 rounded-pill btn-sm">
                                            <i class="bi bi-info-circle"></i> Подробнее
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4">
                        {{ $courses->withQueryString()->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-book display-1 text-muted opacity-50"></i>
                        <h3 class="mt-3 fs-4">Нет доступных курсов</h3>
                        <p class="text-muted small" style="text-align: justify;">
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
    /* Основные стили */
    .course-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-radius: 15px;
        overflow: hidden;
    }
    
    .course-card:active {
        transform: scale(0.98);
    }
    
    @media (min-width: 768px) {
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.1) !important;
        }
    }
    
    .course-card .card-footer {
        border-top: none;
        background-color: transparent;
    }
    
    /* Стили для бейджей */
    .badge {
        padding: 0.4rem 0.75rem;
        font-weight: 500;
        font-size: 0.7rem;
        border-radius: 20px;
    }
    
    /* Стили для активных фильтров */
    .filter-badge {
        background-color: #e9ecef;
        color: #495057;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .filter-badge a {
        color: #6c757d;
        font-size: 1rem;
        font-weight: bold;
    }
    
    .filter-badge a:hover {
        color: #dc3545;
    }
    
    /* Адаптивные стили */
    @media (max-width: 768px) {
        .card-header {
            padding: 0.75rem 1rem !important;
        }
        
        .card-body {
            padding: 1rem !important;
        }
        
        h4 {
            font-size: 1.25rem !important;
        }
        
        .fs-5 {
            font-size: 1rem !important;
        }
        
        .course-card .card-body {
            padding: 1rem !important;
        }
        
        .card-title {
            font-size: 0.95rem !important;
        }
        
        .btn-sm {
            font-size: 0.8rem;
            padding: 0.4rem 0.75rem;
        }
    }
    
    @media (max-width: 576px) {
        .container {
            padding-left: 12px;
            padding-right: 12px;
        }
        
        .row {
            margin-left: -8px;
            margin-right: -8px;
        }
        
        .row > [class*="col-"] {
            padding-left: 8px;
            padding-right: 8px;
        }
        
        .card-body {
            padding: 0.75rem !important;
        }
        
        .form-label {
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }
        
        .form-control, .form-select {
            font-size: 0.85rem;
            padding: 0.4rem 0.6rem;
        }
        
        .input-group-text {
            padding: 0.4rem 0.6rem;
        }
        
        .btn {
            font-size: 0.85rem;
            padding: 0.4rem 0.75rem;
        }
        
        .filter-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.6rem;
        }
        
        /* Пагинация на мобильных */
        .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .page-link {
            padding: 0.375rem 0.75rem;
            font-size: 0.8rem;
        }
    }
    
    /* Улучшение читаемости */
    .card-text {
        line-height: 1.4;
    }
    
    /* Стили для формы */
    .bg-light {
        background-color: #f8f9fa !important;
        border-radius: 15px;
    }
    
    /* Иконки в инпутах */
    .input-group-text {
        border-right: none;
        background-color: transparent;
    }
    
    .input-group .form-control {
        border-left: none;
    }
    
    .input-group .form-control:focus {
        border-left: none;
        box-shadow: none;
    }
    
    /* Кнопки на мобильных */
    @media (max-width: 576px) {
        .d-flex.gap-2 {
            gap: 0.5rem !important;
        }
        
        .btn {
            white-space: normal;
            word-break: break-word;
        }
    }
    
    /* Анимация загрузки */
    .course-card {
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Стили для пагинации */
    .pagination {
        margin-bottom: 0;
    }
    
    .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .page-link {
        color: #0d6efd;
        border-radius: 8px;
        margin: 0 2px;
    }
    
    @media (max-width: 576px) {
        .page-link {
            padding: 0.3rem 0.6rem;
            font-size: 0.75rem;
        }
    }
    
    /* Предотвращение горизонтального скролла */
    body {
        overflow-x: hidden;
    }
    
    /* Улучшенные отступы для сетки */
    .g-3 {
        --bs-gutter-y: 1rem;
        --bs-gutter-x: 1rem;
    }
    
    @media (max-width: 768px) {
        .g-3 {
            --bs-gutter-y: 0.75rem;
            --bs-gutter-x: 0.75rem;
        }
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
    
    // Убираем задержку при отправке формы
    const filterForm = document.getElementById('filter-form');
    if (filterForm) {
        filterForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Применяется...';
            }
        });
    }
</script>
@endpush