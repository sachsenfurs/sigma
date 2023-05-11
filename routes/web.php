<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Auth\LoginController;
use \App\Http\Controllers\Auth\OAuthLoginController;
use \App\Http\Controllers\Public\TableViewController;
use \App\Http\Controllers\Public\TimeslotShowController;
use \App\Http\Controllers\HomeController;
use \App\Http\Controllers\User\UserController;
use \App\Http\Controllers\Sig\SigEventController;
use \App\Http\Controllers\Sig\SigHostController;
use \App\Http\Controllers\Sig\SigLocationController;
use \App\Http\Controllers\TimetableController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| routes are loaded by the RouteServiceProvider within a group which
| Here is where you can register web routes for your application. These
| contains the "web" middleware group. Now create something great!
|
*/



// Auth
Route::get("/login", [LoginController::class, 'showLoginForm'])->name("login");
Route::post("/login", [LoginController::class, 'login']);
Route::post("/logout", [LoginController::class, 'logout'])->name("logout");

Route::get("/oauthlogin", [OAuthLoginController::class, 'index'])->name("oauthlogin");
Route::get("/oauth", [OAuthLoginController::class, 'redirect']);

Route::get("/table", [TableViewController::class, 'index'])->name("public.tableview");
Route::get("/show/{entry}", [TimeslotShowController::class, 'index'])->name("public.timeslot-show");
Route::group(['middleware' => "auth"], function() {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/users', [UserController::class, 'index'])->name("users.index");
    Route::post('/users', [UserController::class, 'store'])->name("users.store");
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name("users.destroy");

    // SIG Events
    Route::get("/sigs", [SigEventController::class, 'index'])->name("sigs.index");
    Route::get("/sigs/create", [SigEventController::class, 'create'])->name("sigs.create");
    Route::post("/sigs", [SigEventController::class, 'store'])->name("sigs.store");
    Route::get("/sigs/{sig}/edit", [SigEventController::class, 'show'])->name("sigs.edit");
    Route::put("/sigs/{sig}", [SigEventController::class, 'update'])->name("sigs.update");
    Route::delete("/sigs/{sig}", [SigEventController::class, 'destroy'])->name("sigs.destroy");

    // SIG Hosts
//    Route::get("/hosts", [SigHostController::class, 'index'])->name("hosts.index");
//    Route::get("/hosts/{host}", [SigHostController::class, 'show'])->name("hosts.show");
//    Route::get("/hosts/{host}/edit", [SigHostController::class, 'edit'])->name("hosts.edit");
//    Route::get("/hosts/{host}", [SigHostController::class, 'update'])->name("hosts.update");
    Route::resource("/hosts", SigHostController::class);

    // SIG Locations
//    Route::get("/locations", [SigLocationController::class, 'index'])->name("locations.index");
//    Route::get("/locations/{location}", [SigLocationController::class, 'show'])->name("locations.show");
    Route::resource("/locations", SigLocationController::class);
    // Timetable
    Route::get("/timetable", [TimetableController::class, 'index'])->name("timetable.index");
    Route::post("/timetable", [TimetableController::class, 'store'])->name("timetable.store");
    Route::get("/timetable/{entry}/edit", [TimetableController::class, "edit"])->name("timetable.edit");
    Route::put("/timetable/{entry}", [TimetableController::class, 'update'])->name("timetable.update");
    Route::delete("/timetable/{entry}", [TimetableController::class, 'destroy'])->name("timetable.destroy");
});
