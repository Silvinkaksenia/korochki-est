@extends('layouts.app')

@section('title', $course->name)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-book"></i> {{ $course->name }}</h4>
                    <a href="{{ route('courses.index') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left"></i> Все курсы
                    </a>
                </div>
            </div>
            <div class="card-body">
            @if($course->start_date && $course->start_date < now()->addDays(7))
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle"></i> 
        <strong>Спешите!</strong> Начало курса {{ $course->start_date->format('d.m.Y') }}.
        Осталось всего {{ $course->start_date->diffForHumans(['parts' => 1, 'short' => false]) }}!
    </div>
@endif
                
                <div class="row mb-4">
                    <div class="col-md-8">
                        <h5 class="mb-3">Описание курса</h5>
                        <p class="lead">{{ $course->description }}</p>
                        
                        @if($course->max_students && $course->available_spots <= 5)
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> 
                                Осталось всего {{ $course->available_spots }} мест из {{ $course->max_students }}
                            </div>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Детали курса</h6>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Длительность:</span>
                                        <strong>{{ $course->duration_hours }} часов</strong>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Начало:</span>
                                        <strong>{{ $course->start_date->format('d.m.Y') }}</strong>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Стоимость:</span>
                                        <strong class="text-primary fs-5">
                                            {{ number_format($course->price, 0, ',', ' ') }} ₽
                                        </strong>
                                    </div>
                                </div>
                                @if($course->max_students)
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Места:</span>
                                            <strong>{{ $course->available_spots }} из {{ $course->max_students }}</strong>
                                        </div>
                                    </div>
                                @endif
                                <div class="mt-4">
                                    <a href="{{ route('applications.create') }}?course_id={{ $course->id }}" 
                                       class="btn btn-primary w-100 btn-lg">
                                        <i class="bi bi-plus-circle"></i> Подать заявку
                                    </a>
                                    <div class="text-center mt-2">
                                    <small class="text-muted">
    Заявок на курс: {{ $course->applications_count }} 
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
                
                <div class="row">
                    <div class="col-md-6">
                        <h5><i class="bi bi-list-check"></i> Что вы узнаете</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Основы и продвинутые техники
                            </li>
                            <li class="list-group-item">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Практические задания и проекты
                            </li>
                            <li class="list-group-item">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Поддержка преподавателя
                            </li>
                            <li class="list-group-item">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Сертификат по окончании
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5><i class="bi bi-person-check"></i> Для кого этот курс</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <i class="bi bi-person me-2"></i>
                                Начинающие специалисты
                            </li>
                            <li class="list-group-item">
                                <i class="bi bi-person me-2"></i>
                                Желающие сменить профессию
                            </li>
                            <li class="list-group-item">
                                <i class="bi bi-person me-2"></i>
                                Для повышения квалификации
                            </li>
                            <li class="list-group-item">
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
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-stars"></i> Похожие курсы</h5>
                </div>
                <div class="card-body">
                    @foreach($similarCourses as $similarCourse)
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-title">{{ $similarCourse->name }}</h6>
                                <p class="card-text small">{{ Str::limit($similarCourse->description, 60) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-primary fw-bold">
                                        {{ number_format($similarCourse->price, 0, ',', ' ') }} ₽
                                    </span>
                                    <a href="{{ route('course.show', $similarCourse) }}" class="btn btn-sm btn-outline-primary">
                                        Подробнее
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-question-circle"></i> Частые вопросы</h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Как проходит обучение?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Обучение проходит онлайн с доступом к материалам 24/7. 
                                Еженедельные вебинары и практические задания.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Когда можно начать обучение?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Обучение начнется {{ $course->start_date->format('d.m.Y') }}. 
                                Вы можете подать заявку прямо сейчас.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Выдается ли сертификат?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
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