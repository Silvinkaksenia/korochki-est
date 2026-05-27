@extends('layouts.app')

@section('title', 'Панель администратора')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-speedometer2"></i> Панель администратора</h4>
                </div>
            </div>
            <div class="card-body">
                <!-- Статистика -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h1 class="display-4">{{ $stats['total'] }}</h1>
                                <p class="mb-0">Всего заявок</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h1 class="display-4">{{ $stats['new'] }}</h1>
                                <p class="mb-0">Новых</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h1 class="display-4">{{ $stats['in_progress'] }}</h1>
                                <p class="mb-0">В процессе</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h1 class="display-4">{{ $stats['completed'] }}</h1>
                                <p class="mb-0">Завершено</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ФИЛЬТРЫ ДЛЯ ЗАЯВОК -->
                <div class="card mb-4 bg-light">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="bi bi-funnel"></i> Фильтры заявок</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.dashboard') }}" id="filter-form">
                            <div class="row g-3">
                                <!-- Поиск по тексту -->
                                <div class="col-md-4">
                                    <label for="search" class="form-label">Поиск</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="search" 
                                           name="search" 
                                           value="{{ request('search') }}"
                                           placeholder="Имя пользователя, курс, ID...">
                                </div>

                                <!-- Фильтр по статусу -->
                                <div class="col-md-2">
                                    <label for="status" class="form-label">Статус</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">Все статусы</option>
                                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>Новые</option>
                                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>В процессе</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Завершенные</option>
                                    </select>
                                </div>

                                <!-- Фильтр по способу оплаты -->
                                <div class="col-md-2">
                                    <label for="payment" class="form-label">Оплата</label>
                                    <select class="form-select" id="payment" name="payment">
                                        <option value="">Все способы</option>
                                        <option value="cash" {{ request('payment') == 'cash' ? 'selected' : '' }}>Наличные</option>
                                        <option value="transfer" {{ request('payment') == 'transfer' ? 'selected' : '' }}>Перевод</option>
                                    </select>
                                </div>

                                <!-- Фильтр по курсу -->
                                <div class="col-md-4">
                                    <label for="course_id" class="form-label">Курс</label>
                                    <select class="form-select" id="course_id" name="course_id">
                                        <option value="">Все курсы</option>
                                        @php
                                            $courses = App\Models\Course::all();
                                        @endphp
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                                {{ $course->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Фильтр по дате создания (от) -->
                                <div class="col-md-2">
                                    <label for="date_from" class="form-label">Дата от</label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="date_from" 
                                           name="date_from" 
                                           value="{{ request('date_from') }}">
                                </div>

                                <!-- Фильтр по дате создания (до) -->
                                <div class="col-md-2">
                                    <label for="date_to" class="form-label">Дата до</label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="date_to" 
                                           name="date_to" 
                                           value="{{ request('date_to') }}">
                                </div>

                                <!-- Фильтр по желаемой дате начала (от) -->
                                <div class="col-md-2">
                                    <label for="start_date_from" class="form-label">Начало от</label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="start_date_from" 
                                           name="start_date_from" 
                                           value="{{ request('start_date_from') }}">
                                </div>

                                <!-- Фильтр по желаемой дате начала (до) -->
                                <div class="col-md-2">
                                    <label for="start_date_to" class="form-label">Начало до</label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="start_date_to" 
                                           name="start_date_to" 
                                           value="{{ request('start_date_to') }}">
                                </div>

                                <!-- Фильтр по наличию отзыва -->
                                <div class="col-md-2">
                                    <label for="has_review" class="form-label">Отзыв</label>
                                    <select class="form-select" id="has_review" name="has_review">
                                        <option value="">Все</option>
                                        <option value="yes" {{ request('has_review') == 'yes' ? 'selected' : '' }}>Есть отзыв</option>
                                        <option value="no" {{ request('has_review') == 'no' ? 'selected' : '' }}>Нет отзыва</option>
                                    </select>
                                </div>

                                <!-- Сортировка -->
                                <div class="col-md-2">
                                    <label for="sort" class="form-label">Сортировка</label>
                                    <select class="form-select" id="sort" name="sort">
                                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>По дате создания</option>
                                        <option value="desired_start_date" {{ request('sort') == 'desired_start_date' ? 'selected' : '' }}>По дате начала</option>
                                        <option value="user_name" {{ request('sort') == 'user_name' ? 'selected' : '' }}>По имени</option>
                                        <option value="course_name" {{ request('sort') == 'course_name' ? 'selected' : '' }}>По курсу</option>
                                    </select>
                                </div>

                                <div class="col-md-1">
                                    <label for="direction" class="form-label">Напр.</label>
                                    <select class="form-select" id="direction" name="direction">
                                        <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>↑</option>
                                        <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>↓</option>
                                    </select>
                                </div>

                                <div class="col-md-3 d-flex align-items-end">
                                    <div class="d-grid gap-2 w-100">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-search"></i> Применить фильтры
                                        </button>
                                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-x-circle"></i> Сбросить
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Информация о результатах -->
                @if(request()->anyFilled(['search', 'status', 'payment', 'course_id', 'date_from', 'date_to', 'start_date_from', 'start_date_to', 'has_review']))
                    <div class="mb-3">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            Найдено заявок: <strong>{{ $applications->total() }}</strong>
                            @if(request('search')) по запросу "{{ request('search') }}"@endif
                            @if(request('status')) 
                                @if(request('status') == 'new') (новые)
                                @elseif(request('status') == 'in_progress') (в процессе)
                                @elseif(request('status') == 'completed') (завершенные)
                                @endif
                            @endif
                        </div>
                    </div>
                @endif

                <h4 class="mb-3">
                    Все заявки 
                    @if($applications->total() > 0)
                        <small class="text-muted">(показано {{ $applications->firstItem() }}-{{ $applications->lastItem() }} из {{ $applications->total() }})</small>
                    @endif
                </h4>

                @if($applications->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Пользователь</th>
                                    <th>Курс</th>
                                    <th>Желаемая дата</th>
                                    <th>Оплата</th>
                                    <th>Статус</th>
                                    <th>Дата создания</th>
                                    <th>Отзыв</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applications as $application)
                                    <tr>
                                        <td>#{{ $application->id }}</td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $application->user) }}" 
                                               class="text-decoration-none"
                                               title="Перейти к карточке пользователя">
                                                <strong class="text-primary">{{ $application->user->full_name }}</strong>
                                                <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                            </a>
                                            <br>
                                            <small class="text-muted">{{ $application->user->login }}</small>
                                            @if($application->user->is_admin)
                                                <span class="badge bg-danger ms-1">Admin</span>
                                            @endif
                                        </td>
                                        <td>{{ $application->course->name }}</td>
                                        <td>{{ $application->desired_start_date->format('d.m.Y') }}</td>
                                        <td>
                                            @if($application->payment_method == 'cash')
                                                <span class="badge bg-secondary">Наличные</span>
                                            @else
                                                <span class="badge bg-info">Перевод</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form method="POST" action="{{ route('admin.applications.update', $application) }}" 
                                                  class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" class="form-select form-select-sm status-select" 
                                                        data-application-id="{{ $application->id }}"
                                                        onchange="this.form.submit()">
                                                    <option value="new" {{ $application->status == 'new' ? 'selected' : '' }}>Новая</option>
                                                    <option value="in_progress" {{ $application->status == 'in_progress' ? 'selected' : '' }}>Идет обучение</option>
                                                    <option value="completed" {{ $application->status == 'completed' ? 'selected' : '' }}>Завершено</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>{{ $application->created_at->format('d.m.Y H:i') }}</td>
                                        <td>
                                            @if($application->review)
                                                <button type="button" class="btn btn-sm btn-outline-info" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#reviewModal{{ $application->id }}">
                                                    <i class="bi bi-star-fill"></i> Отзыв
                                                </button>
                                                
                                                <!-- Модальное окно с отзывом -->
                                                <div class="modal fade" id="reviewModal{{ $application->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Отзыв от {{ $application->user->full_name }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <strong>Курс:</strong> {{ $application->course->name }}
                                                                </div>
                                                                <div class="mb-3">
                                                                    <strong>Оценка:</strong>
                                                                    <div class="text-warning">
                                                                        @for($i = 1; $i <= 5; $i++)
                                                                            <i class="bi bi-star{{ $i <= $application->review->rating ? '-fill' : '' }}"></i>
                                                                        @endfor
                                                                        ({{ $application->review->rating }}/5)
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <strong>Комментарий:</strong>
                                                                    <p class="mt-2">{{ $application->review->comment ?? 'Без комментария' }}</p>
                                                                </div>
                                                                <div class="text-end">
                                                                    <a href="{{ route('admin.users.show', $application->user) }}" 
                                                                       class="btn btn-sm btn-outline-primary">
                                                                        <i class="bi bi-person"></i> Перейти к пользователю
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">Нет отзыва</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.users.show', $application->user) }}" 
                                                   class="btn btn-outline-primary" title="Пользователь">
                                                    <i class="bi bi-person"></i>
                                                </a>
                                                <a href="{{ route('admin.courses.show', $application->course) }}" 
                                                   class="btn btn-outline-success" title="Курс">
                                                    <i class="bi bi-book"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Пагинация -->
                    <div class="mt-4">
                        {{ $applications->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <h3 class="mt-3">Заявок нет</h3>
                        <p class="text-muted">
                            @if(request()->anyFilled(['search', 'status', 'payment', 'course_id', 'date_from', 'date_to', 'start_date_from', 'start_date_to', 'has_review']))
                                По вашему запросу ничего не найдено. Попробуйте изменить параметры фильтрации.
                            @else
                                Пользователи еще не создали заявок
                            @endif
                        </p>
                        @if(request()->anyFilled(['search', 'status', 'payment', 'course_id', 'date_from', 'date_to', 'start_date_from', 'start_date_to', 'has_review']))
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary mt-3">
                                <i class="bi bi-x-circle"></i> Сбросить фильтры
                            </a>
                        @else
                            <a href="{{ route('admin.users.index') }}" class="btn btn-primary mt-3">
                                <i class="bi bi-people"></i> Посмотреть пользователей
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