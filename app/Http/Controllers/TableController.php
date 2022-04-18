<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoretableRequest;
use App\Http\Requests\UpdatetableRequest;
use App\Http\Requests\AvailabilityRequest;
use App\Models\table;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class TableController extends Controller
{
    public function __construct()
    {
       $this->authorizeResource(Table::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['Tables' =>Table::all()]);//
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoretableRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoretableRequest $request)
    {
        Table::create($request->toArray());
        return response()->json('Table Created Successfully');//
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\table  $table
     * @return \Illuminate\Http\Response
     */
    public function show(table $table)
    {
        //
    }

    public function availability(AvailabilityRequest $request)
    {
        
        $now = now();
        if($request->test_time){ // THIS IS ONLY FOR TESTING CONVENIENVE AND WOULD BE REMOVED OTHERWISE
            $now = Carbon::create($request->test_time);
        }
        $start_time = clone($now); $start_time->setTimeFromTimeString('12:00 PM');
        $close_time = clone($now); $close_time->setTimeFromTimeString('11:59 PM');
        $response = collect();

        if($now->isBefore($close_time)){
            $acceptedSize = Table::where('seats','>=',$request->size)->orderBy('seats')->limit(1)->firstOrFail()->seats;
            foreach(Table::where('seats',$acceptedSize)->get() as $table){
                $availableSlots = $table->availability(clone($now>$start_time?$now:$start_time));
                if($availableSlots){
                $response->add(['table number'=>$table->number,'seats'=>$table->seats,'availability' => $availableSlots]);}

            }

            return response()->json($response);
        } 
        return response()->json('time is outside of work hours for today');//
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatetableRequest  $request
     * @param  \App\Models\table  $table
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatetableRequest $request, table $table)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\table  $table
     * @return \Illuminate\Http\Response
     */
    public function destroy(table $table)
    {
        if($table->reservations->empty()){
            $table->delete();
            return response()->json('deleted');
        }//
        return response()->json('not deleted');//
    }
}
