<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем администратора
        User::create([
            'login' => 'Admin',
            'password' => Hash::make('KorokNET'),
            'full_name' => 'Администратор',
            'phone' => '8(999)123-4567',
            'email' => 'admin@korochki-est.ru',
            'is_admin' => true,
        ]);

        // Создаем тестовые курсы
        \App\Models\Course::create([
            'name' => 'Веб-разработка на PHP и Laravel',
            'description' => 'Полный курс по созданию современных веб-приложений с использованием фреймворка Laravel',
            'duration_hours' => 120,
            'price' => 25000.00,
        ]);

        \App\Models\Course::create([
            'name' => 'Data Science с Python',
            'description' => 'Анализ данных, машинное обучение и визуализация результатов',
            'duration_hours' => 160,
            'price' => 35000.00,
        ]);

        \App\Models\Course::create([
            'name' => 'UX/UI дизайн интерфейсов',
            'description' => 'Дизайн пользовательских интерфейсов для веб и мобильных приложений',
            'duration_hours' => 80,
            'price' => 20000.00,
        ]);

        \App\Models\Course::create([
            'name' => 'Digital-маркетинг',
            'description' => 'Продвижение бизнеса в социальных сетях и интернете',
            'duration_hours' => 60,
            'price' => 18000.00,
        ]);

        // Создаем тестового пользователя
        User::create([
            'login' => 'testuser',
            'password' => Hash::make('password123'),
            'full_name' => 'Иванов Иван Иванович',
            'phone' => '8(912)345-6789',
            'email' => 'test@example.com',
            'is_admin' => false,
        ]);
    }
}