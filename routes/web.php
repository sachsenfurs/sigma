<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OAuthLoginController;
use App\Http\Controllers\Auth\RegSysLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Public\ConbookExportController;
use App\Http\Controllers\Public\TableViewController;
use App\Http\Controllers\Public\TimeslotShowController;
use App\Http\Controllers\Sig\SigEventController;
use App\Http\Controllers\Sig\SigHostController;
use App\Http\Controllers\Sig\SigLocationController;
use App\Http\Controllers\Sig\SigRegistrationController;
use App\Http\Controllers\Sig\SigTimeslotController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\UserRoleController;
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
Route::get("/login", [LoginController::class, 'showLoginForm'])->name("login");
Route::post("/login", [LoginController::class, 'login']);
Route::post("/logout", [LoginController::class, 'logout'])->name("logout");

Route::get("/oauthlogin", [OAuthLoginController::class, 'index'])->name("oauthlogin");
Route::get("/oauth", [OAuthLoginController::class, 'redirect']);

Route::get("/oauthlogin_regsys", [RegSysLoginController::class, 'index'])->name("oauthlogin_regsys");
Route::get("/oauth_regsys", [RegSysLoginController::class, 'redirect']);

Route::get("/table", [TableViewController::class, 'index'])->name("public.tableview");
Route::get("/show/{entry}", [TimeslotShowController::class, 'index'])->name("public.timeslot-show");
Route::group(['middleware' => "auth"], function() {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/users', [UserController::class, 'index'])->name("users.index");
    Route::post('/users', [UserController::class, 'store'])->name("users.store");
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put("/users/{user}", [UserController::class, 'update'])->name("users.update");
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name("users.destroy");

    // SIG Events
    Route::get("/sigs", [SigEventController::class, 'index'])->name("sigs.index");
    Route::get("/sigs/create", [SigEventController::class, 'create'])->name("sigs.create");
    Route::post("/sigs", [SigEventController::class, 'store'])->name("sigs.store");
    Route::get("/sigs/{sig}/edit", [SigEventController::class, 'show'])->name("sigs.edit");
    Route::put("/sigs/{sig}", [SigEventController::class, 'update'])->name("sigs.update");
    Route::delete("/sigs/{sig}", [SigEventController::class, 'destroy'])->name("sigs.destroy");
    // TEMP DEACTIVATED - Route::get("/events", [SigEventController::class, 'index'])->name("sig.indexHosts");
    // TEMP DEACTIVATED -  Route::get("/events/{event}", [SigEventController::class, 'show'])->name("sig.showHosts");

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

    // SIG Timeslots
    Route::get('/timeslots/{timeslot}/edit', [SigTimeslotController::class, 'edit'])->name('timeslots.edit');
    Route::post('/timeslots', [SigTimeslotController::class, 'store'])->name('timeslots.store');
    Route::post('/timeslots/{timeslot}', [SigTimeslotController::class, 'update'])->name('timeslots.update');
    Route::delete('/timeslots/{timeslot}', [SigTimeslotController::class, 'destroy'])->name('timeslots.destroy');

    // Registraton
    Route::post('/register/{timeslot}', [SigRegistrationController::class, 'register'])->name('registration.register');
    Route::delete('/cancel/{timeslot}', [SigRegistrationController::class, 'cancel'])->name('registration.cancel');

    // User-Roles
    Route::get("/user-roles", [UserRoleController::class, 'index'])->name("user-roles.index");
    Route::get("/user-roles/create", [UserRoleController::class, 'create'])->name("user-roles.create");
    Route::post("/user-roles", [UserRoleController::class, 'store'])->name("user-roles.store");
    Route::get("/user-roles/{userRole}/edit", [UserRoleController::class, "edit"])->name("user-roles.edit");
    Route::put("/user-roles/{userRole}", [UserRoleController::class, 'update'])->name("user-roles.update");
    Route::delete("/user-roles/{userRole}", [UserRoleController::class, 'destroy'])->name("user-roles.destroy");

    Route::get("/conbook-export", [ConbookExportController::class, 'index'])->name("conbook-export.index");
    //Ajax-Controller
    Route::post("/set-favorite", [AjaxController::class, 'setFavorite']);
});
