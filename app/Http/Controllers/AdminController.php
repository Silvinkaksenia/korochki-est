<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Проверка авторизации
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Пожалуйста, войдите в систему');
        }
        
        // Проверка прав администратора
        if (!Auth::user()->is_admin) {
            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }
        
        $applications = Application::with(['user', 'course'])->latest()->get();
        
        $stats = [
            'total' => Application::count(),
            'new' => Application::where('status', 'new')->count(),
            'in_progress' => Application::where('status', 'in_progress')->count(),
            'completed' => Application::where('status', 'completed')->count(),
        ];
        
        return view('admin.dashboard', compact('applications', 'stats'));
    }

    public function updateStatus(Request $request, Application $application)
    {
        // Проверка авторизации
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Пожалуйста, войдите в систему');
        }
        
        // Проверка прав администратора
        if (!Auth::user()->is_admin) {
            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }
        
        $request->validate([
            'status' => 'required|in:new,in_progress,completed',
        ]);

        $application->update(['status' => $request->status]);

        return back()->with('success', 'Статус заявки обновлен!');
    }

    public function users()
    {
        // Проверка авторизации
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Пожалуйста, войдите в систему');
        }
        
        // Проверка прав администратора
        if (!Auth::user()->is_admin) {
            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }
        
        $users = User::withCount('applications')->latest()->get();
        
        return view('admin.users', compact('users'));
    }

    public function showUser(User $user)
    {
        // Проверка авторизации
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Пожалуйста, войдите в систему');
        }
        
        // Проверка прав администратора
        if (!Auth::user()->is_admin) {
            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }
        
        $user->load(['applications.course', 'reviews']);
        
        return view('admin.user-show', compact('user'));
    }

    // ========== НОВЫЕ МЕТОДЫ ДЛЯ УПРАВЛЕНИЯ КУРСАМИ ==========

    public function courses()
    {
        // Проверка авторизации
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Пожалуйста, войдите в систему');
        }
        
        // Проверка прав администратора
        if (!Auth::user()->is_admin) {
            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }
        
        $courses = Course::withCount('applications')->latest()->get();
        
        return view('admin.courses', compact('courses'));
    }

    public function createCourse()
    {
        // Проверка авторизации
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Пожалуйста, войдите в систему');
        }
        
        // Проверка прав администратора
        if (!Auth::user()->is_admin) {
            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }
        
        return view('admin.course-create');
    }

    public function storeCourse(Request $request)
    {
        // Проверка авторизации
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Пожалуйста, войдите в систему');
        }
        
        // Проверка прав администратора
        if (!Auth::user()->is_admin) {
            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255|unique:courses',
            'description' => 'required|string',
            'duration_hours' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'start_date' => 'required|date|after:today',
            'max_students' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        Course::create([
            'name' => $request->name,
            'description' => $request->description,
            'duration_hours' => $request->duration_hours,
            'price' => $request->price,
            'start_date' => $request->start_date,
            'max_students' => $request->max_students,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.courses')
            ->with('success', 'Курс успешно создан!');
    }

    public function editCourse(Course $course)
    {
        // Проверка авторизации
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Пожалуйста, войдите в систему');
        }
        
        // Проверка прав администратора
        if (!Auth::user()->is_admin) {
            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }
        
        return view('admin.course-edit', compact('course'));
    }

    public function updateCourse(Request $request, Course $course)
    {
        // Проверка авторизации
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Пожалуйста, войдите в систему');
        }
        
        // Проверка прав администратора
        if (!Auth::user()->is_admin) {
            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255|unique:courses,name,' . $course->id,
            'description' => 'required|string',
            'duration_hours' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'max_students' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $course->update([
            'name' => $request->name,
            'description' => $request->description,
            'duration_hours' => $request->duration_hours,
            'price' => $request->price,
            'start_date' => $request->start_date,
            'max_students' => $request->max_students,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.courses')
            ->with('success', 'Курс успешно обновлен!');
    }

    public function destroyCourse(Course $course)
    {
        // Проверка авторизации
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Пожалуйста, войдите в систему');
        }
        
        // Проверка прав администратора
        if (!Auth::user()->is_admin) {
            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }
        
        // Проверяем, есть ли заявки на этот курс
        if ($course->applications()->count() > 0) {
            return back()->with('error', 'Нельзя удалить курс, на который есть заявки!');
        }
        
        $course->delete();
        
        return redirect()->route('admin.courses')
            ->with('success', 'Курс успешно удален!');
    }

    public function showCourse(Course $course)
    {
        // Проверка авторизации
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Пожалуйста, войдите в систему');
        }
        
        // Проверка прав администратора
        if (!Auth::user()->is_admin) {
            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }
        
        $course->load(['applications.user', 'applications.review']);
        
        return view('admin.course-show', compact('course'));
    }
}