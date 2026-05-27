<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// ГЛАВНАЯ
Route::get('/', [HomeController::class, 'index'])->name('home');

// АУТЕНТИФИКАЦИЯ
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ДАШБОРД ПОЛЬЗОВАТЕЛЯ
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

//  КУРСЫ (ПУБЛИЧНЫЙ ДОСТУП) 
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('course.show');

// ЗАЯВКИ (ТОЛЬКО ДЛЯ АВТОРИЗОВАННЫХ)
Route::middleware('auth')->group(function () {
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [ApplicationController::class, 'index'])->name('index');
        Route::get('/create', [ApplicationController::class, 'create'])->name('create');
        Route::post('/', [ApplicationController::class, 'store'])->name('store');
        Route::post('/{application}/review', [ApplicationController::class, 'addReview'])->name('review');
    });
});

// API МАРШРУТЫ 
Route::get('/api/courses/search', [CourseController::class, 'search'])->name('api.courses.search');

//  АДМИН-ПАНЕЛЬ 
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Главная АДМИН-ПАНЕЛЬ 
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Заявки АДМИН-ПАНЕЛЬ 
    Route::get('/applications', [AdminController::class, 'applications'])->name('applications.index');
    Route::patch('/applications/{application}', [AdminController::class, 'updateStatus'])->name('applications.update');
    
    // Пользователи АДМИН-ПАНЕЛЬ 
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    
    // КУРСЫ В АДМИНКЕ
    Route::get('/courses', [CourseController::class, 'adminIndex'])->name('courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}', [CourseController::class, 'adminShow'])->name('courses.show');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
    
    // Дополнительные маршруты для курсов в админке
    Route::patch('/courses/{course}/toggle-status', [CourseController::class, 'toggleStatus'])->name('courses.toggle-status');
    Route::get('/courses-statistics', [CourseController::class, 'statistics'])->name('courses.statistics');
});

// ПРОФИЛЬ ПОЛЬЗОВАТЕЛЯ 
Route::middleware('auth')->group(function () {
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

// ТЕСТОВЫЙ МАРШРУТ
Route::get('/test-routes', function() {
    
})->name('test.routes');