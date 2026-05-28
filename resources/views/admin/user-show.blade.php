@extends('layouts.app')

@section('title', 'Карточка пользователя')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white p-3 p-md-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <h4 class="mb-0 fs-5 fs-md-4 fw-bold"><i class="bi bi-person-badge"></i> Карточка пользователя</h4>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-sm flex-shrink-0">
                        <i class="bi bi-arrow-left"></i> Назад к списку
                    </a>
                </div>
            </div>
            <div class="card-body p-3 p-md-4">
                <div class="row g-4">
                    <!-- Левая колонка - аватар и статус -->
                    <div class="col-md-4 text-center">
                        <div class="mb-3">
                            <i class="bi bi-person-circle display-1 text-primary"></i>
                        </div>
                        @if($user->is_admin)
                            <span class="badge bg-danger fs-6 px-3 py-2">Администратор</span>
                        @else
                            <span class="badge bg-secondary fs-6 px-3 py-2">Пользователь</span>
                        @endif
                    </div>
                    
                    <!-- Правая колонка - основная информация -->
                    <div class="col-md-8">
                        <h5 class="mb-3 fw-bold"><i class="bi bi-info-circle"></i> Основная информация</h5>
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="text-muted small">Логин:</span>
                                    <strong class="ms-2 text-end">{{ $user->login }}</strong>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="text-muted small">ФИО:</span>
                                    <strong class="ms-2 text-end">{{ $user->full_name }}</strong>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="text-muted small">Email:</span>
                                    <a href="mailto:{{ $user->email }}" class="ms-2 text-end text-decoration-none">{{ $user->email }}</a>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="text-muted small">Телефон:</span>
                                    <strong class="ms-2 text-end">{{ $user->phone }}</strong>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="text-muted small">Дата регистрации:</span>
                                    <span class="ms-2 text-end">{{ $user->created_at->format('d.m.Y H:i') }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="text-muted small">Последнее обновление:</span>
                                    <span class="ms-2 text-end">{{ $user->updated_at->format('d.m.Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div class="row g-4 mt-0">
                    <!-- Заявки пользователя -->
                    <div class="col-md-6">
                        <h5 class="mb-3 fw-bold"><i class="bi bi-list-check"></i> Заявки пользователя</h5>
                        @if($user->applications->count() > 0)
                            <div class="list-group">
                                @foreach($user->applications->take(5) as $application)
                                    <div class="list-group-item list-group-item-action border-0 shadow-sm mb-2 rounded-3">
                                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                            <strong class="small">{{ $application->course->name }}</strong>
                                            <span class="badge 
                                                @if($application->status == 'new') bg-primary
                                                @elseif($application->status == 'in_progress') bg-warning
                                                @else bg-success @endif">
                                                {{ $application->status == 'new' ? 'Новая' : 
                                                   ($application->status == 'in_progress' ? 'В процессе' : 'Завершено') }}
                                            </span>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="bi bi-calendar"></i> Дата начала: {{ $application->desired_start_date->format('d.m.Y') }}<br>
                                                <i class="bi bi-wallet2"></i> Способ оплаты: {{ $application->payment_method == 'cash' ? 'Наличные' : 'Перевод' }}
                                            </small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($user->applications->count() > 5)
                                <div class="mt-3 text-center">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle"></i> Показано 5 из {{ $user->applications->count() }} заявок
                                    </small>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info border-0 shadow-sm">
                                <i class="bi bi-info-circle"></i> У пользователя нет заявок
                            </div>
                        @endif
                    </div>
                    
                    <!-- Отзывы пользователя -->
                    <div class="col-md-6">
                        <h5 class="mb-3 fw-bold"><i class="bi bi-star"></i> Отзывы пользователя</h5>
                        @if($user->reviews->count() > 0)
                            <div class="list-group">
                                @foreach($user->reviews->take(5) as $review)
                                    <div class="list-group-item list-group-item-action border-0 shadow-sm mb-2 rounded-3">
                                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                            <div>
                                                <strong class="small">Курс:</strong> {{ $review->application->course->name }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $review->created_at->format('d.m.Y') }}
                                            </small>
                                        </div>
                                        <div class="text-warning mt-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} fs-6"></i>
                                            @endfor
                                            <small class="text-muted ms-1">({{ $review->rating }}/5)</small>
                                        </div>
                                        @if($review->comment)
                                            <div class="mt-2 pt-1 border-top">
                                                <strong class="small">Комментарий:</strong>
                                                <p class="mb-0 small text-muted mt-1" style="text-align: justify;">{{ Str::limit($review->comment, 100) }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @if($user->reviews->count() > 5)
                                <div class="mt-3 text-center">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle"></i> Показано 5 из {{ $user->reviews->count() }} отзывов
                                    </small>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info border-0 shadow-sm">
                                <i class="bi bi-info-circle"></i> Пользователь не оставлял отзывов
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Статистика -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card bg-gradient-light border-0 shadow-sm">
                            <div class="card-body p-3 p-md-4">
                                <h6 class="mb-3 fw-bold"><i class="bi bi-graph-up"></i> Статистика по пользователю</h6>
                                <div class="row g-3 text-center">
                                    <div class="col-6 col-md-3">
                                        <div class="bg-white rounded-3 p-2 shadow-sm">
                                            <h3 class="text-primary mb-0 fs-2">{{ $user->applications_count ?? $user->applications->count() }}</h3>
                                            <p class="mb-0 small text-muted">Всего заявок</p>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="bg-white rounded-3 p-2 shadow-sm">
                                            <h3 class="text-warning mb-0 fs-2">{{ $user->applications->where('status', 'new')->count() }}</h3>
                                            <p class="mb-0 small text-muted">Новых</p>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="bg-white rounded-3 p-2 shadow-sm">
                                            <h3 class="text-info mb-0 fs-2">{{ $user->applications->where('status', 'in_progress')->count() }}</h3>
                                            <p class="mb-0 small text-muted">В процессе</p>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="bg-white rounded-3 p-2 shadow-sm">
                                            <h3 class="text-success mb-0 fs-2">{{ $user->applications->where('status', 'completed')->count() }}</h3>
                                            <p class="mb-0 small text-muted">Завершено</p>
                                        </div>
                                    </div>
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
    /* Основные стили */
    .card {
        border-radius: 16px;
        overflow: hidden;
    }
    
    /* Заголовки */
    .fw-bold {
        font-weight: 600 !important;
    }
    
    /* Градиентный фон */
    .bg-gradient-light {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    
    /* Адаптивные отступы */
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
        
        h5 {
            font-size: 1rem !important;
        }
        
        .fs-2 {
            font-size: 1.5rem !important;
        }
        
        .fs-6 {
            font-size: 0.8rem !important;
        }
        
        .badge.fs-6 {
            font-size: 0.75rem !important;
            padding: 0.4rem 0.75rem !important;
        }
        
        .display-1 {
            font-size: 3rem !important;
        }
        
        .border-bottom {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }
        
        .text-muted.small {
            font-size: 0.75rem !important;
        }
        
        strong {
            font-size: 0.85rem !important;
        }
    }
    
    @media (max-width: 576px) {
        .card-body {
            padding: 0.75rem !important;
        }
        
        h4 {
            font-size: 1.1rem !important;
        }
        
        h5 {
            font-size: 0.9rem !important;
        }
        
        h6 {
            font-size: 0.85rem !important;
        }
        
        .fs-2 {
            font-size: 1.25rem !important;
        }
        
        .display-1 {
            font-size: 2.5rem !important;
        }
        
        .btn-sm {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }
        
        .badge {
            font-size: 0.65rem;
            padding: 0.25rem 0.5rem;
        }
        
        .list-group-item {
            padding: 0.75rem !important;
        }
        
        .list-group-item strong {
            font-size: 0.8rem !important;
        }
        
        .small {
            font-size: 0.7rem !important;
        }
        
        .rounded-3 {
            border-radius: 12px !important;
        }
        
        .p-2 {
            padding: 0.5rem !important;
        }
        
        .p-3 {
            padding: 0.75rem !important;
        }
        
        .mb-2 {
            margin-bottom: 0.5rem !important;
        }
        
        .mt-3 {
            margin-top: 0.75rem !important;
        }
        
        .gap-2 {
            gap: 0.5rem !important;
        }
        
        /* Звезды рейтинга */
        .bi-star-fill, .bi-star {
            font-size: 0.8rem !important;
        }
    }
    
    /* Стили для списка заявок и отзывов */
    .list-group-item {
        transition: all 0.2s ease;
    }
    
    .list-group-item:active {
        transform: scale(0.99);
    }
    
    @media (min-width: 768px) {
        .list-group-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
        }
    }
    
    /* Анимация для кнопок */
    .btn:active {
        transform: scale(0.98);
        transition: transform 0.1s ease;
    }
    
    /* Статистические карточки */
    .bg-white.rounded-3 {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .bg-white.rounded-3:active {
        transform: scale(0.98);
    }
    
    @media (min-width: 768px) {
        .bg-white.rounded-3:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
        }
    }
    
    /* Таблица с информацией */
    .border-bottom {
        border-bottom: 1px solid #e9ecef !important;
    }
    
    /* Иконки */
    .bi {
        vertical-align: middle;
    }
    
    /* Предотвращение горизонтального скролла */
    body {
        overflow-x: hidden;
    }
    
    /* Улучшение читаемости */
    .text-muted {
        font-size: 0.8rem;
    }
    
    /* Алерты */
    .alert {
        border-radius: 12px;
    }
    
    /* Разделитель */
    hr {
        opacity: 0.5;
        margin: 1rem 0;
    }
    
    /* Сетка */
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
    
    /* Карточка статистики */
    .bg-gradient-light {
        border-radius: 16px;
    }
</style>
@endpush