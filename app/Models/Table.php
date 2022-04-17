<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reservation;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Log;
use Spatie\Period\Period;
use Spatie\Period\Precision;
use Spatie\Period\PeriodCollection;

class Table extends Model
{

    public function getRouteKeyName()
{
    return 'number';
}
    use HasFactory;
    protected $fillable = ['number','seats'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function availability($startdate = null,$enddate=null)
    {
        $now = clone $startdate;
        $initialPeriod = Period::make($now,clone($startdate)->setTimeFromTimeString('11:59PM'), Precision::MINUTE());
        $availabilitySlots = new PeriodCollection($initialPeriod);
        $reservations = $this->reservations()->whereDay('starting_time',$now)->get();
        foreach($reservations as $reservation){
            $reservedSlot = Period::make(Carbon::create($reservation->starting_time), Carbon::create($reservation->ending_time), Precision::MINUTE());
            $availabilitySlots = $availabilitySlots->subtract($reservedSlot);
        }
        $slots = collect();
        if($availabilitySlots->isEmpty()){return null;}
        $i=0;
        foreach($availabilitySlots as $availabilitySlot)
        {   
            $s=clone $availabilitySlot;
            $s=clone $s->includedStart();
            $e= clone $availabilitySlot;
            $e=clone $e->includedEnd();
            Log::info($availabilitySlot->start()->format('H:i'));
            $slots->add([++$i =>['from'=>  $s->format('H:i'), 'to'=> $e->format('H:i')]]);
        }
        
        return $slots;
        
    }


}
