<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoretableRequest;
use App\Http\Requests\UpdatetableRequest;
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
        return response()->json(Table::all());//
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

    public function availability()
    {
        //$now = now(); manual for testing purposes
        $now = Carbon::create('2022-05-07')->setTimeFromTimeString('02:00PM');
        $start_time = Carbon::create('2022-05-07')->setTimeFromTimeString('12:00 PM');
        $close_time = Carbon::create('2022-05-07')->setTimeFromTimeString('11:58 PM');
        $response = collect();

        if($now->betweenIncluded($start_time,$close_time)){
            foreach(Table::all() as $table){
                $availableSlots = $table->availability(clone($now));
                if($availableSlots){
                $response->add(['table number'=>$table->number,'availability' => $availableSlots]);}

            }

            return response()->json($response);// 
        } // including the 11:58 minute before closing

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
