<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasRole('admin');
    }

    protected function passedValidation()
    {
    $this->merge(['password'=>bcrypt($this->password)]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return ['name'=>['required','regex:/[a-zA-Z0-9\s]+/'], 'employee_number'=>'required|unique:users|digits:4','password'=>'required|alpha_dash|min:6|confirmed'
        ];
    }
}
