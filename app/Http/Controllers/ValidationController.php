<?php

namespace App\Http\Controllers;

use App\Rules\AgeRange;
use Illuminate\Support\Facades\Validator;
class ValidationController extends Controller
{
    public function validateRequest(){
         $data = [
            'email' => "dimakllockov24@gmail.com",
            'password' => "Дима@1213",
            'repit_password' => "Дима@1213",
            'phone_number' => "123456789",
            'name' => "Дима",
            'came_from' => "tv",
            'date_birth' => "01/01/2009",
        ];
         $validated = Validator::make($data, [
            'email' => 'required|email|min:6',
            'password' => 'required|min:9|regex:/[a-zA-Zа-яА-ЯёЁ]/|regex:/[0-9]/|same:repit_password',
            'repit_password' => 'required|same:password',
            'phone_number' => 'digits_between:6,15',
            'name' => 'required|string|regex:/^[a-zA-Zа-яА-ЯёЁ]+$/|max:30',
            'came_from' => 'string|in:site,city,tv,others',
           'date_birth' => ['required', 'date_format:d/m/Y', new AgeRange()],
         ], [
             'email.required' => 'Поле электронной почты обязательно для заполнения.',
            'email.email' => 'Поле электронной почты должно содержать действительный адрес электронной почты.',
            'email.min' => 'Электронная почта должна быть длиннее пяти символов.',
            'password.required' => 'Поле пароля обязательно для заполнения.',
            'password.min' => 'Пароль должен быть длиннее восьми символов.',
            'password.regex' => 'Пароль должен содержать буквы и цифры.',
            'password.same' => 'Пароль должен совпадать с полем подтверждения пароля.',
            'repit_password.required' => 'Поле подтверждения пароля обязательно для заполнения.',
            'repit_password.same' => 'Поле подтверждения пароля должно совпадать с паролем.',
            'phone_number.digits_between' => 'Номер телефона должен содержать от 5 до 15 цифр.',
            'name.required' => 'Поле имени обязательно для заполнения.',
            'name.string' => 'Имя должно быть строкой.',
            'name.regex' => 'Имя должно содержать только буквы.',
            'name.max' => 'Имя не должно превышать 30 символов.',
            'came_from.string' => 'Поле откуда пришел  должно быть строкой.',
            'came_from.in' => 'Поле откуда пришел должно содержать одно из допустимых значений.',
            'date_birth.required' => 'Поле даты рождения обязательно для заполнения.',
            'date_birth.date_format' => 'Дата рождения должна быть в формате дд/мм/гггг.',
         ]);

         if ($validated->fails()) {
            return response()->json([
                'status' => 'false',
                'message' => $validated->errors()
            ]);
        }

        return response()->json([
            'status' => 'true',
            'message' => 'Регистрация успешна'
        ]);


    }
}
