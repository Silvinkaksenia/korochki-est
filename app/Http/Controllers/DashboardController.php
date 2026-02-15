<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Проверка авторизации
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Пожалуйста, войдите в систему');
        }
        
        // Если пользователь админ - отправляем в админку
        if (Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        
        // Получаем заявки пользователя
        $userApplications = Auth::user()->applications()->with('course')->latest()->get();
        
        // Получаем активные курсы (популярные - те, у которых больше всего заявок)
        $popularCourses = Course::where('is_active', true)
            ->where('start_date', '>', now())
            ->withCount('applications')
            ->orderBy('applications_count', 'desc')
            ->take(4)
            ->get();
            
        // Если популярных курсов мало, добавляем просто активные
        if ($popularCourses->count() < 4) {
            $additionalCourses = Course::where('is_active', true)
                ->where('start_date', '>', now())
                ->whereNotIn('id', $popularCourses->pluck('id'))
                ->take(4 - $popularCourses->count())
                ->get();
                
            $popularCourses = $popularCourses->merge($additionalCourses);
        }
        
        // Передаем в представление
        return view('dashboard', compact('userApplications', 'popularCourses'));
    }
}