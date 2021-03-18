<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlightController;

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

// Returns all 123 Milhas API Flights. (Question 1)
Route::get('/getAll',[FlightController::class,'getAllFlights']);

// Returns json object with grouped flights (Question 2 and 4)
Route::get('/group',[FlightController::class,'groupFlights']);

// Returns json object with grouped flights (Question 3)
Route::get('/sort',[FlightController::class,'sortGroupFlights']);
