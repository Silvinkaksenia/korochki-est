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
                @if($courses->count() > 0)
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
                        <p class="text-muted">В данный момент нет активных курсов для записи</p>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">
                            <i class="bi bi-arrow-left"></i> Вернуться на главную
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection