<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BannerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'link' => 'nullable|url',
            'start_datetime' => [
                'required',
                'date',
                $this->isMethod('PUT') ? 'after_or_equal:' . now()->subDay() : 'after_or_equal:' . now(),
            ],
            'end_datetime' => [
                'required',
                'date',
                'after:start_datetime',
            ],
        ];

        if ($this->isMethod('POST')) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
        } elseif ($this->isMethod('PUT')) {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'title.required' => 'Tiêu đề không được để trống',
            'title.max' => 'Tiêu đề không được vượt quá 100 ký tự',
            'start_datetime.required' => 'Thời gian bắt đầu không được để trống',
            'start_datetime.date' => 'Thời gian bắt đầu không hợp lệ',
            'start_datetime.after_or_equal' => 'Thời gian bắt đầu phải bằng hoặc sau thời điểm hiện tại',
            'end_datetime.required' => 'Thời gian kết thúc không được để trống',
            'end_datetime.date' => 'Thời gian kết thúc không hợp lệ',
            'end_datetime.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu',
            'link.url' => 'Địa chỉ URL không hợp lệ',
            'image.required' => 'Ảnh không được để trống',
            'image.image' => 'Tệp tải lên phải là hình ảnh',
            'image.mimes' => 'Định dạng ảnh không hợp lệ. Chỉ chấp nhận jpeg, png, jpg, gif',
            'image.max' => 'Kích thước ảnh không được vượt quá 2MB',
        ];
    }
}