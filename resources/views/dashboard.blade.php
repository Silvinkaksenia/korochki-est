@extends('layouts.app')

@section('title', 'Главная')
@section('slider', false)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body">
                <h1 class="display-5">Добро пожаловать, {{ Auth::user()->full_name }}!</h1>
                <p class="lead">Выберите курс дополнительного профессионального образования и подайте заявку на обучение.</p>
                <a href="{{ route('applications.create') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle"></i> Подать новую заявку
                </a>
            </div>
        </div>
    </div>
</div>

<h3 class="mb-4">Популярные курсы</h3>
<div class="row g-4">
    @if($popularCourses->count() > 0)
        @foreach($popularCourses as $course)
            <div class="col-md-3">
                <div class="card h-100 course-card shadow-sm">
                    <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                         class="card-img-top" alt="{{ $course->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $course->name }}</h5>
                        <p class="card-text">{{ Str::limit($course->description, 100) }}</p>
                        @if($course->start_date)
                            <p class="card-text">
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i> 
                                    Начало: {{ $course->start_date->format('d.m.Y') }}
                                </small>
                            </p>
                        @endif
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">
                                    <i class="bi bi-clock"></i> {{ $course->duration_hours }} ч.
                                </span>
                                @if($course->max_students)
                                    <span class="text-muted ms-3">
                                        <i class="bi bi-people"></i> {{ $course->available_spots }} мест
                                    </span>
                                @endif
                            </div>
                            <span class="fw-bold text-primary">
                                {{ number_format($course->price, 0, ',', ' ') }} ₽
                            </span>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('course.show', $course) }}" class="btn btn-outline-primary btn-sm w-100">
                                <i class="bi bi-info-circle"></i> Подробнее
                            </a>
                            <a href="{{ route('applications.create') }}?course_id={{ $course->id }}" 
                               class="btn btn-primary btn-sm w-100 mt-2">
                                <i class="bi bi-plus-circle"></i> Подать заявку
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> На данный момент нет доступных курсов. Следите за обновлениями!
            </div>
        </div>
    @endif
</div>

<div class="row mt-5">
    <div class="col-md-8">
        <h3>Последние заявки</h3>
        @if($userApplications->count() > 0)
            <div class="list-group">
                @foreach($userApplications->take(3) as $application)
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">{{ $application->course->name }}</h5>
                            <span class="badge @if($application->status == 'new') bg-primary 
                                           @elseif($application->status == 'in_progress') bg-warning 
                                           @else bg-success @endif">
                                {{ $application->status == 'new' ? 'Новая' : 
                                   ($application->status == 'in_progress' ? 'Идет обучение' : 'Завершено') }}
                            </span>
                        </div>
                        <p class="mb-1">Дата начала: {{ $application->desired_start_date->format('d.m.Y') }}</p>
                        <small>Способ оплаты: {{ $application->payment_method == 'cash' ? 'Наличные' : 'Перевод' }}</small>
                    </div>
                @endforeach
            </div>
            @if($userApplications->count() > 3)
                <div class="mt-3">
                    <a href="{{ route('applications.index') }}" class="btn btn-outline-primary btn-sm">
                        Показать все заявки ({{ $userApplications->count() }})
                    </a>
                </div>
            @endif
        @else
            <div class="alert alert-info">
                У вас пока нет заявок. <a href="{{ route('applications.create') }}">Создайте первую заявку!</a>
            </div>
        @endif
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Информация</h5>
            </div>
            <div class="card-body">
                <p><strong>Ваш логин:</strong> {{ Auth::user()->login }}</p>
                <p><strong>Телефон:</strong> {{ Auth::user()->phone }}</p>
                <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                <hr>
                <p class="small text-muted">
                    <i class="bi bi-lightbulb"></i> Все заявки рассматриваются администратором в течение 24 часов.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection