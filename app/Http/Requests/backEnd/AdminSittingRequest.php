<?php

namespace App\Http\Requests\backEnd;

use Illuminate\Foundation\Http\FormRequest;

class AdminSittingRequest extends FormRequest
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
        return 
        [
            'system_name'   => 'required',
            'phone'         => 'required',
            'address'       => 'required',
            'customer_parent_account_number'       => 'required',
            'supplier_parent_account_number'       => 'required',
        ];
    }

    public function messages()
    {
        return
        [
            'system_name.required'    => 'يجب ادخال  اسم الشركة ',
            'phone.required' => 'يجب ادخال  تليفون الشركة',
            'address.required' => 'يجب ادخال  عنوان الشركة',
            'customer_parent_account_number.required' => 'يجب ادخال اسم الحساب الرئيسي للعملاء',
            'supplier_parent_account_number.required' => 'يجب ادخال اسم الحساب الرئيسي للموردين',
        ];
    }

}
