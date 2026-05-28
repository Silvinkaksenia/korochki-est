@extends('layouts.app')

@section('title', 'Главная')
@section('slider', false)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-primary bg-gradient text-white border-0 shadow">
            <div class="card-body p-4 p-md-5">
                <h1 class="display-5 fw-bold mb-3">Добро пожаловать, {{ Auth::user()->full_name }}!</h1>
                <p class="lead mb-4">Выберите курс дополнительного профессионального образования и подайте заявку на обучение.</p>
                <a href="{{ route('applications.create') }}" class="btn btn-light btn-lg shadow">
                    <i class="bi bi-plus-circle"></i> Подать новую заявку
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для редактирования профиля -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('profile.update') }}" id="profile-form">
                @csrf
                @method('PUT')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editProfileModalLabel">
                        <i class="bi bi-pencil-square"></i> Редактирование профиля
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Сообщения об ошибках -->
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Поле для телефона -->
                    <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold">
                            <i class="bi bi-telephone"></i> Номер телефона
                        </label>
                        <input type="tel" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', Auth::user()->phone) }}"
                               required>
                        <div class="form-text">
                            <i class="bi bi-info-circle"></i> 
                            Введите номер телефона в формате +7 (___) ___-__-__
                        </div>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Поле для email -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">
                            <i class="bi bi-envelope"></i> Email
                        </label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', Auth::user()->email) }}"
                               placeholder="example@mail.com"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Подтверждение пароля для изменений -->
                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-semibold">
                            <i class="bi bi-shield-lock"></i> Текущий пароль <span class="text-danger">*</span>
                        </label>
                        <input type="password" 
                               class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" 
                               name="current_password" 
                               placeholder="Введите пароль для подтверждения изменений"
                               required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Для сохранения изменений необходимо ввести текущий пароль</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Отмена
                    </button>
                    <button type="submit" class="btn btn-primary" id="save-profile-btn">
                        <i class="bi bi-check-circle"></i> Сохранить изменения
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Кнопка для вызова модального окна (скрыта, вызывается из карточки информации) -->
<button type="button" class="btn btn-link d-none" id="editProfileBtn" data-bs-toggle="modal" data-bs-target="#editProfileModal"></button>

