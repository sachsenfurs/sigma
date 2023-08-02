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

Route::middleware('auth:sanctum')->get('/users', function (Request $request) {
    return $request->user();
});

Route::get("/events", [\App\Http\Controllers\Api\EventsEndpoint::class, "index"])->name("events");
Route::get("/locations", [\App\Http\Controllers\Api\LocationsEndpoint::class, "index"])->name("api.locations");
Route::get("/socials", [\App\Http\Controllers\Api\SocialsEndpoint::class, "index"])->name("api.socials");
Route::get("/essentials", [\App\Http\Controllers\Api\EssentialsEndpoint::class, "index"])->name("api.essentials");
