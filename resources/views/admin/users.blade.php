@extends('layouts.app')

@section('title', 'Пользователи')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-people"></i> Пользователи системы</h4>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left"></i> Назад к заявкам
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- ФОРМА ПОИСКА И ФИЛЬТРАЦИИ -->
                <div class="card mb-4 bg-light">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.users.index') }}" id="filter-form">
                            <div class="row g-3">
                                <!-- Поиск по тексту -->
                                <div class="col-md-6">
                                    <label for="search" class="form-label">Поиск по пользователям</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="search" 
                                           name="search" 
                                           value="{{ request('search') }}"
                                           placeholder="Введите логин, имя, email или телефон...">
                                    <div class="form-text">Поиск по логину, ФИО, email и телефону</div>
                                </div>

                                <!-- Фильтр по роли -->
                                <div class="col-md-3">
                                    <label for="role" class="form-label">Роль</label>
                                    <select class="form-select" id="role" name="role">
                                        <option value="">Все пользователи</option>
                                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Обычные пользователи</option>
                                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Администраторы</option>
                                    </select>
                                </div>

                                <!-- Сортировка -->
                                <div class="col-md-2">
                                    <label for="sort" class="form-label">Сортировка</label>
                                    <select class="form-select" id="sort" name="sort">
                                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>По дате</option>
                                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>По имени</option>
                                        <option value="login" {{ request('sort') == 'login' ? 'selected' : '' }}>По логину</option>
                                        <option value="applications_count" {{ request('sort') == 'applications_count' ? 'selected' : '' }}>По заявкам</option>
                                    </select>
                                </div>

                                <div class="col-md-1">
                                    <label for="direction" class="form-label">Напр.</label>
                                    <select class="form-select" id="direction" name="direction">
                                        <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>↑</option>
                                        <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>↓</option>
                                    </select>
                                </div>

                                <div class="col-md-12 d-flex justify-content-between mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search"></i> Применить фильтры
                                    </button>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle"></i> Сбросить
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Результаты поиска -->
                @if(request()->anyFilled(['search', 'role']))
                    <div class="mb-3">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            Найдено пользователей: <strong>{{ $users->total() }}</strong>
                            @if(request('search'))
                                по запросу "{{ request('search') }}"
                            @endif
                            @if(request('role'))
                                @if(request('role') == 'admin')
                                    (только администраторы)
                                @elseif(request('role') == 'user')
                                    (только пользователи)
                                @endif
                            @endif
                        </div>
                    </div>
                @endif

                @if($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Логин</th>
                                    <th>ФИО</th>
                                    <th>Email</th>
                                    <th>Телефон</th>
                                    <th>Заявок</th>
                                    <th>Роль</th>
                                    <th>Дата регистрации</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>#{{ $user->id }}</td>
                                        <td>
                                            <strong>{{ $user->login }}</strong>
                                            @if($user->is_admin)
                                                <span class="badge bg-danger ms-1">Admin</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->full_name }}</td>
                                        <td>
                                            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                        </td>
                                        <td>{{ $user->phone }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $user->applications_count }}</span>
                                        </td>
                                        <td>
                                            @if($user->is_admin)
                                                <span class="badge bg-danger">Администратор</span>
                                            @else
                                                <span class="badge bg-secondary">Пользователь</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $user) }}" 
                                               class="btn btn-sm btn-primary"
                                               title="Просмотр карточки">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Пагинация -->
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>

                    <!-- Статистика -->
                    <div class="mt-3">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Статистика пользователей</h6>
                                        <div class="d-flex justify-content-between">
                                            <span>Всего на странице:</span>
                                            <strong>{{ $users->count() }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Администраторов:</span>
                                            <strong>{{ $users->where('is_admin', true)->count() }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Пользователей:</span>
                                            <strong>{{ $users->where('is_admin', false)->count() }}</strong>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <span>Всего в системе:</span>
                                            <strong>{{ $users->total() }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-people display-1 text-muted"></i>
                        <h3 class="mt-3">Пользователи не найдены</h3>
                        <p class="text-muted">
                            @if(request()->anyFilled(['search', 'role']))
                                По вашему запросу ничего не найдено. Попробуйте изменить параметры поиска.
                            @else
                                В системе еще не зарегистрированы пользователи
                            @endif
                        </p>
                        @if(request()->anyFilled(['search', 'role']))
                            <a href="{{ route('admin.users.index') }}" class="btn btn-primary mt-3">
                                <i class="bi bi-x-circle"></i> Сбросить фильтры
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