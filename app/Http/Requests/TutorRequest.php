<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TutorRequest extends FormRequest
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
        $avatarRule = '';
        $degreeRule = '';

        if ($this->hasFile('avatar')) {
            $avatarRule = 'nullable|image|max:2048';
        }
        if ($this->hasFile('degree')) {
            $degreeRule = 'nullable|image|max:2048';
        }

        return [
            'gender' => 'required',
            'birthday' => 'required|date|before:today',
            'address' => 'required',
            'major' => 'required',
            'school' => 'required',
            'experiences' => '',
            'avatar' => $avatarRule,
            'degree' => $degreeRule,
            'level_id' => 'required',
            'tuition_id' => 'required',
            'grades' => 'required',
            'subjects' => 'required',
            'districts' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute không được để trống',
            'date' => ':attribute không đúng định dạng',
            'birthday.before' => ':attribute không hợp lệ',
            'image' => ':attribute không hợp lệ',
            'max' => ':attribute không quá 2MB',
        ];
    }

    public function attributes()
    {
        return [
            'gender' => 'Giới tính',
            'birthday' => 'Ngày sinh',
            'address' => 'Địa chỉ',
            'major' => 'Chuyên ngành',
            'school' => 'Nơi công tác/học tập',
            'experiences' => 'Kinh nghiệm',
            'avatar' => 'Ảnh đại diện',
            'degree' => 'Ảnh bằng cấp / Thẻ sinh viên',
            'level_id' => 'Trình độ',
            'tuition_id' => 'Học phí',
            'grades' => 'Khối lớp dạy',
            'subjects' => 'Môn dạy',
            'districts' => 'Khu vực dạy',
        ];
    }
}
