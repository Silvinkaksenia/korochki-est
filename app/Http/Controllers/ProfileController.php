<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Валидация с проверкой формата телефона
        $request->validate([
            'phone' => [
                'required',
                'string',
                'regex:/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/',
                Rule::unique('users')->ignore($user->id)
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('Текущий пароль указан неверно.');
                    }
                }
            ]
        ], [
            'phone.regex' => 'Телефон должен быть в формате: +7 (999) 123-45-67',
            'phone.required' => 'Поле телефона обязательно для заполнения',
            'phone.unique' => 'Этот номер телефона уже используется',
            'email.required' => 'Поле email обязательно для заполнения',
            'email.email' => 'Введите корректный email адрес',
            'email.unique' => 'Этот email уже используется',
            'current_password.required' => 'Необходимо ввести текущий пароль'
        ]);

        // Обновляем данные
        $user->update([
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Профиль успешно обновлен!');
    }
}