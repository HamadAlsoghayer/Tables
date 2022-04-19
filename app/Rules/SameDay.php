<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class SameDay implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($starting_time)
    {
        $this->starting_time = Carbon::create($starting_time);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $endingtime)
    {
        $endingtime= Carbon::create($endingtime);
        return $endingtime->isSameDay($this->starting_time);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'both Starting time and Ending time should be in the same day.';
    }
}
