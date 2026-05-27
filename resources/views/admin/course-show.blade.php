@extends('layouts.app')

@section('title', 'Информация о курсе')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-book"></i> Информация о курсе</h4>
                    <div>
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-light btn-sm me-2">
                            <i class="bi bi-arrow-left"></i> Назад
                        </a>
                        <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-light btn-sm">
                            <i class="bi bi-pencil"></i> Редактировать
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="mb-3">{{ $course->name }}</h3>
                        <p class="lead">{{ $course->description }}</p>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Основная информация</h6>
                                        <table class="table table-borderless">
                                            <tr>
                                                <th>Длительность:</th>
                                                <td>{{ $course->duration_hours }} часов</td>
                                            </tr>
                                            <tr>
                                                <th>Цена:</th>
                                                <td class="fw-bold text-primary">
                                                    {{ number_format($course->price, 0, ',', ' ') }} ₽
                                                </td>
                                            </tr>
                                            <tr>
                                            <tr>
                                                <th>Дата начала:</th>
                                                <td>
                                                     @if($course->start_date)
                                                     {{ $course->start_date->format('d.m.Y') }}
                                                     @else
                                                     <span class="text-muted">Не указана</span>
                                                     @endif
                                                    </td>
                                                </tr>
                                            </tr>
                                            <tr>
                                                <th>Статус:</th>
                                                <td>
                                                    @if($course->is_active && $course->is_available)
                                                        <span class="badge bg-success">Доступен для записи</span>
                                                    @elseif($course->is_active && !$course->is_available)
                                                        <span class="badge bg-warning">Заполнен или дата прошла</span>
                                                    @else
                                                        <span class="badge bg-secondary">Неактивен</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @if($course->max_students)
                                                <tr>
                                                    <th>Места:</th>
                                                    <td>
                                                        {{ $course->available_spots }} из {{ $course->max_students }} свободно
                                                    </td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h5 class="mb-3"><i class="bi bi-people"></i> Заявки на курс</h5>
                        @if($course->applications->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
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
                                                <td>#{{ $application->id }}</td>
                                                <td>
                                                    <a href="{{ route('admin.users.show', $application->user) }}" 
                                                       class="text-decoration-none">
                                                        {{ $application->user->full_name }}
                                                    </a>
                                                </td>
                                                <td>{{ $application->created_at->format('d.m.Y') }}</td>
                                                <td>
                                                    <span class="badge 
                                                        @if($application->status == 'new') bg-primary
                                                        @elseif($application->status == 'in_progress') bg-warning
                                                        @else bg-success @endif">
                                                        {{ $application->status == 'new' ? 'Новая' : 
                                                           ($application->status == 'in_progress' ? 'В процессе' : 'Завершено') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ $application->payment_method == 'cash' ? 'Наличные' : 'Перевод' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> На этот курс пока нет заявок
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Статистика курса</h6>
                                <div class="text-center mb-4">
                                    <div class="display-4 text-primary">{{ $course->applications->count() }}</div>
                                    <p class="mb-0">Всего заявок</p>
                                </div>
                                
                                <div class="row text-center">
                                    <div class="col-6 mb-3">
                                        <div class="p-2">
                                            <h5 class="text-primary">{{ $course->applications->where('status', 'new')->count() }}</h5>
                                            <small>Новых</small>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="p-2">
                                            <h5 class="text-warning">{{ $course->applications->where('status', 'in_progress')->count() }}</h5>
                                            <small>В процессе</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2">
                                            <h5 class="text-success">{{ $course->applications->where('status', 'completed')->count() }}</h5>
                                            <small>Завершено</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2">
                                            @php
                                                $withReview = $course->applications->where('review')->count();
                                            @endphp
                                            <h5 class="text-info">{{ $withReview }}</h5>
                                            <small>С отзывами</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr>
                                
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