@extends('layouts.app')

@section('title', 'Карточка пользователя')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-person-badge"></i> Карточка пользователя</h4>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left"></i> Назад к списку
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <div class="mb-3">
                            <i class="bi bi-person-circle display-1 text-primary"></i>
                        </div>
                        @if($user->is_admin)
                            <span class="badge bg-danger fs-6">Администратор</span>
                        @else
                            <span class="badge bg-secondary fs-6">Пользователь</span>
                        @endif
                    </div>
                    
                    <div class="col-md-8">
                        <h5 class="mb-3">Основная информация</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Логин:</th>
                                <td><strong>{{ $user->login }}</strong></td>
                            </tr>
                            <tr>
                                <th>ФИО:</th>
                                <td>{{ $user->full_name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>
                                    <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th>Телефон:</th>
                                <td>{{ $user->phone }}</td>
                            </tr>
                            <tr>
                                <th>Дата регистрации:</th>
                                <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Последнее обновление:</th>
                                <td>{{ $user->updated_at->format('d.m.Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <hr>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <h5><i class="bi bi-list-check"></i> Заявки пользователя</h5>
                        @if($user->applications->count() > 0)
                            <div class="list-group">
                                @foreach($user->applications->take(5) as $application)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between">
                                            <strong>{{ $application->course->name }}</strong>
                                            <span class="badge 
                                                @if($application->status == 'new') bg-primary
                                                @elseif($application->status == 'in_progress') bg-warning
                                                @else bg-success @endif">
                                                {{ $application->status == 'new' ? 'Новая' : 
                                                   ($application->status == 'in_progress' ? 'В процессе' : 'Завершено') }}
                                            </span>
                                        </div>
                                        <small class="text-muted">
                                            Дата начала: {{ $application->desired_start_date->format('d.m.Y') }}<br>
                                            Способ оплаты: {{ $application->payment_method == 'cash' ? 'Наличные' : 'Перевод' }}
                                        </small>
                                    </div>
                                @endforeach
                            </div>
                            @if($user->applications->count() > 5)
                                <div class="mt-2">
                                    <small class="text-muted">
                                        Показано 5 из {{ $user->applications->count() }} заявок
                                    </small>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> У пользователя нет заявок
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-md-6">
                        <h5><i class="bi bi-star"></i> Отзывы пользователя</h5>
                        @if($user->reviews->count() > 0)
                            <div class="list-group">
                                @foreach($user->reviews->take(5) as $review)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong>Курс:</strong> {{ $review->application->course->name }}<br>
                                                <div class="text-warning">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                                    @endfor
                                                    <small>({{ $review->rating }}/5)</small>
                                                </div>
                                            </div>
                                            <small class="text-muted">
                                                {{ $review->created_at->format('d.m.Y') }}
                                            </small>
                                        </div>
                                        @if($review->comment)
                                            <div class="mt-2">
                                                <strong>Комментарий:</strong>
                                                <p class="mb-0 small">{{ Str::limit($review->comment, 100) }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @if($user->reviews->count() > 5)
                                <div class="mt-2">
                                    <small class="text-muted">
                                        Показано 5 из {{ $user->reviews->count() }} отзывов
                                    </small>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> Пользователь не оставлял отзывов
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6><i class="bi bi-graph-up"></i> Статистика по пользователю</h6>
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <div class="p-3">
                                            <h3 class="text-primary">{{ $user->applications_count ?? $user->applications->count() }}</h3>
                                            <p class="mb-0">Всего заявок</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="p-3">
                                            <h3 class="text-warning">{{ $user->applications->where('status', 'new')->count() }}</h3>
                                            <p class="mb-0">Новых</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="p-3">
                                            <h3 class="text-info">{{ $user->applications->where('status', 'in_progress')->count() }}</h3>
                                            <p class="mb-0">В процессе</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="p-3">
                                            <h3 class="text-success">{{ $user->applications->where('status', 'completed')->count() }}</h3>
                                            <p class="mb-0">Завершено</p>
                                        </div>
                                    </div>
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