<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route()->user;

        $emailRule = 'required|email|unique:users,email';
        $phoneRule = [
            'required',
            'regex:/^(032|033|034|035|036|037|038|039|096|097|098|086|083|084|085|081|082|088|091|094|070|079|077|076|078|090|093|089|056|058|092|059|099)[0-9]{7}$/',
            'unique:users,phone'
        ];

        if ($id) {
            $emailRule .= ",{$id}";
            $phoneRule[array_key_last($phoneRule)] = 'unique:users,phone,' . $id;
            $name = $this->name;
            $email = $this->email;
            $phone = $this->phone;
            $password = $this->password;

            $rules = [];
            if ($name) {
                $rules['name'] = 'required|min:4';
            }
            if ($email) {
                $rules['email'] = $emailRule;
            }
            if ($phone) {
                $rules['phone'] = $phoneRule;
            }
            if ($password) {
                $rules['password'] = 'required|min:8';
            }

            return $rules;
        }

        return [
            'name' => 'required|min:4',
            'email' => $emailRule,
            'phone' => $phoneRule,
            'password' => 'required|min:8',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute không được để trống',
            'email' => 'Email không đúng định dạng',
            'min' => ':attribute phải từ :min ký tự',
            'unique' => ':attribute đã được đăng ký',
            'phone.regex' => 'Số điện thoại không hợp lệ'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Họ tên',
            'email' => 'Email',
            'phone' => 'Số điện thoại',
            'password' => 'Mật khẩu',
        ];
    }
}
