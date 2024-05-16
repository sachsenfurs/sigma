<?php

use App\Http\Controllers\Api\AuctionEndpoint;
use App\Http\Controllers\Api\SignageEndpointController;
use App\Http\Controllers\Api\SocialsEndpoint;
use App\Http\Resources\EssentialLocationsApiResource;
use App\Http\Resources\EventApiResource;
use App\Http\Resources\LocationApiResource;
use App\Models\SigLocation;
use App\Models\TimetableEntry;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
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

Route::get("/events", [SignageEndpointController::class, 'events'])->name("api.events");
Route::get("/locations", [SignageEndpointController::class, 'locations'])->name("api.locations");
Route::get("/socials", [SignageEndpointController::class, "socials"])->name("api.socials");
Route::get("/essentials", [SignageEndpointController::class, 'essentials'])->name("api.essentials");

//Route::get("/auctions", [AuctionEndpoint::class, "index"])->name("api.auction");
