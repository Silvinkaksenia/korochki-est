<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function show(Course $course)
    {
        // Проверка авторизации
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Пожалуйста, войдите в систему');
        }
        
        // Проверка доступности курса
        if (!$course->is_active || !$course->is_available) {
            return back()->with('error', 'Этот курс временно недоступен для записи.');
        }
        
        // Загружаем связанные данные
        $course->loadCount('applications');
        
        // Получаем похожие курсы
        $similarCourses = Course::where('is_active', true)
            ->where('id', '!=', $course->id)
            ->where('start_date', '>', now())
            ->withCount('applications')
            ->orderBy('applications_count', 'desc')
            ->take(3)
            ->get();
        
        return view('course.show', compact('course', 'similarCourses'));
    }
    
    public function index()
    {
        // Проверка авторизации
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Пожалуйста, войдите в систему');
        }
        
        // Если пользователь админ - отправляем в админку
        if (Auth::user()->is_admin) {
            return redirect()->route('admin.courses.index');
        }
        
        // Получаем все активные курсы
        $courses = Course::where('is_active', true)
            ->where('start_date', '>', now())
            ->withCount('applications')
            ->orderBy('start_date', 'asc')
            ->paginate(9);
        
        return view('course.index', compact('courses'));
    }
}