<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Auth::user()->applications()->with('course')->latest()->get();
        return view('applications.index', compact('applications'));
    }

    public function create()
    {
        // Проверка авторизации
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Получаем только активные курсы с будущими датами
        $courses = Course::where('is_active', true)
            ->where('start_date', '>', now())
            ->get();
        
        // Получаем все доступные даты начала курсов
        $availableDates = Course::where('is_active', true)
            ->where('start_date', '>', now())
            ->orderBy('start_date', 'asc')
            ->pluck('start_date')
            ->map(function ($date) {
                return $date->format('Y-m-d');
            })
            ->unique()
            ->values()
            ->toArray();
        
        // Если передан ID курса в GET параметре
        $selectedCourseId = request()->get('course_id');
        
        // Если выбран курс, получаем его дату
        $selectedCourseDate = null;
        if ($selectedCourseId) {
            $selectedCourse = Course::find($selectedCourseId);
            if ($selectedCourse && $selectedCourse->start_date) {
                $selectedCourseDate = $selectedCourse->start_date->format('Y-m-d');
            }
        }
        
        return view('applications.create', compact('courses', 'selectedCourseId', 'availableDates', 'selectedCourseDate'));
    }

    public function store(Request $request)
    {
        // Проверка авторизации
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'desired_start_date' => [
                'required',
                'date',
                'after:today',
                function ($attribute, $value, $fail) use ($request) {
                    $isValidDate = Course::where('is_active', true)
                        ->whereDate('start_date', $value)
                        ->exists();
                    
                    if (!$isValidDate) {
                        $fail('Выбранная дата не является датой начала доступного курса.');
                    }
                }
            ],
            'payment_method' => 'required|in:cash,transfer',
        ]);

        // ПРОВЕРКА НА ПОВТОРНУЮ ЗАПИСЬ
        $existingApplication = Application::where('user_id', Auth::id())
            ->where('course_id', $request->course_id)
            ->whereIn('status', ['new', 'in_progress'])
            ->first();

        if ($existingApplication) {
            return back()->with('error', 'Вы уже записаны на этот курс. Нельзя записаться повторно.');
        }

        // ПРОВЕРКА ДОСТУПНОСТИ КУРСА
        $course = Course::find($request->course_id);
        
        if (!$course->is_active) {
            return back()->with('error', 'Этот курс сейчас неактивен.');
        }
        
        if ($course->start_date < now()) {
            return back()->with('error', 'Этот курс уже начался и недоступен для записи.');
        }
        
        // ПРОВЕРКА СВОБОДНЫХ МЕСТ
        if ($course->max_students) {
            $occupied = $course->applications()
                ->whereIn('status', ['new', 'in_progress'])
                ->count();
                
            if ($occupied >= $course->max_students) {
                return back()->with('error', 'На этом курсе нет свободных мест.');
            }
        }

        // СОЗДАНИЕ ЗАЯВКИ
        Application::create([
            'user_id' => Auth::id(),
            'course_id' => $request->course_id,
            'desired_start_date' => $request->desired_start_date,
            'payment_method' => $request->payment_method,
            'status' => 'new',
        ]);

        return redirect()->route('applications.index')
            ->with('success', 'Заявка успешно отправлена!');
    }

    public function addReview(Request $request, Application $application)
    {
        if ($application->user_id !== Auth::id() || $application->status !== 'completed') {
            return back()->with('error', 'Вы не можете оставить отзыв для этой заявки.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::updateOrCreate(
            ['application_id' => $application->id],
            [
                'user_id' => Auth::id(),
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );

        return back()->with('success', 'Отзыв успешно сохранен!');
    }
}