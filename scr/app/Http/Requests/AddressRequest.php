<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'address_detail' => 'required|string|max:255',
            'id_province' => 'required|exists:provinces,code',
            'id_district' => 'required|exists:districts,code', 
            'id_ward' => 'required|exists:wards,code',
            'is_default' => 'boolean'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên.',
            'address_detail.required' => 'Vui lòng nhập địa chỉ chi tiết.',
            'id_province.required' => 'Vui lòng chọn tỉnh/thành phố.',
            'id_district.required' => 'Vui lòng chọn quận/huyện.',
            'id_ward.required' => 'Vui lòng chọn phường/xã.'
        ];
    }
}