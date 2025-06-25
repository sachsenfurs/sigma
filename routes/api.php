<?php

use App\Http\Controllers\Api\LassieExportEndpoint;
use App\Http\Controllers\Api\SignageEndpointController;
use App\Http\Controllers\Api\UserCalendarController;
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

Route::get("/events", [SignageEndpointController::class, 'events'])->name("api.events");
Route::get("/locations", [SignageEndpointController::class, 'locations'])->name("api.locations");
Route::get("/socials", [SignageEndpointController::class, "socials"])->name("api.socials");
Route::get("/essentials", [SignageEndpointController::class, 'essentials'])->name("api.essentials");
Route::get("/artshowItems", [SignageEndpointController::class, 'artshowItems'])->name("api.artshow_items");
Route::get("/announcements", [SignageEndpointController::class, 'announcements'])->name("api.announcements");
Route::get("/lassie-export", LassieExportEndpoint::class)->name("lassie-export.index");

Route::get("/user-calendar/{calendar}", [UserCalendarController::class, 'show'])->name("user-calendar.show");

//Route::get("/auctions", [AuctionEndpoint::class, "index"])->name("api.auction");
