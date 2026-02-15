<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

// Главная
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

// Аутентификация
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Дашборд пользователя
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Заявки (только для авторизованных)
Route::middleware('auth')->group(function () {
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [ApplicationController::class, 'index'])->name('index');
        Route::get('/create', [ApplicationController::class, 'create'])->name('create');
        Route::post('/', [ApplicationController::class, 'store'])->name('store');
        Route::post('/{application}/review', [ApplicationController::class, 'addReview'])->name('review');
    });
});

// Маршруты для просмотра курсов пользователями
Route::middleware('auth')->group(function () {
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('course.show');
});

// ========== АДМИНКА ==========

// Главная админки
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// Заявки админки
Route::patch('/admin/applications/{application}', [AdminController::class, 'updateStatus'])->name('admin.applications.update');

// Пользователи админки
Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users.index');
Route::get('/admin/users/{user}', [AdminController::class, 'showUser'])->name('admin.users.show');

// КУРСЫ АДМИНКИ - ЯВНО И ПОЛНОСТЬЮ
Route::get('/admin/courses', [AdminController::class, 'courses'])->name('admin.courses');
Route::get('/admin/courses/create', [AdminController::class, 'createCourse'])->name('admin.courses.create');
Route::post('/admin/courses', [AdminController::class, 'storeCourse'])->name('admin.courses.store');
Route::get('/admin/courses/{course}', [AdminController::class, 'showCourse'])->name('admin.courses.show');
Route::get('/admin/courses/{course}/edit', [AdminController::class, 'editCourse'])->name('admin.courses.edit');
Route::put('/admin/courses/{course}', [AdminController::class, 'updateCourse'])->name('admin.courses.update');
Route::delete('/admin/courses/{course}', [AdminController::class, 'destroyCourse'])->name('admin.courses.destroy');

// Тестовый маршрут
Route::get('/test-routes', function() {
    echo "<h3>Проверка маршрутов:</h3>";
    
    $routesToCheck = [
        'admin.courses',
        'admin.courses.create', 
        'admin.courses.store',
        'admin.courses.show',
        'admin.courses.edit',
        'admin.courses.update',
        'admin.courses.destroy',
        'courses.index',
        'course.show'
    ];
    
    foreach ($routesToCheck as $routeName) {
        if (Route::has($routeName)) {
            echo "<p style='color: green;'>✅ Маршрут '$routeName' существует</p>";
        } else {
            echo "<p style='color: red;'>❌ Маршрут '$routeName' НЕ существует</p>";
        }
    }
});