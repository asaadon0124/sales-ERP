<?php

namespace App\Http\Requests\backEnd;

use Illuminate\Foundation\Http\FormRequest;

class loginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

  
    public function rules(): array
    {
        return 
        [
            'email'     => ['required', 'string', 'email'],
            'password'  => 'required|min:8',
        ];
    }


    public function messages()
    {
        return
        [
            
            

            'email.required'    => 'يجب ادخال ايميل المدير ',
            'email.email'       => 'الايميل غير صحيح',

            'password.required' => 'يجب ادخال كلمة السر',
            'password.min'      => 'كلمة السر لا تقل عن 8 عناصر',
        ];
    }
}
