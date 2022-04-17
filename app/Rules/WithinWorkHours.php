<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class WithinWorkHours implements Rule
{


    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $time)
    {
    $time = Carbon::create($time);
    $start =  clone $time;
    $end =  clone $time;
    $start->setTimeFromTimeString('12:00PM');
    $end->setTimeFromTimeString('11:59PM');
        return $time->betweenIncluded($start,$end);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute should be within working hours';
    }
}
