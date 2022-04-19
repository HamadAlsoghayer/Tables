<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Table;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use Spatie\Period\Period;

class Available implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($starting_time,$table_number)
    {
        $this->starting_time = Carbon::create($starting_time);
        $this->table_number = $table_number;
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
    $endingtime= Carbon::make($endingtime);
        $table = Table::where('number',$this->table_number)->first();
        $todaysReservations = $table->reservations()->whereDate('starting_time', $endingtime)->get();
        foreach($todaysReservations as $booked){
            if($this->starting_time>$booked->ending_time || $endingtime<$booked->starting_time)
            continue;
            $period = Period::make(Carbon::make($this->starting_time), Carbon::make($endingtime));
            $reservedPeriod = Period::make(Carbon::make($booked->starting_time), Carbon::make($booked->ending_time));
            if($period->overlapsWith($reservedPeriod)){
              return false;
            } 
        }

        return true;
        //
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The time period chosen is not available for reservation.';
    }
}
