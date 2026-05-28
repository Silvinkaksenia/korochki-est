@extends('layouts.app')

@section('title', 'Информация о курсе')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-info text-white p-3 p-md-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <h4 class="mb-0 fs-5 fs-md-4"><i class="bi bi-book"></i> Информация о курсе</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left"></i> Назад
                        </a>
                        <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-light btn-sm">
                            <i class="bi bi-pencil"></i> Редактировать
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-3 p-md-4">
                <div class="row g-4">
                    <!-- Основная колонка -->
                    <div class="col-lg-8">
                        <h3 class="mb-3 fw-bold fs-3 fs-md-2" style="word-break: keep-all;">{{ $course->name }}</h3>
                        <p class="lead mb-4" style="text-align: justify; font-size: 0.95rem;">{{ $course->description }}</p>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <div class="card bg-light border-0">
                                    <div class="card-body p-3 p-md-4">
                                        <h6 class="card-title fw-bold mb-3"><i class="bi bi-info-circle"></i> Основная информация</h6>
                                        <div class="row g-2">
                                            <div class="col-sm-6">
                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                    <span class="text-muted small">Длительность:</span>
                                                    <span class="fw-semibold">{{ $course->duration_hours }} часов</span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                    <span class="text-muted small">Цена:</span>
                                                    <span class="fw-bold text-primary fs-5">{{ number_format($course->price, 0, ',', ' ') }} ₽</span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                    <span class="text-muted small">Дата начала:</span>
                                                    <span class="fw-semibold">
                                                        @if($course->start_date)
                                                            {{ $course->start_date->format('d.m.Y') }}
                                                        @else
                                                            <span class="text-muted">Не указана</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                    <span class="text-muted small">Статус:</span>
                                                    <span>
                                                        @if($course->is_active && $course->is_available)
                                                            <span class="badge bg-success">Доступен для записи</span>
                                                        @elseif($course->is_active && !$course->is_available)
                                                            <span class="badge bg-warning">Заполнен или дата прошла</span>
                                                        @else
                                                            <span class="badge bg-secondary">Неактивен</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            @if($course->max_students)
                                            <div class="col-sm-12">
                                                <div class="d-flex justify-content-between align-items-center py-2">
                                                    <span class="text-muted small">Места:</span>
                                                    <div class="text-end">
                                                        <span class="fw-semibold">{{ $course->available_spots }} из {{ $course->max_students }} свободно</span>
                                                        <div class="progress mt-1" style="height: 6px;">
                                                            @php
                                                                $percentFilled = (($course->max_students - $course->available_spots) / $course->max_students) * 100;
                                                            @endphp
                                                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percentFilled }}%;" aria-valuenow="{{ $percentFilled }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h5 class="mb-3 fw-bold"><i class="bi bi-people"></i> Заявки на курс</h5>
                        @if($course->applications->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Пользователь</th>
                                            <th>Дата заявки</th>
                                            <th>Статус</th>
                                            <th>Оплата</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($course->applications as $application)
                                            <tr>
                                                <td class="align-middle">#{{ $application->id }}</td>
                                                <td class="align-middle">
                                                    <a href="{{ route('admin.users.show', $application->user) }}" 
                                                       class="text-decoration-none">
                                                        {{ $application->user->full_name }}
                                                    </a>
                                                </td>
                                                <td class="align-middle small">{{ $application->created_at->format('d.m.Y') }}</td>
                                                <td class="align-middle">
                                                    <span class="badge 
                                                        @if($application->status == 'new') bg-primary
                                                        @elseif($application->status == 'in_progress') bg-warning
                                                        @else bg-success @endif">
                                                        {{ $application->status == 'new' ? 'Новая' : 
                                                           ($application->status == 'in_progress' ? 'В процессе' : 'Завершено') }}
                                                    </span>
                                                </td>
                                                <td class="align-middle small">
                                                    {{ $application->payment_method == 'cash' ? 'Наличные' : 'Перевод' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info border-0 shadow-sm">
                                <i class="bi bi-info-circle"></i> На этот курс пока нет заявок
                            </div>
                        @endif
                    </div>
                    
                    <!-- Боковая колонка со статистикой -->
                    <div class="col-lg-4">
                        <div class="card bg-light border-0 shadow-sm sticky-lg-top" style="top: 20px;">
                            <div class="card-body p-3 p-md-4">
                                <h6 class="card-title fw-bold mb-3"><i class="bi bi-graph-up"></i> Статистика курса</h6>
                                
                                <div class="text-center mb-4">
                                    <div class="display-3 fw-bold text-primary">{{ $course->applications->count() }}</div>
                                    <p class="mb-0 text-muted small">Всего заявок</p>
                                </div>
                                
                                <div class="row g-2 text-center">
                                    <div class="col-6 mb-2">
                                        <div class="bg-white rounded-3 p-2 shadow-sm">
                                            <h5 class="text-primary mb-0 fs-4">{{ $course->applications->where('status', 'new')->count() }}</h5>
                                            <small class="text-muted">Новых</small>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <div class="bg-white rounded-3 p-2 shadow-sm">
                                            <h5 class="text-warning mb-0 fs-4">{{ $course->applications->where('status', 'in_progress')->count() }}</h5>
                                            <small class="text-muted">В процессе</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-white rounded-3 p-2 shadow-sm">
                                            <h5 class="text-success mb-0 fs-4">{{ $course->applications->where('status', 'completed')->count() }}</h5>
                                            <small class="text-muted">Завершено</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-white rounded-3 p-2 shadow-sm">
                                            @php
                                                $withReview = $course->applications->where('review')->count();
                                            @endphp
                                            <h5 class="text-info mb-0 fs-4">{{ $withReview }}</h5>
                                            <small class="text-muted">С отзывами</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="my-4">
                                
                                <div class="d-grid gap-2">
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                                        <i class="bi bi-speedometer2"></i> К заявкам
                                    </a>
                                    <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" 
                                          onsubmit="return confirm('Удалить курс?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100">
                                            <i class="bi bi-trash"></i> Удалить курс
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Адаптивные стили */
    .card {
        border-radius: 16px;
        overflow: hidden;
    }
    
    .card-header {
        border-bottom: none;
    }
    
    /* Таблица на мобильных */
    .table-responsive {
        border-radius: 12px;
        overflow-x: auto;
    }
    
    .table td, .table th {
        padding: 0.75rem;
        vertical-align: middle;
    }
    
    /* Прогресс-бар */
    .progress {
        border-radius: 10px;
        background-color: #e9ecef;
    }
    
    /* Стики блок на десктопе */
    @media (min-width: 992px) {
        .sticky-lg-top {
            position: sticky;
            top: 20px;
        }
    }
    
    /* Адаптация для планшетов */
    @media (max-width: 768px) {
        .card-header {
            padding: 0.75rem 1rem !important;
        }
        
        .card-body {
            padding: 1rem !important;
        }
        
        h3 {
            font-size: 1.35rem !important;
        }
        
        .lead {
            font-size: 0.9rem !important;
        }
        
        .display-3 {
            font-size: 2.5rem !important;
        }
        
        .fs-4 {
            font-size: 1.1rem !important;
        }
        
        .table td, .table th {
            padding: 0.5rem;
            font-size: 0.8rem;
        }
        
        .badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }
        
        .btn-sm {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
    }
    
    /* Адаптация для телефонов */
    @media (max-width: 576px) {
        .card-body {
            padding: 0.75rem !important;
        }
        
        h3 {
            font-size: 1.2rem !important;
        }
        
        .lead {
            font-size: 0.85rem !important;
            text-align: justify;
        }
        
        .display-3 {
            font-size: 2rem !important;
        }
        
        .fs-4 {
            font-size: 1rem !important;
        }
        
        .table td, .table th {
            padding: 0.4rem;
            font-size: 0.7rem;
        }
        
        .btn {
            font-size: 0.8rem;
            padding: 0.4rem 0.75rem;
        }
        
        .btn-sm {
            font-size: 0.7rem;
        }
        
        .badge {
            font-size: 0.65rem;
        }
        
        .bg-white.rounded-3 {
            padding: 0.5rem !important;
        }
        
        h5.mb-0 {
            font-size: 1rem !important;
        }
        
        small {
            font-size: 0.65rem;
        }
        
        /* Улучшение читаемости таблицы */
        .table-responsive {
            border-radius: 8px;
        }
    }
    
    /* Стили для таблицы */
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
    
    .table-light th {
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    /* Анимация для карточек */
    .bg-white.rounded-3 {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .bg-white.rounded-3:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    
    /* Кнопки при нажатии */
    .btn:active {
        transform: scale(0.98);
    }
    
    /* Выравнивание текста */
    .text-justify {
        text-align: justify;
    }
    
    /* Улучшенные отступы */
    .g-4 {
        --bs-gutter-y: 1rem;
        --bs-gutter-x: 1rem;
    }
    
    @media (max-width: 768px) {
        .g-4 {
            --bs-gutter-y: 0.75rem;
            --bs-gutter-x: 0.75rem;
        }
    }
    
    /* Заголовки */
    .fw-bold {
        font-weight: 600 !important;
    }
    
    /* Информационные блоки */
    .border-bottom {
        border-bottom: 1px solid #dee2e6;
    }
    
    /* Цвета для бейджей */
    .bg-warning {
        background-color: #ffc107 !important;
        color: #000 !important;
    }
    
    /* Предотвращение горизонтального скролла */
    body {
        overflow-x: hidden;
    }
    
    /* Адаптация для iframe если есть */
    iframe {
        max-width: 100%;
    }
</style>
@endpush