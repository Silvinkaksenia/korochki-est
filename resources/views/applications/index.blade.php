@extends('layouts.app')

@section('title', 'Мои заявки')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-list-check"></i> Мои заявки</h4>
                <a href="{{ route('applications.create') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-plus-circle"></i> Новая заявка
                </a>
            </div>
            <div class="card-body">
                @if($applications->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Курс</th>
                                    <th>Дата начала</th>
                                    <th>Способ оплаты</th>
                                    <th>Статус</th>
                                    <th>Дата создания</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applications as $application)
                                    <tr>
                                        <td>
                                            <strong>{{ $application->course->name }}</strong><br>
                                            <small class="text-muted">{{ number_format($application->course->price, 0, ',', ' ') }} ₽</small>
                                        </td>
                                        <td>{{ $application->desired_start_date->format('d.m.Y') }}</td>
                                        <td>
                                            @if($application->payment_method == 'cash')
                                                <span class="badge bg-secondary">Наличные</span>
                                            @else
                                                <span class="badge bg-info">Перевод</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($application->status == 'new')
                                                <span class="badge bg-primary">Новая</span>
                                            @elseif($application->status == 'in_progress')
                                                <span class="badge bg-warning">Идет обучение</span>
                                            @else
                                                <span class="badge bg-success">Завершено</span>
                                            @endif
                                        </td>
                                        <td>{{ $application->created_at->format('d.m.Y H:i') }}</td>
                                        <td>
                                            @if($application->status == 'completed' && !$application->review)
                                                <button type="button" class="btn btn-sm btn-success" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#reviewModal{{ $application->id }}">
                                                    <i class="bi bi-star"></i> Оставить отзыв
                                                </button>
                                            @elseif($application->review)
                                                <button type="button" class="btn btn-sm btn-outline-success" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#reviewViewModal{{ $application->id }}">
                                                    <i class="bi bi-star-fill"></i> Ваш отзыв
                                                </button>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                    
                                    <!-- Модальное окно для отзыва -->
                                    @if($application->status == 'completed')
                                        <div class="modal fade" id="reviewModal{{ $application->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('applications.review', $application) }}">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Отзыв о курсе "{{ $application->course->name }}"</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Оценка</label>
                                                                <div class="rating">
                                                                    @for($i = 1; $i <= 5; $i++)
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio" 
                                                                                   name="rating" id="rating{{ $application->id }}_{{ $i }}" 
                                                                                   value="{{ $i }}" required>
                                                                            <label class="form-check-label" for="rating{{ $application->id }}_{{ $i }}">
                                                                                <i class="bi bi-star{{ $i == 1 ? '-fill' : '' }}"></i> {{ $i }}
                                                                            </label>
                                                                        </div>
                                                                    @endfor
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="comment{{ $application->id }}" class="form-label">Комментарий</label>
                                                                <textarea class="form-control" id="comment{{ $application->id }}" 
                                                                          name="comment" rows="3" 
                                                                          placeholder="Расскажите о вашем опыте обучения..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                                            <button type="submit" class="btn btn-primary">Сохранить отзыв</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Модальное окно просмотра отзыва -->
                                        @if($application->review)
                                            <div class="modal fade" id="reviewViewModal{{ $application->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Ваш отзыв о курсе "{{ $application->course->name }}"</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
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
                                                            <div class="text-muted small">
                                                                <i class="bi bi-calendar"></i> 
                                                                {{ $application->review->created_at->format('d.m.Y H:i') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{-- {{ $applications->links() }} --}}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <h3 class="mt-3">Заявок пока нет</h3>
                        <p class="text-muted">У вас нет ни одной заявки на обучение</p>
                        <a href="{{ route('applications.create') }}" class="btn btn-primary mt-2">
                            <i class="bi bi-plus-circle"></i> Создать первую заявку
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection