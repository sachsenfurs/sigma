<?php

use App\Http\Controllers\Api\LassieExportEndpoint;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OAuthLoginController;
use App\Http\Controllers\Auth\RegSysLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LostFoundItemController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Post\TranslateController;
use App\Http\Controllers\Public\ConbookExportController;
use App\Http\Controllers\Public\ListViewController;
use App\Http\Controllers\Public\TableViewController;
use App\Http\Controllers\Public\TimeslotShowController;
use App\Http\Controllers\SetLocaleController;
use App\Http\Controllers\Sig\SigEventController;
use App\Http\Controllers\Sig\SigFormController;
use App\Http\Controllers\Sig\SigMyEventController;
use App\Http\Controllers\Sig\SigHostController;
use App\Http\Controllers\Sig\SigLocationController;
use App\Http\Controllers\Sig\SigRegistrationController;
use App\Http\Controllers\Sig\SigSignInController;
use App\Http\Controllers\Sig\SigTimeslotController;
use App\Http\Controllers\Sig\SigFavoriteController;
use App\Http\Controllers\Sig\SigReminderController;
use App\Http\Controllers\Sig\SigTimeslotReminderController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\DDAS\DealersDenController;
use App\Http\Controllers\DDAS\ArtshowController;
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

Route::get("/devlogin/{id?}", function($id=1) {
   if(App::environment("local") OR App::environment("development")) {
       Auth::loginUsingId($id);
       return redirect(\App\Providers\RouteServiceProvider::HOME);
   }
})->name("devlogin");


// Auth
Route::get("/login", [LoginController::class, 'showLoginForm'])->name("login");
Route::post("/login", [LoginController::class, 'login']);
Route::post("/logout", [LoginController::class, 'logout'])->name("logout");

Route::get("/oauthlogin", [OAuthLoginController::class, 'index'])->name("oauthlogin");
Route::get("/oauth", [OAuthLoginController::class, 'redirect']);

Route::get("/oauthlogin_regsys", [RegSysLoginController::class, 'index'])->name("oauthlogin_regsys");
Route::get("/oauth_regsys", [RegSysLoginController::class, 'redirect']);

Route::get("/schedule", [ListViewController::class, 'index'])->name("public.listview");
Route::get("/schedule/index", [ListViewController::class, 'timetableIndex'])->name("public.listview-index");
Route::get("/show/{entry}", [TimeslotShowController::class, 'index'])->name("public.timeslot-show");

Route::get("/table", [TableViewController::class, 'index'])->name("public.tableview");
Route::get("/table-old", [TableViewController::class, 'indexOld'])->name("public.tableview-old");


Route::resource("/hosts", SigHostController::class)->except('show');
Route::get("/hosts/{host:slug}", [SigHostController::class, 'show'])->name("hosts.show");

Route::resource("/locations", SigLocationController::class)->except('show');
Route::get("/locations/{location:slug}", [SigLocationController::class, 'show'])->name("locations.show");

Route::get("/lang/{locale}", [SetLocaleController::class, 'set'])->name("lang.set");


Route::get("/conbook-export", [ConbookExportController::class, 'index'])->name("conbook-export.index");
Route::get("/lassie-export", [LassieExportEndpoint::class, 'index'])->name("lassie-export.index");


