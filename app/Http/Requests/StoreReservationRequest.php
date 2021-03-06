<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Table;
use App\Rules\Available;
use App\Rules\SameDay;
use App\Rules\WithinWorkHours;

class StoreReservationRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
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
        return ['customer_name'=>['required','regex:/[a-zA-Z0-9\s]+/'],'table_number'=>'required|bail|exists:tables,number','starting_time'=>['required','date','after:now',new WithinWorkHours()],'ending_time'=>['required','date','after:starting_time',new WithinWorkHours(),new SameDay($this->starting_time),new Available($this->starting_time,$this->table_number)],        ];
    }
    protected function passedValidation()
    {
    $table_id = Table::where('number',$this->table_number)->get()->first()->id;
    $this->merge(['table_id'=>$table_id]);
    }

}
