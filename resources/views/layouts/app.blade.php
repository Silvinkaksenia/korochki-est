<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Корочки.есть</title>
    <!-- Bootstrap 5.3 -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Inputmask -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.8/jquery.inputmask.min.js"></script>
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .hero-slider {
            height: 400px;
            overflow: hidden;
            position: relative;
        }
        .hero-slider img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .slider-controls {
            position: absolute;
            top: 50%;
            width: 100%;
            transform: translateY(-50%);
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
        }
        .status-badge {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
        .course-card {
            transition: transform 0.3s;
        }
        .course-card:hover {
            transform: translateY(-5px);
        }
        .login-container {
            min-height: calc(100vh - 200px);
        }
    </style>
</head>
<body>
    <!-- Навигация -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-mortarboard-fill"></i> Корочки.есть
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    
                    <!-- КАТАЛОГ КУРСОВ - ВИДЕН ВСЕМ -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}" 
                           href="{{ route('courses.index') }}">
                            <i class="bi bi-book"></i> Каталог курсов
                        </a>
                    </li>
                    
                    @auth
                        @if(Auth::user()->is_admin)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-speedometer2"></i> Админ-панель
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-card-checklist"></i> Заявки
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                        <i class="bi bi-people"></i> Пользователи
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.courses.index') }}">
                                        <i class="bi bi-book"></i> Курсы
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.courses.create') }}">
                                        <i class="bi bi-plus-circle"></i> Добавить курс
                                    </a></li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">
                                    <i class="bi bi-house"></i> Главная
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('applications.create') }}">
                                    <i class="bi bi-plus-circle"></i> Новая заявка
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('applications.index') }}">
                                    <i class="bi bi-list-check"></i> Мои заявки
                                </a>
                            </li>
                        @endif
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->full_name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right"></i> Выход
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Вход
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i> Регистрация
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Слайдер (только на главной странице) 
    @hasSection('slider')
        <div class="hero-slider">
            <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('img/slider/slide1.jpg') }}" alt="Образование 1">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('img/slider/slide2.jpg') }}" alt="Образование 2">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('img/slider/slide3.jpg') }}" alt="Образование 3">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('img/slider/slide4.jpg') }}" alt="Образование 4">
                    </div>
                </div>
                <div class="slider-controls">
                    <button class="btn btn-light btn-sm" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="btn btn-light btn-sm" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif -->

    <!-- Основной контент -->
    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </div>
    </main>

    <!-- Футер -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Корочки.есть</h5>
                    <p>Портал дополнительного профессионального образования</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>© 2026 Все права защищены</p>
                    <p>Телефон: 8(800)123-4567</p>
                    <p>Email: info@korochki-est.ru</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script>
        // Автоматическое переключение слайдера каждые 3 секунды
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.getElementById('carouselExample');
            if (carousel) {
                const carouselInstance = new bootstrap.Carousel(carousel, {
                    interval: 3000,
                    wrap: true
                });
            }
        });
    </script>
    @yield('scripts')
</body>
</html>