@extends('layouts.app')

@section('title', 'Управление курсами')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-book"></i> Управление курсами</h4>
                    <div>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm me-2">
                            <i class="bi bi-arrow-left"></i> Назад
                        </a>
                        <a href="{{ route('admin.courses.create') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-plus-circle"></i> Добавить курс
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if($courses->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Название курса</th>
                                    <th>Дата начала</th>
                                    <th>Длительность</th>
                                    <th>Цена</th>
                                    <th>Статус</th>
                                    <th>Заявок</th>
                                    <th>Доступных мест</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courses as $course)
                                    <tr>
                                        <td>#{{ $course->id }}</td>
                                        <td>
                                            <strong>{{ $course->name }}</strong><br>
                                            <small class="text-muted">{{ Str::limit($course->description, 50) }}</small>
                                        </td>
                                        <td>
    @if($course->start_date)
        {{ $course->start_date->format('d.m.Y') }}
    @else
        <span class="text-muted">Не указана</span>
    @endif
</td>
                                        <td>{{ $course->duration_hours }} ч.</td>
                                        <td>
                                            <span class="fw-bold text-primary">
                                                {{ number_format($course->price, 0, ',', ' ') }} ₽
                                            </span>
                                        </td>
                                        <td>
                                            @if($course->is_active && $course->is_available)
                                                <span class="badge bg-success">Доступен</span>
                                            @elseif($course->is_active && !$course->is_available)
                                                <span class="badge bg-warning">Заполнен</span>
                                            @else
                                                <span class="badge bg-secondary">Неактивен</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $course->applications_count }}</span>
                                        </td>
                                        <td>
                                            @if($course->max_students)
                                                {{ $course->available_spots }} из {{ $course->max_students }}
                                            @else
                                                <span class="text-muted">Без ограничений</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.courses.show', $course) }}" 
                                                   class="btn btn-outline-primary" title="Просмотр">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.courses.edit', $course) }}" 
                                                   class="btn btn-outline-warning" title="Редактировать">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" 
                                                      class="d-inline" onsubmit="return confirm('Удалить курс?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Удалить">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Статистика курсов</h6>
                                        <div class="d-flex justify-content-between">
                                            <span>Всего курсов:</span>
                                            <strong>{{ $courses->count() }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Активных:</span>
                                            <strong>{{ $courses->where('is_active', true)->count() }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Доступных:</span>
                                            <strong>{{ $courses->where('is_available', true)->count() }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Всего заявок:</span>
                                            <strong>{{ $courses->sum('applications_count') }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-book display-1 text-muted"></i>
                        <h3 class="mt-3">Курсов нет</h3>
                        <p class="text-muted">Добавьте первый курс для обучения</p>
                        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary mt-3">
                            <i class="bi bi-plus-circle"></i> Добавить курс
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection