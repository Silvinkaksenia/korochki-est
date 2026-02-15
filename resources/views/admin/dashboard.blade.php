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

                <h4 class="mb-3">Все заявки</h4>
                @if($applications->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Пользователь</th>
                                    <th>Курс</th>
                                    <th>Дата начала</th>
                                    <th>Оплата</th>
                                    <th>Статус</th>
                                    <th>Дата создания</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applications as $application)
                                    <tr>
                                        <td>#{{ $application->id }}</td>
                                        <td>
                                            <!-- Ссылка на карточку пользователя -->
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <h3 class="mt-3">Заявок нет</h3>
                        <p class="text-muted">Пользователи еще не создали заявок</p>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary mt-3">
                            <i class="bi bi-people"></i> Посмотреть пользователей
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@php
    use App\Models\Application;
    $stats = [
        'total' => Application::count(),
        'new' => Application::where('status', 'new')->count(),
        'in_progress' => Application::where('status', 'in_progress')->count(),
        'completed' => Application::where('status', 'completed')->count(),
    ];
@endphp