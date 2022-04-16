<?php

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
