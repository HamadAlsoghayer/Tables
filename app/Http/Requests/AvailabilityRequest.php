<?php

namespace App\Http\Requests;

use App\Models\Table;
use Illuminate\Foundation\Http\FormRequest;
class AvailabilityRequest extends FormRequest
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
        $biggest = Table::orderByDesc('seats')->firstOrFail()->seats;
        return ['size'=>'required|numeric|between:1,12|lte:'.$biggest .''];
    }

        /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return ['size.lte'=>'there are no tables that can fit the size of customer group'];
    }


}