Route::group(['middleware' => "auth"], function() {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // SIG Events
    Route::get("/sigs", [SigEventController::class, 'index'])->name("sigs.index");
    Route::get("/sigs/create", [SigEventController::class, 'create'])->name("sigs.create");
    Route::post("/sigs", [SigEventController::class, 'store'])->name("sigs.store");
    Route::get("/sigs/{sig}/edit", [SigEventController::class, 'edit'])->name("sigs.edit");
    Route::get("/sigs/{sig}", [SigEventController::class, 'show'])->name("sigs.show");
    Route::put("/sigs/{sig}", [SigEventController::class, 'update'])->name("sigs.update");
    Route::delete("/sigs/{sig}", [SigEventController::class, 'destroy'])->name("sigs.destroy");

    // Sig My Events
    Route::get("/my-events", [SigMyEventController::class, 'index'])->name("mysigs.index");

    // SIG Hosts
//    Route::get("/hosts", [SigHostController::class, 'index'])->name("hosts.index");
//    Route::get("/hosts/{host}", [SigHostController::class, 'show'])->name("hosts.show");
//    Route::get("/hosts/{host}/edit", [SigHostController::class, 'edit'])->name("hosts.edit");
//    Route::get("/hosts/{host}", [SigHostController::class, 'update'])->name("hosts.update");


    // SIG Locations
//    Route::get("/locations", [SigLocationController::class, 'index'])->name("locations.index");
//    Route::get("/locations/{location}", [SigLocationController::class, 'show'])->name("locations.show");

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
    Route::get("/timeslots/{timeslot}/editNotes", [SigTimeslotController::class, 'editNotes'])->name("timeslots.editNotes");
    Route::POST("/timeslots/{timeslot}/updateNotes", [SigTimeslotController::class, 'updateNotes'])->name("timeslots.updateNotes");

    // Registration
    Route::post('/register/{timeslot}', [SigRegistrationController::class, 'register'])->name('registration.register');
    Route::post('/cancel/{timeslot}', [SigRegistrationController::class, 'cancel'])->name('registration.cancel');

    // Favorites
    Route::post("/favorites", [SigFavoriteController::class, 'store'])->name('favorites.store');
    Route::delete("/favorites/{entry}", [SigFavoriteController::class, 'destroy'])->name('favorites.destroy');

    // Reminders
    Route::post("/reminders", [SigReminderController::class, 'store'])->name('reminders.store');
    Route::post("/reminders/update", [SigReminderController::class, 'update'])->name('reminders.update');
    Route::delete("/reminders/delete", [SigReminderController::class, 'delete'])->name('reminders.delete');

    // Reminders
    Route::post("/timeslotReminders", [SigTimeslotReminderController::class, 'store'])->name('timeslotReminders.store');
    Route::post("/timeslotReminders/update", [SigTimeslotReminderController::class, 'update'])->name('timeslotReminders.update');
    Route::delete("/timeslotReminders/delete", [SigTimeslotReminderController::class, 'delete'])->name('timeslotReminders.delete');

    // Telegram auth
    Route::get("/telegram/auth", [TelegramController::class, 'connect'])->name('telegram.connect');

    // SF Post
    Route::prefix("post")->name("posts.")->group(function() {
        Route::get("/", [PostController::class, 'index'])->name("index");
        Route::post("/", [PostController::class, 'store'])->name("store");
        Route::get("/create", [PostController::class, "create"])->name("create");
        Route::delete("/{post}", [PostController::class, 'destroy'])->name("destroy");

        Route::post("/translate", TranslateController::class)->name("translate");
    });

    // Dealer´s Dan
    Route::resource('/dealersden', DealersDenController::class);

    //Artshow
    Route::resource('/artshow', ArtshowController::class);

    // Sig SignIn (Sigs Anmelden)
    Route::resource('sig/signup', SigSignInController::class);

    Route::get("/lostfound", [LostFoundItemController::class, 'index'])->name("lostfound.index");

    // Artshow
    Route::get('/artshow', [ArtshowController::class, 'index'])->name('artshow.index');

    // Dealers Den
    Route::get('/dealersden', [DealersDenController::class, 'index'])->name('dealersden.index');

    // Sig Sign In
    Route::prefix('/sigs/signup')->name('sigs.signup.')->group(function() {
        Route::get('/', [SigSignInController::class, 'index'])->name('index');
        Route::post('/', [SigSignInController::class, 'store'])->name('store');
        Route::get('/create', [SigSignInController::class, 'create'])->name('create');
    });

    Route::prefix('form')->name('forms.')->group(function() {
        Route::get('/{form}', [SigFormController::class, 'show'])->name('show');
        Route::post('/{form}', [SigFormController::class, 'store'])->name('store');
        Route::delete('/{form}', [SigFormController::class, 'destroy'])->name('destroy');
    });
});
