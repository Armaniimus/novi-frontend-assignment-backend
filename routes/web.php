<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Api;
use App\Http\Controllers\Login;
Route::get('/login', [Login::class, 'run']);
Route::get('/login', [Api::class, 'login']);

use App\Http\Controllers\Auth;
Route::get('/check', [Auth::class, 'check']);

Route::fallback(function () {
    /** This will check for the 404 view page unders /resources/views/errors/404 route */
    // return 404;
});
