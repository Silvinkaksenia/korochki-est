@extends('layouts.app')

@section('title', 'Вход')

@section('content')
<div class="row justify-content-center login-container">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-box-arrow-in-right"></i> Вход в систему</h4>
            </div>
            <div class="card-body">
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
                    <div class="text-center mt-3">
                                <p class="text-muted small">Тестовый доступ для проверки:</p>
                                <p class="small">
                                    <strong>Администратор:</strong> Admin / KorokNET<br>
                                    <strong>Пользователь:</strong> testuser / password123
                                </p>
                            </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection