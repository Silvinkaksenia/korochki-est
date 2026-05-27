@extends('layouts.app')

@section('title', 'Добавление курса')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Добавление нового курса</h4>
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left"></i> Назад
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.courses.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Название курса *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Описание курса *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="duration_hours" class="form-label">Длительность (часов) *</label>
                            <input type="number" class="form-control @error('duration_hours') is-invalid @enderror" 
                                   id="duration_hours" name="duration_hours" 
                                   value="{{ old('duration_hours') }}" min="1" required>
                            @error('duration_hours')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Цена (руб.) *</label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price') }}" min="0" step="0.01" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Дата начала курса *</label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" name="start_date" 
                                   value="{{ old('start_date') }}" required
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Дата должна быть не ранее завтрашнего дня</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="max_students" class="form-label">Максимум студентов</label>
                            <input type="number" class="form-control @error('max_students') is-invalid @enderror" 
                                   id="max_students" name="max_students" 
                                   value="{{ old('max_students') }}" min="1">
                            @error('max_students')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Оставьте пустым для неограниченного количества</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" 
                                   id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">
                                Активный курс (доступен для записи)
                            </label>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h5><i class="bi bi-info-circle"></i> Важно!</h5>
                        <p class="mb-0">
                            После создания курса он станет доступен для записи пользователям.
                            Вы всегда можете отредактировать или деактивировать курс позже.
                        </p>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-check-circle"></i> Создать курс
                        </button>
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Отмена
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection