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
                    
                    <div class="mt-3">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Статистика пользователей</h6>
                                        <div class="d-flex justify-content-between">
                                            <span>Всего:</span>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-people display-1 text-muted"></i>
                        <h3 class="mt-3">Пользователей нет</h3>
                        <p class="text-muted">В системе еще не зарегистрированы пользователи</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection