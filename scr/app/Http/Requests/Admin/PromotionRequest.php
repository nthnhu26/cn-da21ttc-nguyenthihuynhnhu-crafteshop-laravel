<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => [
                'required',
                Rule::in(['percent', 'fixed'])
            ],
            'value' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($this->input('type') === 'percent' && ($value <= 0 || $value > 100)) {
                        $fail('Giá trị khuyến mãi phần trăm phải lớn hơn 0 và nhỏ hơn hoặc bằng 100.');
                    }
                    if ($this->input('type') === 'fixed' && $value <= 0) {
                        $fail('Giá trị khuyến mãi cố định phải lớn hơn 0.');
                    }
                }
            ],
            'start_date' => ['required', 'date', 'before_or_equal:end_date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'code' => ['nullable', 'string', 'max:255', 'unique:promotions,code'],
            'max_quantity' => ['nullable', 'integer', 'min:1'],
            'usage_per_code' => ['nullable', 'integer', 'min:1'],
            'product_ids' => [
                'required_if:type,percent',
                'array',
            ],
            'product_ids.*' => ['exists:products,product_id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên chương trình khuyến mãi.',
            'name.max' => 'Tên chương trình khuyến mãi không được vượt quá 255 ký tự.',
            'type.required' => 'Vui lòng chọn loại khuyến mãi.',
            'type.in' => 'Loại khuyến mãi không hợp lệ.',
            'value.required' => 'Vui lòng nhập giá trị khuyến mãi.',
            'value.numeric' => 'Giá trị khuyến mãi phải là số.',
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'start_date.before_or_equal' => 'Ngày bắt đầu phải trước hoặc bằng ngày kết thúc.',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'min_order_amount.numeric' => 'Giá trị đơn hàng tối thiểu phải là số.',
            'min_order_amount.min' => 'Giá trị đơn hàng tối thiểu không được âm.',
            'code.string' => 'Mã khuyến mãi phải là chuỗi ký tự.',
            'code.max' => 'Mã khuyến mãi không được vượt quá 255 ký tự.',
            'code.unique' => 'Mã khuyến mãi đã tồn tại trong hệ thống.',
            'max_quantity.integer' => 'Số lượng tối đa phải là số nguyên.',
            'max_quantity.min' => 'Số lượng tối đa phải lớn hơn hoặc bằng 1.',
            'usage_per_code.integer' => 'Số lần sử dụng mỗi mã phải là số nguyên.',
            'usage_per_code.min' => 'Số lần sử dụng mỗi mã phải lớn hơn hoặc bằng 1.',
            'product_ids.required_if' => 'Vui lòng chọn ít nhất một sản phẩm cho khuyến mãi theo phần trăm.',
            'product_ids.*.exists' => 'Sản phẩm được chọn không tồn tại trong hệ thống.',
        ];
    }
}

