<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        // Проверка авторизации
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Пожалуйста, войдите в систему');
        }
        
        // Проверка прав администратора
        if (!Auth::user()->is_admin) {
            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }
        
        $query = Application::with(['user', 'course', 'review']);
        
        // Поиск по тексту
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('full_name', 'like', "%{$search}%")
                                ->orWhere('login', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('course', function($courseQuery) use ($search) {
                      $courseQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Фильтр по статусу
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Фиоьтр по способу оплаты
        if ($request->filled('payment')) {
            $query->where('payment_method', $request->payment);
        }
        
        // Фильтр по курсу
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }
        
        // Фильтр по дате создания
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Фильтр по жедаемой дате начала
        if ($request->filled('start_date_from')) {
            $query->whereDate('desired_start_date', '>=', $request->start_date_from);
        }
        if ($request->filled('start_date_to')) {
            $query->whereDate('desired_start_date', '<=', $request->start_date_to);
        }
        
        // Фильтр по наличию отзыва
        if ($request->filled('has_review')) {
            if ($request->has_review == 'yes') {
                $query->has('review');
            } elseif ($request->has_review == 'no') {
                $query->doesntHave('review');
            }
        }
        
        // СОРТИРОВКА
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        switch ($sortField) {
            case 'user_name':
                // Сортировка по имени пользователя
                $query->leftJoin('users', 'applications.user_id', '=', 'users.id')
                      ->select('applications.*')
                      ->orderBy('users.full_name', $sortDirection);
                break;
            case 'course_name':
                // Сортировка по названию курса
                $query->leftJoin('courses', 'applications.course_id', '=', 'courses.id')
                      ->select('applications.*')
                      ->orderBy('courses.name', $sortDirection);
                break;
            default:
                // Сортировка по обычным полям
                if (in_array($sortField, ['created_at', 'desired_start_date', 'status', 'payment_method'])) {
                    $query->orderBy($sortField, $sortDirection);
                } else {
                    $query->orderBy('created_at', 'desc');
                }
        }
        
        // ПАГИНАЦИЯ
        $applications = $query->paginate(15)->withQueryString();
        
        // СТАТИСТИКА
        $stats = [
            'total' => Application::count(),
            'new' => Application::where('status', 'new')->count(),
            'in_progress' => Application::where('status', 'in_progress')->count(),
            'completed' => Application::where('status', 'completed')->count(),
        ];
        
        // Получаем список всех курсов для выпадающего списка
        $courses = \App\Models\Course::all();
        
        return view('admin.dashboard', compact('applications', 'stats', 'courses'));
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

    public function users(Request $request)
    {
        $query = User::query()->withCount('applications');
        
        // Поиск по тексту
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('login', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Фильтр по роли
        if ($request->filled('role')) {
            if ($request->role == 'admin') {
                $query->where('is_admin', true);
            } elseif ($request->role == 'user') {
                $query->where('is_admin', false);
            }
        }
        
        // Сортировка
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        if (in_array($sortField, ['created_at', 'login', 'full_name', 'applications_count'])) {
            if ($sortField == 'applications_count') {
                $query->orderBy('applications_count', $sortDirection);
            } else {
                $query->orderBy($sortField, $sortDirection);
            }
        }
        
        // ВАЖНО: используем paginate()
        $users = $query->paginate(15)->withQueryString();
        
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

    // МЕТОДЫ ДЛЯ УПРАВЛЕНИЯ КУРСАМИ

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