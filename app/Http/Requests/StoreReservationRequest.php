<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return ['customer_name'=>['regex:/[a-zA-Z0-9\s]+/'],'table_number'=>'required|exists:tables,number','starting_time'=>'date|after:now','ending_time'=>'date|after:starting_time',
        ];
    }
}
