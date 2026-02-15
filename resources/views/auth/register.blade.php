@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
<div class="row justify-content-center login-container">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-person-plus"></i> Регистрация</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="login" class="form-label">Логин</label>
                        <input type="text" class="form-control @error('login') is-invalid @enderror" 
                               id="login" name="login" value="{{ old('login') }}" 
                               required pattern="[A-Za-z0-9]{6,50}" 
                               title="Латиница и цифры, не менее 6 символов">
                        @error('login')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Латиница и цифры, не менее 6 символов</div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required minlength="8">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Минимум 8 символов</div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <div class="mb-3">
                        <label for="full_name" class="form-label">ФИО</label>
                        <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                               id="full_name" name="full_name" value="{{ old('full_name') }}" 
                               required pattern="[А-Яа-яЁё\s]+"
                               title="Только кириллица и пробелы">
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Телефон</label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone') }}" 
                               placeholder="+7 (___) ___-__-__"
                               required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Введите номер телефона, скобки и дефисы добавятся автоматически</div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-person-plus"></i> Создать пользователя
                        </button>
                        <a href="{{ route('login') }}" class="btn btn-link">
                            <i class="bi bi-box-arrow-in-right"></i> Уже зарегистрированы? Войти
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone');
    
    // Функция для форматирования телефона в +7 (999) 123-45-67
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
        
        // Ограничиваем 11 цифрами (7 + 10)
        numbers = numbers.substring(0, 11);
        
        // Форматируем в +7 (999) 123-45-67
        let formatted = '';
        if (numbers.length > 0) {
            formatted = '+7';
            if (numbers.length > 1) {
                formatted += ' (' + numbers.substring(1, 4);
            }
            if (numbers.length >= 4) {
                formatted += ') ' + numbers.substring(4, 7);
            }
            if (numbers.length >= 7) {
                formatted += '-' + numbers.substring(7, 9);
            }
            if (numbers.length >= 9) {
                formatted += '-' + numbers.substring(9, 11);
            }
        }
        
        return formatted;
    }
    
    // Обработчик ввода
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value;
        
        // Получаем цифры из введенного значения
        let numbers = value.replace(/\D/g, '');
        
        // Если ввод начинается не с 7 или 8, добавляем 7
        if (numbers.length > 0 && !['7', '8'].includes(numbers[0])) {
            numbers = '7' + numbers;
        }
        
        // Ограничиваем длину
        numbers = numbers.substring(0, 11);
        
        // Форматируем
        let formatted = '+7';
        if (numbers.length > 1) {
            formatted += ' (' + numbers.substring(1, 4);
        }
        if (numbers.length >= 4) {
            formatted += ') ' + numbers.substring(4, 7);
        }
        if (numbers.length >= 7) {
            formatted += '-' + numbers.substring(7, 9);
        }
        if (numbers.length >= 9) {
            formatted += '-' + numbers.substring(9, 11);
        }
        
        // Устанавливаем отформатированное значение
        e.target.value = formatted;
    });
    
    // При фокусе
    phoneInput.addEventListener('focus', function(e) {
        if (e.target.value === '') {
            e.target.value = '+7 (';
        } else {
            e.target.select();
        }
    });
    
    // При потере фокуса проверяем корректность
    phoneInput.addEventListener('blur', function(e) {
        let numbers = e.target.value.replace(/\D/g, '');
        if (numbers.length < 11) {
            e.target.value = '';
        } else {
            // Финальное форматирование
            e.target.value = formatPhoneNumber(e.target.value);
        }
    });
    
    // Инициализация при загрузке
    if (phoneInput.value) {
        phoneInput.value = formatPhoneNumber(phoneInput.value);
    }
    
    // Предотвращаем ввод нецифровых символов
    phoneInput.addEventListener('keydown', function(e) {
        // Разрешаем: Backspace, Delete, Tab, Escape, Enter, управляющие клавиши
        if ([46, 8, 9, 27, 13].includes(e.keyCode) ||
            (e.keyCode === 65 && e.ctrlKey === true) ||
            (e.keyCode === 67 && e.ctrlKey === true) ||
            (e.keyCode === 86 && e.ctrlKey === true) ||
            (e.keyCode === 88 && e.ctrlKey === true) ||
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            return;
        }
        
        // Запрещаем все, кроме цифр
        if ((e.keyCode < 48 || e.keyCode > 57) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
});
</script>
@endsection