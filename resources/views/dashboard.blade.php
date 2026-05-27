@extends('layouts.app')

@section('title', 'Главная')
@section('slider', false)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body">
                <h1 class="display-5">Добро пожаловать, {{ Auth::user()->full_name }}!</h1>
                <p class="lead">Выберите курс дополнительного профессионального образования и подайте заявку на обучение.</p>
                <a href="{{ route('applications.create') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle"></i> Подать новую заявку
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для редактирования профиля -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
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
                <div class="modal-body">
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
    <label for="phone" class="form-label">
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
        Введите номер телефона
    </div>
    @error('phone')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                    <!-- Поле для email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
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
                        <label for="current_password" class="form-label">
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

<h3 class="mb-4">Популярные курсы</h3>
<div class="row g-4">
    @if($popularCourses->count() > 0)
        @foreach($popularCourses as $course)
            <div class="col-md-3">
                <div class="card h-100 course-card shadow-sm">
                    <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                         class="card-img-top" alt="{{ $course->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $course->name }}</h5>
                        <p class="card-text">{{ Str::limit($course->description, 100) }}</p>
                        @if($course->start_date)
                            <p class="card-text">
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i> 
                                    Начало: {{ $course->start_date->format('d.m.Y') }}
                                </small>
                            </p>
                        @endif
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted">
                                    <i class="bi bi-clock"></i> {{ $course->duration_hours }} ч.
                                </span>
                                @if($course->max_students)
                                    <span class="text-muted ms-3">
                                        <i class="bi bi-people"></i> {{ $course->available_spots }} мест
                                    </span>
                                @endif
                            </div>
                            <span class="fw-bold text-primary">
                                {{ number_format($course->price, 0, ',', ' ') }} ₽
                            </span>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('course.show', $course) }}" class="btn btn-outline-primary btn-sm w-100">
                                <i class="bi bi-info-circle"></i> Подробнее
                            </a>
                            <a href="{{ route('applications.create') }}?course_id={{ $course->id }}" 
                               class="btn btn-primary btn-sm w-100 mt-2">
                                <i class="bi bi-plus-circle"></i> Подать заявку
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> На данный момент нет доступных курсов. Следите за обновлениями!
            </div>
        </div>
    @endif
</div>

<div class="row mt-5">
    <div class="col-md-8">
        <h3>Последние заявки</h3>
        @if($userApplications->count() > 0)
            <div class="list-group">
                @foreach($userApplications->take(3) as $application)
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">{{ $application->course->name }}</h5>
                            <span class="badge @if($application->status == 'new') bg-primary 
                                           @elseif($application->status == 'in_progress') bg-warning 
                                           @else bg-success @endif">
                                {{ $application->status == 'new' ? 'Новая' : 
                                   ($application->status == 'in_progress' ? 'Идет обучение' : 'Завершено') }}
                            </span>
                        </div>
                        <p class="mb-1">Дата начала: {{ $application->desired_start_date->format('d.m.Y') }}</p>
                        <small>Способ оплаты: {{ $application->payment_method == 'cash' ? 'Наличные' : 'Перевод' }}</small>
                    </div>
                @endforeach
            </div>
            @if($userApplications->count() > 3)
                <div class="mt-3">
                    <a href="{{ route('applications.index') }}" class="btn btn-outline-primary btn-sm">
                        Показать все заявки ({{ $userApplications->count() }})
                    </a>
                </div>
            @endif
        @else
            <div class="alert alert-info">
                У вас пока нет заявок. <a href="{{ route('applications.create') }}">Создайте первую заявку!</a>
            </div>
        @endif
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Информация</h5>
                <button type="button" class="btn btn-sm btn-light" onclick="document.getElementById('editProfileBtn').click()">
                    <i class="bi bi-pencil"></i> Редактировать
                </button>
            </div>
            <div class="card-body">
                <p><strong>Ваш логин:</strong> {{ Auth::user()->login }}</p>
                <p>
                    <strong>Телефон:</strong> 
                    <span id="display-phone">{{ Auth::user()->phone }}</span>
                </p>
                <p>
                    <strong>Email:</strong> 
                    <span id="display-email">{{ Auth::user()->email }}</span>
                </p>
                <hr>
                <p class="small text-muted">
                    <i class="bi bi-lightbulb"></i> Все заявки рассматриваются администратором в течение 24 часов.
                </p>
            </div>
        </div>
    </div>
</div>

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