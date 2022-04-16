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
Route::prefix('employees')->group(function(){
    Route::post('/', [UserController::class,'store'])->middleware('auth:sanctum');
    Route::delete('/{user:employee_number}', [UserController::class,'destroy'])->middleware('auth:sanctum');
    Route::get('/', [UserController::class,'index'])->middleware('auth:sanctum');
});
Route::prefix('tables')->group(function(){
    Route::post('/', [TableController::class,'store'])->middleware('auth:sanctum');
    Route::delete('/{table:number}', [TableController::class,'destroy'])->middleware('auth:sanctum');
    Route::get('/', [TableController::class,'index']);
});
Route::middleware('auth:sanctum')->prefix('reservations')->group(function(){
    Route::get('/', [ReservationController::class,'index']);
    Route::post('/', [ReservationController::class,'store']);
});
