<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Если пользователь авторизован, редиректим на дашборд
        if (auth()->check()) {
            if (auth()->user()->is_admin) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dashboard');
        }
        
        // Получаем популярные курсы
        $popularCourses = Course::where('is_active', true)
            ->where('start_date', '>', now())
            ->withCount('applications')
            ->orderBy('applications_count', 'desc')
            ->limit(6)
            ->get();
        
        // Получаем ближайшие курсы
        $upcomingCourses = Course::where('is_active', true)
            ->where('start_date', '>', now())
            ->orderBy('start_date', 'asc')
            ->limit(6)
            ->get();
        
        return view('welcome', compact('popularCourses', 'upcomingCourses'));
    }
}