@extends('layouts.app')

@section('title', $course->name)

@section('content')
<div class="row g-3 g-lg-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white p-3 p-md-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                    <h4 class="mb-0 fs-5 fs-md-4 fw-bold" style="word-break: keep-all;"><i class="bi bi-book"></i> {{ $course->name }}</h4>
                    <a href="{{ route('courses.index') }}" class="btn btn-light btn-sm flex-shrink-0">
                        <i class="bi bi-arrow-left"></i> Все курсы
                    </a>
                </div>
            </div>
            <div class="card-body p-3 p-md-4">
            @if($course->start_date && $course->start_date < now()->addDays(7))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> 
                    <strong>Спешите!</strong> Начало курса {{ $course->start_date->format('d.m.Y') }}.
                    Осталось всего {{ $course->start_date->diffForHumans(['parts' => 1, 'short' => false]) }}!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
                
                <div class="row g-4 mb-4">
                    <div class="col-md-8">
                        <h5 class="mb-3 fw-bold"><i class="bi bi-file-text"></i> Описание курса</h5>
                        <p class="lead" style="text-align: justify;">{{ $course->description }}</p>
                        
                        @if($course->max_students && $course->available_spots <= 5)
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> 
                                Осталось всего {{ $course->available_spots }} мест из {{ $course->max_students }}
                            </div>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light border-0 sticky-md-top" style="top: 20px;">
                            <div class="card-body p-3 p-md-4">
                                <h6 class="card-title fw-bold mb-3"><i class="bi bi-info-circle"></i> Детали курса</h6>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between flex-wrap gap-2 py-2 border-bottom">
                                        <span class="text-muted">Длительность:</span>
                                        <strong>{{ $course->duration_hours }} часов</strong>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between flex-wrap gap-2 py-2 border-bottom">
                                        <span class="text-muted">Начало:</span>
                                        <strong>{{ $course->start_date->format('d.m.Y') }}</strong>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between flex-wrap gap-2 py-2 border-bottom">
                                        <span class="text-muted">Стоимость:</span>
                                        <strong class="text-primary fs-5">
                                            {{ number_format($course->price, 0, ',', ' ') }} ₽
                                        </strong>
                                    </div>
                                </div>
                                @if($course->max_students)
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between flex-wrap gap-2 py-2">
                                            <span class="text-muted">Места:</span>
                                            <div class="text-end">
                                                <strong>{{ $course->available_spots }} из {{ $course->max_students }}</strong>
                                                <div class="progress mt-2" style="height: 8px;">
                                                    @php
                                                        $percentFilled = (($course->max_students - $course->available_spots) / $course->max_students) * 100;
                                                    @endphp
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percentFilled }}%;" aria-valuenow="{{ $percentFilled }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="mt-4">
                                    <a href="{{ route('applications.create') }}?course_id={{ $course->id }}" 
                                       class="btn btn-primary w-100 btn-lg py-2 py-md-3">
                                        <i class="bi bi-plus-circle"></i> Подать заявку
                                    </a>
                                    <div class="text-center mt-3">
                                        <small class="text-muted">
                                            <i class="bi bi-people"></i> Заявок на курс: {{ $course->applications_count }} 
                                            @if($course->max_students)
                                                (занято: {{ $course->max_students - $course->available_spots }} мест)
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <h5 class="mb-3 fw-bold"><i class="bi bi-list-check"></i> Что вы узнаете</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item bg-transparent px-0 py-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Основы и продвинутые техники
                            </li>
                            <li class="list-group-item bg-transparent px-0 py-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Практические задания и проекты
                            </li>
                            <li class="list-group-item bg-transparent px-0 py-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Поддержка преподавателя
                            </li>
                            <li class="list-group-item bg-transparent px-0 py-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Сертификат по окончании
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-3 fw-bold"><i class="bi bi-person-check"></i> Для кого этот курс</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item bg-transparent px-0 py-2">
                                <i class="bi bi-person me-2"></i>
                                Начинающие специалисты
                            </li>
                            <li class="list-group-item bg-transparent px-0 py-2">
                                <i class="bi bi-person me-2"></i>
                                Желающие сменить профессию
                            </li>
                            <li class="list-group-item bg-transparent px-0 py-2">
                                <i class="bi bi-person me-2"></i>
                                Для повышения квалификации
                            </li>
                            <li class="list-group-item bg-transparent px-0 py-2">
                                <i class="bi bi-person me-2"></i>
                                Предприниматели и владельцы бизнеса
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        @if($similarCourses->count() > 0)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-info text-white p-3">
                    <h5 class="mb-0 fs-6 fs-md-5 fw-bold"><i class="bi bi-stars"></i> Похожие курсы</h5>
                </div>
                <div class="card-body p-3">
                    @foreach($similarCourses as $similarCourse)
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body p-3">
                                <h6 class="card-title fw-bold mb-2" style="word-break: keep-all;">{{ $similarCourse->name }}</h6>
                                <p class="card-text small text-muted mb-2" style="text-align: justify;">{{ Str::limit($similarCourse->description, 60) }}</p>
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <span class="text-primary fw-bold">
                                        {{ number_format($similarCourse->price, 0, ',', ' ') }} ₽
                                    </span>
                                    <a href="{{ route('course.show', $similarCourse) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                        Подробнее <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light p-3">
                <h5 class="mb-0 fs-6 fs-md-5 fw-bold"><i class="bi bi-question-circle"></i> Частые вопросы</h5>
            </div>
            <div class="card-body p-3">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item border-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed py-2 py-md-3 bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                <i class="bi bi-question-circle me-2"></i> Как проходит обучение?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body small" style="text-align: justify;">
                                Обучение проходит онлайн с доступом к материалам 24/7. 
                                Еженедельные вебинары и практические задания.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed py-2 py-md-3 bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                <i class="bi bi-calendar me-2"></i> Когда можно начать обучение?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body small" style="text-align: justify;">
                                Обучение начнется {{ $course->start_date->format('d.m.Y') }}. 
                                Вы можете подать заявку прямо сейчас.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed py-2 py-md-3 bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                <i class="bi bi-award me-2"></i> Выдается ли сертификат?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body small" style="text-align: justify;">
                                Да, по окончании курса вы получите сертификат о дополнительном 
                                профессиональном образовании.
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
    
    /* Выравнивание текста */
    p, .card-text, .lead, .accordion-body {
        text-align: justify;
    }
    
    /* Заголовки */
    .fw-bold {
        font-weight: 600 !important;
    }
    
    /* Стики-блок на десктопе */
    @media (min-width: 992px) {
        .sticky-md-top {
            position: sticky;
            top: 20px;
        }
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
        
        .lead {
            font-size: 0.9rem;
        }
        
        .btn-lg {
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
        }
        
        .fs-5 {
            font-size: 0.95rem !important;
        }
        
        /* Улучшение читаемости на мобильных */
        .list-group-item {
            font-size: 0.85rem;
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }
        
        small {
            font-size: 0.7rem;
        }
        
        /* Аккордеон на мобильных */
        .accordion-button {
            font-size: 0.85rem;
            padding: 0.7rem 1rem;
        }
        
        .accordion-button i {
            font-size: 0.85rem;
        }
        
        .accordion-body {
            font-size: 0.8rem;
            padding: 0.7rem 1rem;
        }
        
        /* Границы */
        .border-bottom {
            border-bottom: 1px solid #dee2e6 !important;
        }
    }
    
    @media (max-width: 576px) {
        /* Дополнительные корректировки для очень маленьких экранов */
        .container {
            padding-left: 12px;
            padding-right: 12px;
        }
        
        .btn {
            white-space: normal;
            word-break: break-word;
        }
        
        /* Уменьшаем отступы в карточках */
        .card-body {
            padding: 0.75rem !important;
        }
        
        /* Прогресс-бар */
        .progress {
            height: 5px !important;
        }
        
        /* Заголовки */
        h4 {
            font-size: 1.1rem !important;
        }
        
        h5 {
            font-size: 0.9rem !important;
        }
        
        .lead {
            font-size: 0.85rem;
        }
        
        /* Кнопки */
        .btn-sm {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }
        
        /* Бейджи */
        .badge {
            font-size: 0.65rem;
            padding: 0.25rem 0.5rem;
        }
        
        /* Аккордеон */
        .accordion-button {
            font-size: 0.8rem;
            padding: 0.6rem 0.75rem;
        }
        
        .accordion-body {
            font-size: 0.75rem;
            padding: 0.6rem 0.75rem;
        }
    }
    
    /* Анимация для кнопок при нажатии */
    .btn:active {
        transform: scale(0.98);
        transition: transform 0.1s ease;
    }
    
    /* Улучшенные отступы для списков */
    .list-group-item {
        border-left: none;
        border-right: none;
        transition: background-color 0.2s ease;
    }
    
    .list-group-item:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
    
    /* Алерты на мобильных */
    @media (max-width: 768px) {
        .alert {
            padding: 0.7rem 1rem;
            font-size: 0.8rem;
        }
        
        .alert .btn-close {
            padding: 0.7rem;
        }
    }
    
    /* Сетка с улучшенными отступами */
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
    
    /* Иконки в списках */
    .bi-check-circle, .bi-person {
        font-size: 0.95rem;
    }
    
    @media (max-width: 768px) {
        .bi-check-circle, .bi-person {
            font-size: 0.85rem;
        }
    }
    
    /* Предотвращение горизонтального скролла */
    body {
        overflow-x: hidden;
    }
    
    /* Улучшение читаемости */
    .text-muted {
        font-size: 0.85rem;
    }
    
    /* Карточки похожих курсов */
    .card.border-0.shadow-sm {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card.border-0.shadow-sm:active {
        transform: scale(0.99);
    }
    
    @media (min-width: 768px) {
        .card.border-0.shadow-sm:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
        }
    }
    
    /* Прогресс-бар */
    .progress {
        background-color: #e9ecef;
        border-radius: 10px;
    }
    
    .progress-bar {
        border-radius: 10px;
        transition: width 0.3s ease;
    }
    
    /* Аккордеон */
    .accordion-item {
        background: transparent;
    }
    
    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(13, 110, 253, 0.25);
    }
    
    .accordion-button:not(.collapsed) {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }
    
    /* Разделители */
    .border-bottom {
        border-bottom-color: #e9ecef !important;
    }
    
    /* Запрет переноса слов для названий курсов */
    .card-title {
        word-break: keep-all;
    }
    
    @media (max-width: 576px) {
        .card-title {
            word-break: break-word;
        }
    }
</style>
@endpush