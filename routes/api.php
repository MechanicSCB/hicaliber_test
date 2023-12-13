<?php

use App\Http\Controllers\HintController;
use App\Http\Controllers\HouseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/get-data', [HouseController::class, 'getData'])->name('houses.get-data');
Route::get('/get-hints', [HintController::class, 'getHints'])->name('hints.get');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
