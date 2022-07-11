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



// Auth
Route::get("/login", [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name("login");
Route::post("/login", [\App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post("/logout", [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name("logout");

Route::get("/oauthlogin", [\App\Http\Controllers\Auth\OAuthLoginController::class, 'index'])->name("oauthlogin");
Route::get("/oauth", [\App\Http\Controllers\Auth\OAuthLoginController::class, 'redirect']);

Route::group(['middleware' => "auth"], function() {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/users', [\App\Http\Controllers\User\UserController::class, 'index'])->name("users.index");
    Route::post('/users', [\App\Http\Controllers\User\UserController::class, 'store'])->name("users.store");
    Route::delete('/users/{user}', [\App\Http\Controllers\User\UserController::class, 'destroy'])->name("users.destroy");

    // SIG Events
    Route::get("/sigs", [\App\Http\Controllers\Sig\SigEventController::class, "index"])->name("sigs.index");
    Route::get("/sigs/create", [\App\Http\Controllers\Sig\SigEventController::class, "create"])->name("sigs.create");
    Route::post("/sigs", [\App\Http\Controllers\Sig\SigEventController::class, "store"])->name("sigs.store");
    Route::get("/sigs/{sig}/edit", [\App\Http\Controllers\Sig\SigEventController::class, "show"])->name("sigs.edit");

    // SIG Hosts
    Route::get("/hosts", [\App\Http\Controllers\Sig\SigHostController::class, "index"])->name("hosts.index");

    // SIG Locations
    Route::get("/locations", [\App\Http\Controllers\Sig\SigLocationController::class, "index"])->name("locations.index");

});
