@extends('layouts.app')

@section('title', 'Вход')

@section('content')
<div class="row justify-content-center login-container">
    <div class="col-11 col-sm-10 col-md-6 col-lg-5 col-xl-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center text-md-start">
                <h4 class="mb-0"><i class="bi bi-box-arrow-in-right"></i> Вход в систему</h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="login" class="form-label">Логин</label>
                        <input type="text" class="form-control @error('login') is-invalid @enderror" 
                               id="login" name="login" value="{{ old('login') }}" required>
                        @error('login')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Запомнить меня</label>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-box-arrow-in-right"></i> Войти
                        </button>
                        <a href="{{ route('register') }}" class="btn btn-link">
                            <i class="bi bi-person-plus"></i> Еще не зарегистрированы? Регистрация
                        </a>
                    </div>
                    
                    <div class="text-center mt-4 pt-2 border-top">
                        <p class="text-muted small mb-2">Тестовый доступ для проверки:</p>
                        <div class="small">
                            <div class="row">
                                <div class="col-12 col-sm-6 mb-2 mb-sm-0">
                                    <span class="fw-bold">👨‍💼 Администратор:</span><br>
                                    <span class="text-muted">Admin / KorokNET</span>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <span class="fw-bold">👤 Пользователь:</span><br>
                                    <span class="text-muted">testuser / password123</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .login-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
    }
    
    .card {
        border-radius: 12px;
        overflow: hidden;
        border: none;
    }
    
    .card-header {
        border-bottom: none;
        padding: 1rem 1.25rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .btn-primary {
        padding: 0.5rem 1rem;
        font-weight: 500;
    }
    
    .btn-link {
        text-decoration: none;
    }
    
    .btn-link:hover {
        text-decoration: underline;
    }
    
    .border-top {
        border-color: #dee2e6 !important;
    }
    
    /* Адаптация для планшетов */
    @media (max-width: 768px) {
        .login-container {
            min-height: calc(100vh - 180px);
            align-items: flex-start;
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
        
        .card-body {
            padding: 1.25rem;
        }
        
        .card-header {
            text-align: center !important;
        }
        
        .card-header h4 {
            font-size: 1.3rem;
        }
    }
    
    /* Адаптация для мобильных телефонов */
    @media (max-width: 576px) {
        .login-container {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .card-header {
            padding: 0.75rem 1rem;
        }
        
        .card-header h4 {
            font-size: 1.1rem;
        }
        
        .form-label {
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }
        
        .form-control {
            font-size: 0.85rem;
            padding: 0.4rem 0.75rem;
        }
        
        .btn {
            font-size: 0.85rem;
            padding: 0.4rem 0.75rem;
        }
        
        .btn-link {
            font-size: 0.85rem;
        }
        
        .small {
            font-size: 0.7rem;
        }
        
        .fw-bold {
            font-size: 0.8rem;
        }
        
        .border-top {
            margin-top: 1rem !important;
            padding-top: 1rem !important;
        }
        
        .test-credentials .row {
            font-size: 0.7rem;
        }
    }
    
    /* Адаптация для очень маленьких экранов */
    @media (max-width: 400px) {
        .test-credentials .row > div {
            margin-bottom: 0.5rem;
        }
        
        .test-credentials .row > div:last-child {
            margin-bottom: 0;
        }
    }
    
    /* Улучшение читаемости */
    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        border-color: #86b7fe;
    }
    
    /* Анимация для карточки */
    .card {
        animation: fadeInUp 0.4s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush