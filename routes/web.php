<?php

use Illuminate\Support\Facades\Route;

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
Route::get("/login", [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name("login");
Route::post("/login", [\App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post("/logout", [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name("logout");

Route::get("/oauthlogin", [\App\Http\Controllers\Auth\OAuthLoginController::class, 'index'])->name("oauthlogin");
Route::get("/oauth", [\App\Http\Controllers\Auth\OAuthLoginController::class, 'redirect']);

Route::get("/secretlogin", function() {
    if(config("app.debug")) {
        $u = \App\Models\User::find(1);
        auth()->guard()->login($u);
        return redirect("/");
    }
});
Route::group(['middleware' => "auth"], function() {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/users', [\App\Http\Controllers\User\UserController::class, 'index'])->name("users.index");
    Route::post('/users', [\App\Http\Controllers\User\UserController::class, 'store'])->name("users.store");
    Route::delete('/users/{user}', [\App\Http\Controllers\User\UserController::class, 'destroy'])->name("users.destroy");

    // SIG Events
    Route::get("/sigs", [\App\Http\Controllers\Sig\SigEventController::class, 'index'])->name("sigs.index");
    Route::get("/sigs/create", [\App\Http\Controllers\Sig\SigEventController::class, 'create'])->name("sigs.create");
    Route::post("/sigs", [\App\Http\Controllers\Sig\SigEventController::class, 'store'])->name("sigs.store");
    Route::get("/sigs/{sig}/edit", [\App\Http\Controllers\Sig\SigEventController::class, 'show'])->name("sigs.edit");
    Route::put("/sigs/{sig}", [\App\Http\Controllers\Sig\SigEventController::class, 'update'])->name("sigs.update");
    Route::delete("/sigs/{sig}", [\App\Http\Controllers\Sig\SigEventController::class, 'destroy'])->name("sigs.destroy");

    // SIG Hosts
    Route::get("/hosts", [\App\Http\Controllers\Sig\SigHostController::class, 'index'])->name("hosts.index");
    Route::get("/hosts/{host}", [\App\Http\Controllers\Sig\SigHostController::class, 'show'])->name("hosts.show");

    // SIG Locations
    Route::get("/locations", [\App\Http\Controllers\Sig\SigLocationController::class, 'index'])->name("locations.index");
    Route::get("/locations/{location}", [\App\Http\Controllers\Sig\SigLocationController::class, 'show'])->name("locations.show");

    // Timetable
    Route::get("/timetable", [\App\Http\Controllers\TimetableController::class, 'index'])->name("timetable.index");
    Route::post("/timetable", [\App\Http\Controllers\TimetableController::class, 'store'])->name("timetable.store");
    Route::get("/timetable/{entry}/edit", [\App\Http\Controllers\TimetableController::class, "edit"])->name("timetable.edit");
    Route::put("/timetable/{entry}", [\App\Http\Controllers\TimetableController::class, 'update'])->name("timetable.update");
    Route::delete("/timetable/{entry}", [\App\Http\Controllers\TimetableController::class, 'destroy'])->name("timetable.destroy");
});
