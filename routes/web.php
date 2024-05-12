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
use App\Http\Controllers\Sig\MySigController;
use App\Http\Controllers\Sig\SigEventController;
use App\Http\Controllers\Sig\SigFormController;
use App\Http\Controllers\Sig\SigHostController;
use App\Http\Controllers\Sig\SigLocationController;
use App\Http\Controllers\Sig\SigRegistrationController;
use App\Http\Controllers\Sig\SigTimeslotController;
use App\Http\Controllers\Sig\SigFavoriteController;
use App\Http\Controllers\Sig\SigReminderController;
use App\Http\Controllers\Sig\SigTimeslotReminderController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\Ddas\DealersDenController;
use App\Http\Controllers\Ddas\ArtshowController;
use App\Providers\RouteServiceProvider;
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

// Schedule
Route::get("/schedule", [ListViewController::class, 'index'])->name("public.listview");
Route::get("/schedule/index", [ListViewController::class, 'timetableIndex'])->name("public.listview-index");
Route::get("/show/{entry}", [TimeslotShowController::class, 'index'])->name("public.timeslot-show");

Route::get("/table", [TableViewController::class, 'index'])->name("public.tableview");
Route::get("/table-old", [TableViewController::class, 'indexOld'])->name("public.tableview-old");

// Host list
Route::get("/hosts", [SigHostController::class, 'index'])->name("hosts.index");
Route::get("/hosts/{host:slug}", [SigHostController::class, 'show'])->name("hosts.show");

// Location list
Route::get("/locations", [SigLocationController::class, 'index'])->name("locations.index");
Route::get("/locations/{location:slug}", [SigLocationController::class, 'show'])->name("locations.show");

// functional routes
Route::get("/lang/{locale}", [SetLocaleController::class, 'set'])->name("lang.set");
Route::get("/conbook-export", [ConbookExportController::class, 'index'])->name("conbook-export.index");
Route::get("/lassie-export", [LassieExportEndpoint::class, 'index'])->name("lassie-export.index");

// dev login
Route::get("/devlogin/{id?}", function($id=1) {
    if(App::environment("local") OR App::environment("development")) {
        Auth::loginUsingId($id);
        return redirect(RouteServiceProvider::HOME);
    }
})->name("devlogin");

Route::group(['middleware' => "auth"], function() {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // SIG Events
    Route::resource("/sigs", SigEventController::class);

    // Sig application
    Route::prefix('sigs')->name('sigs.')->group(function() {
        Route::get('/create', [SigEventController::class, 'create'])->name("create");
        Route::post('/', [SigEventController::class, 'store'])->name("store");
    });

    // Sig My Events
    Route::get("/my-events", [MySigController::class, 'index'])->name("mysigs.index");
    Route::get("/my-events/{sig}", [MySigController::class, 'show'])->name("mysigs.show");

    // SIG Registration
    Route::post('/register/{timeslot}', [SigRegistrationController::class, 'register'])->name('registration.register');
    Route::post('/cancel/{timeslot}', [SigRegistrationController::class, 'cancel'])->name('registration.cancel');


    // SIG Reminders
    Route::post("/reminders", [SigReminderController::class, 'store'])->name('reminders.store');
    Route::post("/reminders/update", [SigReminderController::class, 'update'])->name('reminders.update');
    Route::delete("/reminders/delete", [SigReminderController::class, 'delete'])->name('reminders.delete');

    // Favorites
    Route::post("/favorites", [SigFavoriteController::class, 'store'])->name('favorites.store');
    Route::delete("/favorites/{entry}", [SigFavoriteController::class, 'destroy'])->name('favorites.destroy');

    // SIG Timeslots
    Route::get('/timeslots/{timeslot}/edit', [SigTimeslotController::class, 'edit'])->name('timeslots.edit');
    Route::post('/timeslots', [SigTimeslotController::class, 'store'])->name('timeslots.store');
    Route::post('/timeslots/{timeslot}', [SigTimeslotController::class, 'update'])->name('timeslots.update');
    Route::delete('/timeslots/{timeslot}', [SigTimeslotController::class, 'destroy'])->name('timeslots.destroy');
    Route::get("/timeslots/{timeslot}/editNotes", [SigTimeslotController::class, 'editNotes'])->name("timeslots.editNotes");
    Route::POST("/timeslots/{timeslot}/updateNotes", [SigTimeslotController::class, 'updateNotes'])->name("timeslots.updateNotes");

    // Timeslot Reminders
    Route::post("/timeslotReminders", [SigTimeslotReminderController::class, 'store'])->name('timeslotReminders.store');
    Route::post("/timeslotReminders/update", [SigTimeslotReminderController::class, 'update'])->name('timeslotReminders.update');
    Route::delete("/timeslotReminders/delete", [SigTimeslotReminderController::class, 'delete'])->name('timeslotReminders.delete');

    // Timetable
    Route::get("/timetable", [TimetableController::class, 'index'])->name("timetable.index");
    Route::post("/timetable", [TimetableController::class, 'store'])->name("timetable.store");
    Route::get("/timetable/{entry}/edit", [TimetableController::class, "edit"])->name("timetable.edit");
    Route::put("/timetable/{entry}", [TimetableController::class, 'update'])->name("timetable.update");
    Route::delete("/timetable/{entry}", [TimetableController::class, 'destroy'])->name("timetable.destroy");

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

    // Dealers Den
    Route::resource('/dealers', DealersDenController::class)->names("dealers");

    //Artshow
    Route::resource('/artshow', ArtshowController::class)->parameters(['artshow' => 'artshowItem']);

    // Lost and found
    Route::get("/lostfound", [LostFoundItemController::class, 'index'])->name("lostfound.index");

    // SIG dynamic signup forms
    Route::prefix('form')->name('forms.')->group(function() {
        Route::get('/{form}', [SigFormController::class, 'show'])->name('show');
        Route::post('/{form}', [SigFormController::class, 'store'])->name('store');
        Route::delete('/{form}', [SigFormController::class, 'destroy'])->name('destroy');
    });
});
