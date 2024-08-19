<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactUpdateRequest extends FormRequest
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
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'address' => 'required',
            'bank_name' => 'required',
            'bank_owner' => 'required',
            'bank_acc_num' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Họ không được để trống',
            'last_name.required' => 'Tên không được để trống',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'phone.required' => 'Số điện thoại không được để trống',
            'phone.numeric' => 'Số điện thoại phải là một dãy số',
            'address.required' => 'Địa chỉ không được để trống',

            'bank_name.required' => 'Tên ngân hàng không được để trống',
            'bank_owner.required' => 'Tên chủ tài khoản không được để trống',
            'bank_acc_num.required' => 'Số tài khoản không được để trống',
        ];
    }
}
