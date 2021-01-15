<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\Api;
use App\Http\Controllers\Auth;

Route::post('/login', [Api::class, 'login']);
Route::post('/overview', [Api::class, 'overview']);
Route::post('/overview/{id}', [Api::class, 'overviewSpecific']);


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
