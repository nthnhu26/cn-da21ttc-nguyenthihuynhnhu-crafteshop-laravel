<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust authorization logic as needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $categoryId = $this->route('category') ? $this->route('category')->category_id : null;

        return [
            'category_name' => [
                'required', 
                'string', 
                'max:100', 
                Rule::unique('categories', 'category_name')->ignore($categoryId, 'category_id')
            ],
            'description' => 'nullable|string|max:500',
            'display_order' => [
                'nullable', 
                'integer', 
                'min:0', 
                'max:100',
            ],
            'is_active' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'category_name.required' => 'Tên danh mục không được để trống.',
            'category_name.unique' => 'Tên danh mục đã tồn tại.',
            'category_name.max' => 'Tên danh mục không được vượt quá 100 ký tự.',
            'description.max' => 'Mô tả không được vượt quá 500 ký tự.',
            'display_order.integer' => 'Thứ tự hiển thị phải là số nguyên.',
            'display_order.min' => 'Thứ tự hiển thị không được nhỏ hơn 0.',
            'display_order.max' => 'Thứ tự hiển thị không được vượt quá 100.',
            'display_order.regex' => 'Thứ tự hiển thị chỉ được chứa số.',
        ];
    }
}