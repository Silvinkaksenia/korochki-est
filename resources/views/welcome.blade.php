@extends('layouts.app')

@section('title', 'Главная')
<!-- @section('slider', true) -->

@section('content')
<!-- Hero секция с градиентом и анимацией -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px;">
            <div class="card-body p-4 p-md-5">
                <div class="row align-items-center">
                    <div class="col-md-7 text-white">
                        <h1 class="display-3 fw-bold mb-4 animate__animated animate__fadeInDown text-center text-md-start" style="word-break: keep-all; white-space: normal;">
                            Добро пожаловать в <br><span style="white-space: nowrap; display: inline-block;">«Корочки.есть»!</span>
                        </h1>
                        <p class="lead mb-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s; text-align: justify;">
                            <i class="bi bi-mortarboard-fill me-2"></i>
                            Повышайте квалификацию с нашими курсами дополнительного профессионального образования
                        </p>
                        <div class="mt-4 animate__animated animate__fadeInUp d-flex flex-wrap gap-2 justify-content-center justify-content-md-start" style="animation-delay: 0.6s;">
                            <a href="{{ route('courses.index') }}" class="btn btn-light btn-lg shadow-sm flex-fill flex-md-grow-0">
                                <i class="bi bi-book"></i> Смотреть все курсы
                            </a>
                            
                            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg shadow-sm flex-fill flex-md-grow-0">
                                <i class="bi bi-person-plus"></i> Зарегистрироваться
                            </a>
                        </div>
                    </div>
                    <div class="col-md-5 text-center d-none d-md-block">
                        <div class="position-relative">
                            <i class="bi bi-mortarboard-fill display-1 text-white opacity-75" style="font-size: 12rem;"></i>
                            <div class="position-absolute top-0 start-0 w-100 h-100">
                                <div class="spinner-grow text-light position-absolute top-25 start-25" style="width: 3rem; height: 3rem;" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Статистика в цифрах -->
<div class="row mb-5 g-3">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-3 p-md-4">
                <div class="display-4 text-primary mb-2">
                    <i class="bi bi-mortarboard"></i>
                </div>
                <h3 class="fw-bold text-primary fs-2 fs-md-1">50+</h3>
                <p class="text-muted mb-0 small">Образовательных программ</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-3 p-md-4">
                <div class="display-4 text-success mb-2">
                    <i class="bi bi-people"></i>
                </div>
                <h3 class="fw-bold text-success fs-2 fs-md-1">1000+</h3>
                <p class="text-muted mb-0 small">Выпускников</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-3 p-md-4">
                <div class="display-4 text-info mb-2">
                    <i class="bi bi-star"></i>
                </div>
                <h3 class="fw-bold text-info fs-2 fs-md-1">4.9</h3>
                <p class="text-muted mb-0 small">Средняя оценка</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-3 p-md-4">
                <div class="display-4 text-warning mb-2">
                    <i class="bi bi-clock-history"></i>
                </div>
                <h3 class="fw-bold text-warning fs-2 fs-md-1">24/7</h3>
                <p class="text-muted mb-0 small">Доступ к материалам</p>
            </div>
        </div>
    </div>
</div>

