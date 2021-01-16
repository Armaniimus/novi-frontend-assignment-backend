<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

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

Route::get('/login', [Api::class, 'login']);
Route::get('/logout', [Api::class, 'logout']);

Route::get('/overview', [Api::class, 'overview']);
Route::get('/overview/{id}', [Api::class, 'overviewSpecific']);

Route::get('/accountbeheer', [Api::class, 'accountbeheer']);
Route::get('/accountbeheer/create', [Api::class, 'accountbeheerCreate']);
Route::get('/accountbeheer/update', [Api::class, 'accountbeheerUpdate']);
Route::get('/accountbeheer/delete', [Api::class, 'accountbeheerDelete']);

Route::get('/liedbeheer', [Api::class, 'liedbeheer']);
Route::get('/liedbeheer/create', [Api::class, 'liedbeheerCreate']);
Route::get('/liedbeheer/update', [Api::class, 'liedbeheerUpdate']);
Route::get('/liedbeheer/update/songtext', [Api::class, 'liedbeheerUpdateSongtext']);
Route::get('/liedbeheer/delete', [Api::class, 'liedbeheerDelete']);

Route::fallback(function () {
    /** This will check for the 404 view page unders /resources/views/errors/404 route */
    // return 404;
});
