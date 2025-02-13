<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassRequest extends FormRequest
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
        return [
            'parent_id' => 'required',
            'district_id' => 'required',
            'ward_id' => 'required',
            'detail' => 'required',
            'subjects' => 'required',
            'grade_id' => 'required',
            'num_of_sessions' => '',
            'num_of_students' => 'required',
            'gender_tutor' => '',
            'level_id' => '',
            'tuition' => 'required',
            'request' => '',
            'times' => 'required', //Thoi gian hoc
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute không được để trống',
        ];
    }

    public function attributes()
    {
        return [
            'parent_id' => 'Phụ huynh',
            'district_id' => 'Quận/huyện',
            'ward_id' => 'Phường/xã',
            'detail' => 'Địa chỉ',
            'subjects' => 'Môn học',
            'grade_id' => 'Khối lớp',
            'num_of_sessions' => 'Số buổi',
            'num_of_students' => 'Số người học',
            'gender_tutor' => 'Giới tính gia sư',
            'level_id' => 'Trình độ gia sư',
            'tuition' => 'Học phí',
            'request' => 'Yêu cầu khác',
            'times' => 'Thời gian học', //Thoi gian hoc
        ];
    }
}