<div class="row g-4">
    <!-- Левая колонка - популярные курсы -->
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0"><i class="bi bi-star-fill text-warning"></i> Популярные курсы</h3>
            <a href="{{ route('courses.index') }}" class="btn btn-sm btn-outline-primary">
                Все курсы <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        
        <div class="row g-4">
            @if($popularCourses->count() > 0)
                @foreach($popularCourses as $course)
                    <div class="col-md-6 col-xl-4">
                        <div class="card h-100 course-card shadow-sm border-0">
                            <div class="card-body p-3 p-md-4">
                                <h5 class="card-title fw-bold mb-2">{{ $course->name }}</h5>
                                <p class="card-text text-muted small" style="text-align: justify;">{{ Str::limit($course->description, 80) }}</p>
                                @if($course->start_date)
                                    <p class="card-text mb-2">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar3"></i> 
                                            Начало: {{ $course->start_date->format('d.m.Y') }}
                                        </small>
                                    </p>
                                @endif
                            </div>
                            <div class="card-footer bg-transparent border-0 pb-3 pt-0">
                                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                    <div>
                                        <span class="text-muted small">
                                            <i class="bi bi-clock"></i> {{ $course->duration_hours }} ч.
                                        </span>
                                        @if($course->max_students)
                                            <span class="text-muted small ms-2">
                                                <i class="bi bi-people"></i> {{ $course->available_spots }} мест
                                            </span>
                                        @endif
                                    </div>
                                    <span class="fw-bold text-primary">
                                        {{ number_format($course->price, 0, ',', ' ') }} ₽
                                    </span>
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('course.show', $course) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-info-circle"></i> Подробнее
                                    </a>
                                    <a href="{{ route('applications.create') }}?course_id={{ $course->id }}" 
                                       class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-circle"></i> Подать заявку
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="alert alert-info border-0 shadow-sm">
                        <i class="bi bi-info-circle"></i> На данный момент нет доступных курсов. Следите за обновлениями!
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Правая колонка - информация и заявки -->
    <div class="col-lg-4">
        <!-- Карточка информации -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-person-circle"></i> Информация</h5>
                <button type="button" class="btn btn-sm btn-light" onclick="document.getElementById('editProfileBtn').click()">
                    <i class="bi bi-pencil"></i> Редактировать
                </button>
            </div>
            <div class="card-body p-3 p-md-4">
                <div class="mb-3">
                    <label class="text-muted small text-uppercase fw-semibold">Логин</label>
                    <p class="fw-semibold mb-0">{{ Auth::user()->login }}</p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small text-uppercase fw-semibold">Телефон</label>
                    <p class="fw-semibold mb-0" id="display-phone">{{ Auth::user()->phone }}</p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small text-uppercase fw-semibold">Email</label>
                    <p class="fw-semibold mb-0" id="display-email">{{ Auth::user()->email }}</p>
                </div>
                <hr>
                <p class="small text-muted mb-0">
                    <i class="bi bi-lightbulb"></i> Все заявки рассматриваются администратором в течение 24 часов.
                </p>
            </div>
        </div>

        <!-- Последние заявки -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-list-check"></i> Последние заявки</h5>
                @if($userApplications->count() > 3)
                    <a href="{{ route('applications.index') }}" class="btn btn-sm btn-light">
                        Все <i class="bi bi-arrow-right"></i>
                    </a>
                @endif
            </div>
            <div class="card-body p-3 p-md-4">
                @if($userApplications->count() > 0)
                    @foreach($userApplications->take(3) as $application)
                        <div class="list-group-item bg-transparent border-0 px-0 pt-0 pb-3 mb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start mb-2 flex-wrap gap-2">
                                <h6 class="mb-0 fw-bold">{{ $application->course->name }}</h6>
                                <span class="badge @if($application->status == 'new') bg-primary 
                                               @elseif($application->status == 'in_progress') bg-warning 
                                               @else bg-success @endif">
                                    @if($application->status == 'new')
                                        <i class="bi bi-clock"></i> Новая
                                    @elseif($application->status == 'in_progress')
                                        <i class="bi bi-play-circle"></i> Идет обучение
                                    @else
                                        <i class="bi bi-check-circle"></i> Завершено
                                    @endif
                                </span>
                            </div>
                            <p class="small text-muted mb-1">
                                <i class="bi bi-calendar"></i> Дата начала: {{ $application->desired_start_date->format('d.m.Y') }}
                            </p>
                            <p class="small text-muted mb-0">
                                <i class="bi bi-wallet2"></i> Способ оплаты: {{ $application->payment_method == 'cash' ? 'Наличные' : 'Безналичный перевод' }}
                            </p>
                        </div>
                    @endforeach
                    
                    @if($userApplications->count() > 3)
                        <div class="text-center mt-2">
                            <a href="{{ route('applications.index') }}" class="btn btn-outline-primary btn-sm w-100">
                                Все заявки ({{ $userApplications->count() }})
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox display-6 text-muted"></i>
                        <p class="text-muted mb-2 mt-2">У вас пока нет заявок</p>
                        <a href="{{ route('applications.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle"></i> Создать первую заявку
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .course-card {
        transition: all 0.2s ease;
        border-radius: 16px;
    }
    
    .course-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
    }
    
    .list-group-item:last-child {
        border-bottom: none !important;
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }
    
    .bg-gradient {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    }
    
    @media (max-width: 768px) {
        .display-5 {
            font-size: 1.75rem;
        }
        
        .lead {
            font-size: 0.95rem;
        }
        
        .btn-lg {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
        
        .card-body {
            padding: 1rem !important;
        }
        
        .modal-body {
            padding: 1rem !important;
        }
    }
    
    @media (max-width: 576px) {
        .display-5 {
            font-size: 1.5rem;
        }
        
        h3 {
            font-size: 1.25rem;
        }
        
        h5 {
            font-size: 1rem;
        }
        
        .card-title {
            font-size: 0.95rem;
        }
        
        .btn-sm {
            font-size: 0.75rem;
        }
    }
    
    .btn:active {
        transform: scale(0.98);
    }
    
    .modal-dialog-centered {
        display: flex;
        align-items: center;
        min-height: calc(100% - 1rem);
    }
    
    p {
        text-align: justify;
    }
</style>
@endpush

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone');
    
    if (phoneInput) {
        // Функция форматирования номера
        function formatPhoneNumber(value) {
            // Удаляем все нецифровые символы
            let numbers = value.replace(/\D/g, '');
            
            // Если номер начинается с 8, меняем на 7
            if (numbers.startsWith('8')) {
                numbers = '7' + numbers.substring(1);
            }
            
            // Если номер не начинается с 7, добавляем 7
            if (numbers.length > 0 && !numbers.startsWith('7')) {
                numbers = '7' + numbers;
            }
            
            // Ограничиваем 11 цифрами (7 + 10 цифр)
            numbers = numbers.substring(0, 11);
            
            // Если нет цифр, возвращаем пустую строку
            if (numbers.length === 0) return '';
            
            // Форматируем: +7 (XXX) XXX-XX-XX
            let formatted = '+7';
            
            if (numbers.length > 1) {
                formatted += ' (' + numbers.substring(1, Math.min(4, numbers.length));
            }
            
            if (numbers.length >= 4) {
                formatted += ') ' + numbers.substring(4, Math.min(7, numbers.length));
            } else if (numbers.length > 1) {
                formatted += ')';
            }
            
            if (numbers.length >= 7) {
                formatted += '-' + numbers.substring(7, Math.min(9, numbers.length));
            }
            
            if (numbers.length >= 9) {
                formatted += '-' + numbers.substring(9, 11);
            }
            
            return formatted;
        }
        
        // Форматируем начальное значение
        let currentValue = phoneInput.value;
        if (currentValue) {
            phoneInput.value = formatPhoneNumber(currentValue);
        }
        
        // Обработчик ввода - всегда ставим курсор в конец
        phoneInput.addEventListener('input', function(e) {
            let numbers = this.value.replace(/\D/g, '');
            let formatted = formatPhoneNumber(numbers);
            this.value = formatted;
            
            // Всегда ставим курсор в конец
            setTimeout(() => {
                this.setSelectionRange(formatted.length, formatted.length);
            }, 0);
        });
        
        // При фокусе
        phoneInput.addEventListener('focus', function() {
            if (!this.value) {
                this.value = '+7 (';
                setTimeout(() => {
                    this.setSelectionRange(4, 4);
                }, 10);
            } else {
                // Ставим курсор в конец при фокусе
                setTimeout(() => {
                    this.setSelectionRange(this.value.length, this.value.length);
                }, 10);
            }
        });
        
        // При клике - тоже в конец
        phoneInput.addEventListener('click', function() {
            setTimeout(() => {
                this.setSelectionRange(this.value.length, this.value.length);
            }, 10);
        });
    }
});
</script>
@endsection
@endsection