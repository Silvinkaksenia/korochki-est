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
                
                <!-- ФОРМА ФИЛЬТРАЦИИ -->
                <div class="card mb-4 bg-light">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.courses.index') }}" id="filter-form">
                            <div class="row g-3">
                                <!-- Поиск по названию -->
                                <div class="col-md-6">
                                    <label for="search" class="form-label">Поиск по названию</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="search" 
                                           name="search" 
                                           value="{{ request('search') }}"
                                           placeholder="Введите название курса...">
                                </div>

                                <!-- Фильтр по статусу-->
                                <div class="col-md-6">
                                    <label for="is_active" class="form-label">Статус</label>
                                    <select class="form-select" id="is_active" name="is_active">
                                        <option value="">Все</option>
                                        <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Активные</option>
                                        <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Неактивные</option>
                                    </select>
                                </div>

                                <!-- Фильтр по цене -->
                                <div class="col-md-3">
                                    <label for="price_min" class="form-label">Цена от (₽)</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="price_min" 
                                           name="price_min" 
                                           value="{{ request('price_min') }}"
                                           min="0"
                                           step="100">
                                </div>
                                
                                <div class="col-md-3">
                                    <label for="price_max" class="form-label">Цена до (₽)</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="price_max" 
                                           name="price_max" 
                                           value="{{ request('price_max') }}"
                                           min="0"
                                           step="100">
                                </div>

                                <!-- Фильтр по длительности --
                                <div class="col-md-3">
                                    <label for="duration_min" class="form-label">Длительность от (ч.)</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="duration_min" 
                                           name="duration_min" 
                                           value="{{ request('duration_min') }}"
                                           min="1">
                                </div>
                                
                                <div class="col-md-3">
                                    <label for="duration_max" class="form-label">Длительность до (ч.)</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="duration_max" 
                                           name="duration_max" 
                                           value="{{ request('duration_max') }}"
                                           min="1">
                                </div> -->

                                <!-- Фильтр по дате 
                                <div class="col-md-3">
                                    <label for="date_from" class="form-label">Дата начала от</label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="date_from" 
                                           name="date_from" 
                                           value="{{ request('date_from') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="date_to" class="form-label">Дата начала до</label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="date_to" 
                                           name="date_to" 
                                           value="{{ request('date_to') }}">
                                </div> -->

                                <!-- Сортировка -->
                                <div class="col-md-4">
                                    <label for="sort" class="form-label">Сортировать по</label>
                                    <select class="form-select" id="sort" name="sort">
                                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Дате создания</option>
                                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Названию</option>
                                        <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Цене</option>
                                        <option value="duration_hours" {{ request('sort') == 'duration_hours' ? 'selected' : '' }}>Длительности</option>
                                        <option value="start_date" {{ request('sort') == 'start_date' ? 'selected' : '' }}>Дате начала курса</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="direction" class="form-label">Направление</label>
                                    <select class="form-select" id="direction" name="direction">
                                        <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>По возрастанию</option>
                                        <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>По убыванию</option>
                                    </select>
                                </div>

                                <div class="col-md-4 d-flex align-items-end">
                                    <div class="d-grid gap-2 w-100">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-search"></i> Применить фильтры
                                        </button>
                                        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-x-circle"></i> Сбросить
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Информация о количестве найденных курсов -->
                <div class="mb-3">
                    <p class="text-muted">
                        Найдено курсов: <strong>{{ $courses->total() }}</strong>
                    </p>
                </div>

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
                                                {{ $course->price ? number_format($course->price, 0, ',', ' ') . ' ₽' : 'Бесплатно' }}
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
                                                {{ $course->available_spots ?? 0 }} / {{ $course->max_students }}
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
                                                    <button type="submit" class="btn btn-outline-danger" title="Удалить"
                                                            {{ $course->applications_count > 0 ? 'disabled' : '' }}>
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
                        {{ $courses->links() }}
                    </div>

                    <div class="mt-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Статистика на этой странице</h6>
                                        <div class="d-flex justify-content-between">
                                            <span>Курсов на странице:</span>
                                            <strong>{{ $courses->count() }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Активных:</span>
                                            <strong>{{ $courses->where('is_active', true)->count() }}</strong>
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
                        <p class="text-muted">
                            @if(request()->anyFilled(['search', 'is_active', 'price_min', 'price_max', 'duration_min', 'duration_max', 'date_from', 'date_to']))
                                По вашему запросу ничего не найдено. Попробуйте изменить параметры фильтрации.
                            @else
                                Добавьте первый курс для обучения
                            @endif
                        </p>
                        @if(request()->anyFilled(['search', 'is_active', 'price_min', 'price_max', 'duration_min', 'duration_max', 'date_from', 'date_to']))
                            <a href="{{ route('admin.courses.index') }}" class="btn btn-primary mt-3">
                                <i class="bi bi-x-circle"></i> Сбросить фильтры
                            </a>
                        @else
                            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary mt-3">
                                <i class="bi bi-plus-circle"></i> Добавить курс
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Автоматическая отправка формы при изменении сортировки
    document.getElementById('sort')?.addEventListener('change', function() {
        document.getElementById('filter-form').submit();
    });
    
    document.getElementById('direction')?.addEventListener('change', function() {
        document.getElementById('filter-form').submit();
    });
</script>
@endpush