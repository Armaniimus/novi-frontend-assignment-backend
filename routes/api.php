<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

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

Route::post('/login', [Api::class, 'login']);
Route::post('/logout', [Api::class, 'logout']);

Route::post('/overview', [Api::class, 'overview']);
Route::post('/overview/{id}', [Api::class, 'overviewSpecific']);

Route::post('/accountbeheer', [Api::class, 'accountbeheer']);
Route::post('/accountbeheer/create', [Api::class, 'accountbeheerCreate']);
Route::post('/accountbeheer/update', [Api::class, 'accountbeheerUpdate']);
Route::post('/accountbeheer/delete', [Api::class, 'accountbeheerDelete']);

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
