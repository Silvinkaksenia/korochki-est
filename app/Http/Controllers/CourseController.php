<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
// Отображает список курсов для пользователей (с фильтрами).
    public function index(Request $request)
    {
        // Собираем фильтры из запроса
        $filters = [
            'search' => $request->search,
            'price_min' => $request->price_min,
            'price_max' => $request->price_max,
            'duration_min' => $request->duration_min,
            'duration_max' => $request->duration_max,
        ];

        // Для пользователей показываем только доступные курсы
        $filters['only_available'] = true;

        // Параметры сортировки
        $sortField = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');

        // Получаем курсы с применением фильтров, сортировки и подсчетом заявок
        $courses = Course::query()
            ->filter($filters)
            ->sort($sortField, $sortDirection)
            ->withCount('applications')
            ->paginate(9)
            ->withQueryString();

        return view('courses.index', compact('courses'));
    }

// Отображает указанный курс для пользователей.
public function show(Course $course)
{
    // Проверяем доступность курса для пользователей
    if (!$course->is_available) {
        abort(404, 'Курс недоступен для записи');
    }

    // Загружаем связанные данные
    $course->loadCount('applications');
    
    // Получаем отзывы о курсе (если есть связь через заявки)
    $reviews = $course->applications()
        ->with('user')
        ->whereHas('review')
        ->get()
        ->pluck('review');

    // Добавляем похожие курсы (по цене или длительности)
    $similarCourses = Course::query()
        ->where('id', '!=', $course->id) // исключаем текущий курс
        ->where('is_active', true)
        ->where('start_date', '>=', now())
        ->where(function($query) use ($course) {
            // Похожие по цене (в диапазоне ±30%)
            if ($course->price) {
                $query->whereBetween('price', [
                    $course->price * 0.7, 
                    $course->price * 1.3
                ]);
            }
            
            // ИЛИ похожие по длительности (в диапазоне ±10 часов)
            if ($course->duration_hours) {
                $query->orWhereBetween('duration_hours', [
                    max(1, $course->duration_hours - 10),
                    $course->duration_hours + 10
                ]);
            }
        })
        ->limit(3)
        ->get();

        return view('courses.show', compact('course', 'reviews', 'similarCourses'));
}

  // Администратор: Отображает список всех курсов с полными фильтрами.
    public function adminIndex(Request $request)
    {
        // Проверка прав администратора
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Доступ запрещен');
        }

        // Собираем все фильтры для админа
        $filters = [
            'search' => $request->search,
            'price_min' => $request->price_min,
            'price_max' => $request->price_max,
            'duration_min' => $request->duration_min,
            'duration_max' => $request->duration_max,
            'is_active' => $request->is_active,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
        ];

        // Параметры сортировки
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        // Получаем курсы для админки
        $courses = Course::query()
            ->filter($filters)
            ->sort($sortField, $sortDirection)
            ->withCount('applications')
            ->paginate(15)
            ->withQueryString();

            return view('admin.courses', compact('courses'));
    }

 // Администратор: Отображает указанный курс со всеми деталями.
    public function adminShow(Course $course)
    {
        // Проверка прав администратора
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Доступ запрещен');
        }

        // Загружаем все связанные данные для админа
        $course->load([
            'applications' => function($query) {
                $query->with('user')->latest();
            }
        ]);

        return view('admin.course-show', compact('course'));
    }

  // Показать форму для создания нового курса (только для администратора).
    public function create()
    {
        // Проверка прав администратора
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Доступ запрещен');
        }

        return view('admin.course-create');
    }

  // Сохранить новый созданный курс (только для администратора).
    public function store(Request $request)
    {
        // Проверка прав администратора
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Доступ запрещен');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_hours' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date|after_or_equal:today',
            'max_students' => 'nullable|integer|min:1',
            'is_active' => 'sometimes|boolean'
        ]);

        // Устанавливаем значение is_active по умолчанию, если не передано
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $course = Course::create($validated);

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Курс успешно создан');
    }

 // Показать форму для редактирования указанного курса (только для администратора)
    public function edit(Course $course)
    {
        // Проверка прав администратора
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Доступ запрещен');
        }

        return view('admin.course-edit', compact('course'));
    }

// Обновить указанный курс (только для администратора).
    public function update(Request $request, Course $course)
    {
        // Проверка прав администратора
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Доступ запрещен');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_hours' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'max_students' => 'nullable|integer|min:1',
            'is_active' => 'sometimes|boolean'
        ]);

        // Обрабатываем флаг is_active
        $validated['is_active'] = $request->has('is_active');

        $course->update($validated);

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Курс успешно обновлен');
    }

 // Удалить указанный курс (только для администратора).
    public function destroy(Course $course)
    {
        // Проверка прав администратора
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Доступ запрещен');
        }

        // Проверяем, есть ли связанные заявки
        if ($course->applications()->exists()) {
            return back()->with('error', 'Нельзя удалить курс, на который уже есть заявки');
        }

        $course->delete();

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Курс успешно удален');
    }

   // Переключить статус активности курса (только для администратора).
    public function toggleStatus(Course $course)
    {
        // Проверка прав администратора
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Доступ запрещен');
        }

        $course->update([
            'is_active' => !$course->is_active
        ]);

        return back()->with('success', 'Статус курса изменен');
    }

 // API endpoint для AJAX-поиска (для автозаполнения пользователей).
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);

        $courses = Course::query()
            ->filter([
                'search' => $request->query,
                'only_available' => true
            ])
            ->sort('name', 'asc')
            ->limit(5)
            ->get(['id', 'name', 'price', 'start_date']);

        return response()->json($courses);
    }

  // Получить статистику по курсу (только для администратора).
    public function statistics()
    {
        // Проверка прав администратора
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Доступ запрещен');
        }

        $stats = [
            'total_courses' => Course::count(),
            'active_courses' => Course::where('is_active', true)->count(),
            'upcoming_courses' => Course::where('start_date', '>=', now())->count(),
            'total_applications' => \App\Models\Application::count(),
            'popular_courses' => Course::withCount('applications')
                ->orderBy('applications_count', 'desc')
                ->limit(5)
                ->get()
        ];

        return view('admin.courses.statistics', compact('stats'));
    }
}