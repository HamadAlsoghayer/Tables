<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

   Route::get('/user', function (Request $request) {
    return response()->json($request->user()->load('roles:name'));
})->middleware(['auth:sanctum']); 

Route::post('/login', function (Request $request) {    
    $attr = $request->validate([
    'employee_number' => 'required|digits:4',
    'password' => 'required',
]);

if (!Auth::attempt($attr)) {
    return $this->error('Credentials not match', 401);
}
auth()->user()->tokens()->delete();
return response()->json([
    'token' => auth()->user()->createToken('API Token')->plainTextToken
]);

});
Route::middleware('auth:sanctum')->prefix('employees')->group(function(){
    Route::post('/', [UserController::class,'store']);
    Route::delete('/{user:employee_number}', [UserController::class,'destroy']);
    Route::get('/', [UserController::class,'index']);
});
Route::middleware('auth:sanctum')->prefix('tables')->group(function(){    
    Route::get('/timeslots', [TableController::class,'availability']);
    Route::post('/', [TableController::class,'store']);
    Route::delete('/{table}', [TableController::class,'destroy']);
    Route::get('/', [TableController::class,'index']);
});
Route::middleware('auth:sanctum')->prefix('reservations')->group(function(){
    Route::get('/', [ReservationController::class,'index']);
    Route::post('/', [ReservationController::class,'store']);
    Route::delete('/{reservation}', [ReservationController::class,'destroy']);

});