<!-- Популярные курсы -->
<div class="row mb-5">
    <div class="col-12">
        <div class="d-flex flex-column flex-md-row align-items-center mb-4">
            <div class="flex-shrink-0 mb-3 mb-md-0">
                <span class="badge bg-warning p-3 rounded-circle me-md-3">
                    <i class="bi bi-star-fill text-white fs-4"></i>
                </span>
            </div>
            <div class="flex-grow-1 text-center text-md-start">
                <h2 class="fw-bold mb-0">Популярные курсы</h2>
                <p class="text-muted">Самые востребованные программы этого сезона</p>
            </div>
            <div class="d-none d-md-block">
                <a href="{{ route('courses.index') }}" class="btn btn-outline-primary">
                    Все курсы <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
    
    @forelse($popularCourses as $course)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm course-card">
                <div class="position-relative">
                    <img src="{{ asset('img/1.svg') }}" class="card-img-top" alt="{{ $course->name }}" style="height: 200px; object-fit: cover; width: 100%;">
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-warning text-dark p-2">
                            <i class="bi bi-star-fill me-1"></i> Популярный
                        </span>
                    </div>
                    @if($course->max_students && $course->available_spots <= 3)
                        <div class="position-absolute bottom-0 start-0 m-3">
                            <span class="badge bg-danger p-2">
                                <i class="bi bi-exclamation-triangle me-1"></i> Осталось {{ $course->available_spots }} мест
                            </span>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <h5 class="card-title fw-bold" style="word-break: keep-all;">{{ $course->name }}</h5>
                    <p class="card-text text-muted small text-justify" style="text-align: justify;">{{ Str::limit($course->description, 100) }}</p>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-clock text-primary me-1"></i>
                            <span class="text-muted small">{{ $course->duration_hours }} ч.</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar text-success me-1"></i>
                            <span class="text-muted small">{{ $course->start_date->format('d.m.Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <span class="h5 fw-bold text-primary">{{ number_format($course->price, 0, ',', ' ') }} ₽</span>
                        </div>
                        <div>
                            @if($course->applications_count > 0)
                                <span class="badge bg-light text-dark">
                                    <i class="bi bi-people text-primary me-1"></i>
                                    {{ $course->applications_count }} {{ $course->applications_count == 1 ? 'заявка' : ($course->applications_count < 5 ? 'заявки' : 'заявок') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-3 pt-0">
                    <a href="{{ route('course.show', $course) }}" class="btn btn-primary w-100 rounded-pill">
                        <i class="bi bi-info-circle me-2"></i> Подробнее о курсе
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info border-0 shadow-sm">
                <i class="bi bi-info-circle me-2"></i> Пока нет доступных курсов. Следите за обновлениями!
            </div>
        </div>
    @endforelse
    
    <div class="col-12 text-center d-md-none mt-3">
        <a href="{{ route('courses.index') }}" class="btn btn-primary">
            Все курсы <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
</div>

<!-- Ближайшие курсы с таймером -->
<div class="row mb-5">
    <div class="col-12">
        <div class="d-flex flex-column flex-md-row align-items-center mb-4">
            <div class="flex-shrink-0 mb-3 mb-md-0">
                <span class="badge bg-info p-3 rounded-circle me-md-3">
                    <i class="bi bi-calendar-event text-white fs-4"></i>
                </span>
            </div>
            <div class="flex-grow-1 text-center text-md-start">
                <h2 class="fw-bold mb-0">Скоро начинаем</h2>
                <p class="text-muted">Запишитесь на ближайшие курсы</p>
            </div>
        </div>
    </div>
    
    @forelse($upcomingCourses as $course)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm course-card border-start border-4 border-info">
                <div class="card-body">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start mb-3 gap-2">
                        <h5 class="card-title fw-bold mb-0" style="word-break: keep-all;">{{ $course->name }}</h5>
                        <span class="badge bg-info text-white flex-shrink-0">
                            <i class="bi bi-calendar-check me-1"></i>
                            {{ $course->start_date->diffForHumans() }}
                        </span>
                    </div>
                    
                    <p class="card-text text-muted small text-justify" style="text-align: justify;">{{ Str::limit($course->description, 80) }}</p>
                    
                    <div class="bg-light p-3 rounded-3 mb-3">
                        <div class="row text-center g-2">
                            <div class="col-4">
                                <div class="small text-muted">Длит.</div>
                                <div class="fw-bold">{{ $course->duration_hours }} ч.</div>
                            </div>
                            <div class="col-4">
                                <div class="small text-muted">Цена</div>
                                <div class="fw-bold text-primary">{{ number_format($course->price, 0, ',', ' ') }} ₽</div>
                            </div>
                            <div class="col-4">
                                <div class="small text-muted">Мест</div>
                                <div class="fw-bold">{{ $course->max_students ? $course->available_spots : '∞' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <a href="{{ route('course.show', $course) }}" class="btn btn-outline-info rounded-pill">
                            <i class="bi bi-calendar-plus me-2"></i> Выбрать дату
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info border-0 shadow-sm">
                <i class="bi bi-info-circle me-2"></i> Пока нет ближайших курсов
            </div>
        </div>
    @endforelse
</div>

<!-- Преимущества с иконками -->
<div class="row mt-5 mb-5">
    <div class="col-12">
        <div class="card border-0 bg-light" style="border-radius: 20px;">
            <div class="card-body p-4 p-md-5">
                <h2 class="text-center fw-bold mb-5">Почему выбирают <span class="text-primary">«Корочки.есть»</span>?</h2>
                
                <div class="row g-4">
                    <div class="col-sm-6 col-md-4">
                        <div class="text-center">
                            <div class="bg-white shadow-sm rounded-circle d-inline-flex p-4 mb-3">
                                <i class="bi bi-mortarboard fs-1 text-primary"></i>
                            </div>
                            <h5 class="fw-bold">Квалифицированные преподаватели</h5>
                            <p class="text-muted" style="text-align: center;">Опытные специалисты с большим стажем работы</p>
                        </div>
                    </div>
                    
                    <div class="col-sm-6 col-md-4">
                        <div class="text-center">
                            <div class="bg-white shadow-sm rounded-circle d-inline-flex p-4 mb-3">
                                <i class="bi bi-clock-history fs-1 text-success"></i>
                            </div>
                            <h5 class="fw-bold">Гибкий график обучения</h5>
                            <p class="text-muted" style="text-align: center;">Выбирайте удобное время и формат занятий</p>
                        </div>
                    </div>
                    
                    <div class="col-sm-6 col-md-4">
                        <div class="text-center">
                            <div class="bg-white shadow-sm rounded-circle d-inline-flex p-4 mb-3">
                                <i class="bi bi-award fs-1 text-warning"></i>
                            </div>
                            <h5 class="fw-bold">Официальные сертификаты</h5>
                            <p class="text-muted" style="text-align: center;">Документы установленного государственного образца</p>
                        </div>
                    </div>
                    
                    <div class="col-sm-6 col-md-4">
                        <div class="text-center">
                            <div class="bg-white shadow-sm rounded-circle d-inline-flex p-4 mb-3">
                                <i class="bi bi-laptop fs-1 text-info"></i>
                            </div>
                            <h5 class="fw-bold">Современные технологии</h5>
                            <p class="text-muted" style="text-align: center;">Доступ к материалам 24/7 с любого устройства</p>
                        </div>
                    </div>
                    
                    <div class="col-sm-6 col-md-4">
                        <div class="text-center">
                            <div class="bg-white shadow-sm rounded-circle d-inline-flex p-4 mb-3">
                                <i class="bi bi-people fs-1 text-danger"></i>
                            </div>
                            <h5 class="fw-bold">Небольшие группы</h5>
                            <p class="text-muted" style="text-align: center;">Индивидуальный подход к каждому студенту</p>
                        </div>
                    </div>
                    
                    <div class="col-sm-6 col-md-4">
                        <div class="text-center">
                            <div class="bg-white shadow-sm rounded-circle d-inline-flex p-4 mb-3">
                                <i class="bi bi-piggy-bank fs-1 text-success"></i>
                            </div>
                            <h5 class="fw-bold">Доступные цены</h5>
                            <p class="text-muted" style="text-align: center;">Гибкая система скидок и рассрочка</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Отзывы выпускников -->
<div class="row mb-5">
    <div class="col-12">
        <h2 class="text-center fw-bold mb-5">Что говорят наши <span class="text-primary">выпускники</span></h2>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-placeholder rounded-circle me-3" style="min-width: 50px; width: 50px; height: 50px; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                        АП
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">Анна Петрова</h6>
                        <small class="text-muted">Выпуск 2025</small>
                    </div>
                </div>
                <div class="text-warning mb-2">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                </div>
                <p class="card-text text-muted fst-italic" style="text-align: justify;">
                    "Прошла курс по веб-разработке. Отличные преподаватели, много практики. Сразу после обучения нашла работу!"
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-placeholder rounded-circle me-3" style="min-width: 50px; width: 50px; height: 50px; background: linear-gradient(135deg, #764ba2, #667eea); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                        ИС
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">Иван Сидоров</h6>
                        <small class="text-muted">Выпуск 2025</small>
                    </div>
                </div>
                <div class="text-warning mb-2">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                </div>
                <p class="card-text text-muted fst-italic" style="text-align: justify;">
                    "Курс по маркетингу превзошел ожидания! Много полезной информации, кейсов. Спасибо команде!"
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-placeholder rounded-circle me-3" style="min-width: 50px; width: 50px; height: 50px; background: linear-gradient(135deg, #28a745, #20c997); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                        ЕС
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">Елена Смирнова</h6>
                        <small class="text-muted">Выпуск 2025</small>
                    </div>
                </div>
                <div class="text-warning mb-2">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-half"></i>
                </div>
                <p class="card-text text-muted fst-italic" style="text-align: justify;">
                    "Удобная платформа, понятные материалы, отзывчивая поддержка. Обязательно приду еще!"
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Призыв к действию -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card border-0 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px;">
            <div class="card-body p-4 p-md-5 text-center">
                <h2 class="fw-bold mb-3">Начните обучение сегодня!</h2>
                <p class="lead mb-4">Присоединяйтесь к тысячам наших выпускников</p>
                <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4 px-md-5 py-3 rounded-pill shadow d-inline-block">
                    <i class="bi bi-person-plus me-2"></i> Зарегистрироваться
                </a>
                <p class="mt-4 small opacity-75">
                    <i class="bi bi-shield-check me-1"></i> 
                    Регистрация бесплатна и занимает 1 минуту
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Контакты и карта -->
<div class="row g-4">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-4"><i class="bi bi-telephone text-primary me-2"></i> Контактная информация</h4>
                
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <span class="badge bg-primary p-2 rounded-circle me-3">
                            <i class="bi bi-telephone-fill text-white"></i>
                        </span>
                    </div>
                    <div>
                        <p class="text-muted mb-1">Телефон</p>
                        <h5 class="fw-bold mb-0">8 (800) 123-45-67</h5>
                        <small class="text-muted">Звонок бесплатный</small>
                    </div>
                </div>
                
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <span class="badge bg-success p-2 rounded-circle me-3">
                            <i class="bi bi-envelope-fill text-white"></i>
                        </span>
                    </div>
                    <div>
                        <p class="text-muted mb-1">Email</p>
                        <h5 class="fw-bold mb-0">info@korochki-est.ru</h5>
                        <small class="text-muted">Ответим в течение 2 часов</small>
                    </div>
                </div>
                
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <span class="badge bg-warning p-2 rounded-circle me-3">
                            <i class="bi bi-geo-alt-fill text-white"></i>
                        </span>
                    </div>
                    <div>
                        <p class="text-muted mb-1">Адрес</p>
                        <h5 class="fw-bold mb-0">г. Москва, ул. Образования, д. 1</h5>
                        <small class="text-muted">БЦ "Образовательный", офис 101</small>
                    </div>
                </div>
                
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <span class="badge bg-info p-2 rounded-circle me-3">
                            <i class="bi bi-clock-fill text-white"></i>
                        </span>
                    </div>
                    <div>
                        <p class="text-muted mb-1">Режим работы</p>
                        <h5 class="fw-bold mb-0">Пн-Пт: 9:00 - 18:00</h5>
                        <small class="text-muted">Сб-Вс: выходной</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-0">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2245.823413247596!2d37.6173!3d55.7558!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNTXCsDQ1JzIwLjgiTiAzN8KwMzcnMDIuMyJF!5e0!3m2!1sru!2sru!4v1620000000000!5m2!1sru!2sru" 
                    width="100%" 
                    height="350" 
                    style="border:0; border-radius: 0 0 10px 10px;" 
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
    /* Основные стили */
    .course-card {
        transition: all 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
    }
    
    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    
    .badge.rounded-circle {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .bg-white.rounded-circle {
        transition: all 0.3s ease;
    }
    
    .bg-white.rounded-circle:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    /* Запрет переноса слов */
    h1, h2, h3, h4, h5, h6, .card-title, .fw-bold, .badge, .btn {
        word-break: keep-all;
        white-space: normal;
    }
    
    /* Для длинных слов на мобильных - перенос только если совсем не влезает */
    @media (max-width: 576px) {
        h1, h2, h3, h4, h5, h6 {
            word-break: break-word;
            white-space: normal;
        }
        
        .card-title {
            word-break: break-word;
        }
    }
    
    /* Адаптивные стили для мобильных устройств */
    @media (max-width: 768px) {
        .display-3 {
            font-size: 2rem !important;
        }
        
        .display-4 {
            font-size: 2rem !important;
        }
        
        .text-justify {
            text-align: justify !important;
        }
        
        .card-body {
            padding: 1rem !important;
        }
        
        .badge.rounded-circle {
            width: 40px;
            height: 40px;
        }
        
        .btn-lg {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
        
        h1.display-3 {
            font-size: 1.75rem !important;
        }
        
        .lead {
            font-size: 0.95rem;
        }
        
        h2 {
            font-size: 1.5rem;
        }
        
        h3 {
            font-size: 1.25rem;
        }
        
        h4 {
            font-size: 1.1rem;
        }
        
        .fs-2 {
            font-size: 1.5rem !important;
        }
    }
    
    @media (max-width: 576px) {
        .container {
            padding-left: 12px;
            padding-right: 12px;
        }
        
        .gap-2 {
            gap: 0.5rem !important;
        }
        
        .btn {
            white-space: normal;
            word-break: break-word;
        }
        
        .card-text {
            font-size: 0.85rem;
        }
        
        .small {
            font-size: 0.75rem;
        }
    }
    
    /* Улучшение читаемости и предотвращение съезжания */
    body {
        word-break: break-word;
        overflow-x: hidden;
    }
    
    .card {
        word-break: break-word;
    }
    
    img, svg {
        max-width: 100%;
        height: auto;
    }
    
    /* Адаптация iframe карты */
    iframe {
        height: auto;
        min-height: 300px;
    }
    
    @media (max-width: 768px) {
        iframe {
            min-height: 250px;
        }
    }
    
    /* Улучшение отступов на мобильных */
    .row > [class*="col-"] {
        margin-bottom: 1rem;
    }
    
    .row > [class*="col-"]:last-child {
        margin-bottom: 0;
    }
    
    @media (min-width: 768px) {
        .row > [class*="col-"] {
            margin-bottom: 0;
        }
    }
    
    /* Стили для аватаров */
    .avatar-placeholder {
        transition: transform 0.3s ease;
    }
    
    .avatar-placeholder:hover {
        transform: scale(1.05);
    }
    
    /* Стили для текста в разделе преимуществ - центрирование */
    .text-center p,
    .text-center .text-muted {
        text-align: center !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
<script>
    new WOW().init();
</script>
@endpush