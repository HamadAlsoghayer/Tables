<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Builder;


class ReservationController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Reservation::class, 'reservation');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservation = Reservation::query();
        if(request()->tables){        
            $reservation->whereHas('table',function (Builder $query) {
                $query->whereIn('number', request()->tables);
            });//
        }
        if(request()->from_date){
            $reservation->where('starting_time','>',request()->from_date);
        }
        if(request()->to_date){
            $reservation->where('ending_time','<=',request()->to_date);
        }
        return response()->json($reservation->paginate());//
    }

    public function todays()
    {
        $reservation = Reservation::whereDay('starting_time',now())->paginate();
        return response()->json($reservation);//
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreReservationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReservationRequest $request)
    {
        return response()->json(Reservation::create($request->toArray()));//
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateReservationRequest  $request
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
        if($reservation->starting_time<now() && $reservation->ending_time<now()){
            return response()->json('Cannot Delete Reservations that have already passed');
        }
        else 
        $reservation->delete();
        return response()->json('deleted');

    }
}
