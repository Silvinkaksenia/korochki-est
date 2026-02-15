@extends('layouts.app')

@section('title', 'Новая заявка')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Новая заявка на обучение</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('applications.store') }}" id="applicationForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="course_id" class="form-label">Наименование курса</label>
                        <select class="form-select @error('course_id') is-invalid @enderror" 
                                id="course_id" name="course_id" required>
                            <option value="">Выберите курс...</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" 
                                        data-start-date="{{ $course->start_date ? $course->start_date->format('Y-m-d') : '' }}"
                                        {{ old('course_id', $selectedCourseId) == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }} 
                                    ({{ number_format($course->price, 0, ',', ' ') }} ₽)
                                    @if($course->start_date)
                                        - начало {{ $course->start_date->format('d.m.Y') }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="desired_start_date" class="form-label">Желаемая дата начала обучения</label>
                        <select class="form-select @error('desired_start_date') is-invalid @enderror" 
                                id="desired_start_date" name="desired_start_date" required>
                            <option value="">Выберите дату начала...</option>
                            @if($selectedCourseDate)
                                <option value="{{ $selectedCourseDate }}" selected>
                                    {{ \Carbon\Carbon::parse($selectedCourseDate)->format('d.m.Y') }}
                                    (Дата выбранного курса)
                                </option>
                            @endif
                            @foreach($availableDates as $date)
                                <option value="{{ $date }}"
                                        {{ old('desired_start_date') == $date ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($date)->format('d.m.Y') }} ({{ \Carbon\Carbon::parse($date)->translatedFormat('l') }})
                                </option>
                            @endforeach
                        </select>
                        @error('desired_start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Выберите дату из доступных дат начала курсов. 
                            При выборе курса дата может подставиться автоматически.
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Способ оплаты</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" 
                                   id="cash" value="cash" 
                                   {{ old('payment_method') == 'cash' ? 'checked' : 'checked' }}>
                            <label class="form-check-label" for="cash">
                                <i class="bi bi-cash"></i> Наличными
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" 
                                   id="transfer" value="transfer"
                                   {{ old('payment_method') == 'transfer' ? 'checked' : '' }}>
                            <label class="form-check-label" for="transfer">
                                <i class="bi bi-phone"></i> Переводом по номеру телефона
                            </label>
                        </div>
                        @error('payment_method')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <h5><i class="bi bi-info-circle"></i> Важно!</h5>
                        <p class="mb-0">
                            После отправки заявки она поступит на рассмотрение администратору. 
                            Статус заявки можно отслеживать в разделе "Мои заявки".
                        </p>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-send"></i> Отправить заявку
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Вернуться назад
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const courseSelect = document.getElementById('course_id');
    const dateSelect = document.getElementById('desired_start_date');
    
    // При изменении выбора курса
    courseSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const startDate = selectedOption.getAttribute('data-start-date');
        
        if (startDate) {
            // Ищем опцию с этой датой
            for (let i = 0; i < dateSelect.options.length; i++) {
                if (dateSelect.options[i].value === startDate) {
                    dateSelect.value = startDate;
                    break;
                }
            }
            
            // Если даты нет в списке, добавляем ее
            if (dateSelect.value !== startDate) {
                const dateObj = new Date(startDate);
                const formattedDate = dateObj.toLocaleDateString('ru-RU', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    weekday: 'long'
                });
                
                const newOption = document.createElement('option');
                newOption.value = startDate;
                newOption.textContent = formattedDate + ' (Дата выбранного курса)';
                dateSelect.appendChild(newOption);
                dateSelect.value = startDate;
            }
        }
    });
    
    // Если при загрузке страницы уже выбран курс
    if (courseSelect.value) {
        courseSelect.dispatchEvent(new Event('change'));
    }
    
    // Валидация формы
    document.getElementById('applicationForm').addEventListener('submit', function(e) {
        const courseId = courseSelect.value;
        const selectedDate = dateSelect.value;
        
        if (!courseId || !selectedDate) {
            e.preventDefault();
            alert('Пожалуйста, выберите курс и дату начала обучения.');
            return false;
        }
        
        // Проверяем, что выбранная дата не раньше завтрашнего дня
        const selectedDateObj = new Date(selectedDate);
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        tomorrow.setHours(0, 0, 0, 0);
        
        if (selectedDateObj < tomorrow) {
            e.preventDefault();
            alert('Дата начала обучения должна быть не ранее завтрашнего дня.');
            return false;
        }
    });
});
</script>
@endsection