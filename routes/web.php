<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Api\LassieExportEndpoint;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegSysLoginController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Ddas\ArtshowController;
use App\Http\Controllers\Ddas\DealersDenController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LostFoundItemController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Schedule\ConbookExportController;
use App\Http\Controllers\Schedule\TimetableEntryController;
use App\Http\Controllers\SetLocaleController;
use App\Http\Controllers\Sig\SigEventController;
use App\Http\Controllers\Sig\SigFavoriteController;
use App\Http\Controllers\Sig\SigFormController;
use App\Http\Controllers\Sig\SigHostController;
use App\Http\Controllers\Sig\SigLocationController;
use App\Http\Controllers\Sig\SigRegistrationController;
use App\Http\Controllers\User\SettingsController;
use App\Http\Controllers\User\ConnectTelegramController;
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

Route::get("/devlogin/{id?}", function($id=1) {
    if(App::environment("local") OR App::environment("development")) {
        Auth::loginUsingId($id);
        return redirect(RouteServiceProvider::HOME);
    }
})->name("devlogin");

// Auth
Route::get("/login", [LoginController::class, 'showLoginForm'])->name("login");
Route::post("/login", [LoginController::class, 'login']);
Route::post("/logout", [LoginController::class, 'logout'])->name("logout");
Route::get("/logout", [LoginController::class, 'redirect']);

Route::get("/oauthlogin_regsys", [RegSysLoginController::class, 'index'])->name("oauthlogin_regsys");
Route::get("/oauth_regsys", [RegSysLoginController::class, 'redirect'])->name("oauth_redirect");

// Announcements
Route::get("/announcements", [AnnouncementController::class, 'index'])->name("announcements");

// Schedule
Route::get("/schedule", [TimetableEntryController::class, 'index'])->name("schedule.listview");
Route::get("/schedule/index", [TimetableEntryController::class, 'timetableIndex'])->name("schedule.listview-index");
Route::get("/show/{entry:slug}", [TimetableEntryController::class, 'show'])->name("timetable-entry.show");

Route::get("/table", [TimetableEntryController::class, 'table'])->name("schedule.tableview");
Route::get("/calendar", [TimetableEntryController::class, 'calendar'])->name("schedule.calendarview");
Route::get("/calendar/events", [TimetableEntryController::class, 'calendarEvents'])->name("schedule.calendarview-index");
Route::get("/calendar/resources", [TimetableEntryController::class, 'calendarResources'])->name("schedule.calendarview-resources");

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

Route::group(['middleware' => "auth"], function() {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // SIG Events
    Route::resource("/sigs", SigEventController::class)
        ->only(['create', 'index', 'store']);

    // Sig application
    Route::prefix('sigs')->name('sigs.')->group(function() {
        Route::get('/create', [SigEventController::class, 'create'])->name("create");
        Route::post('/', [SigEventController::class, 'store'])->name("store");
    });

    // SIG Timeslot Registration
    Route::post('/register/{timeslot}', [SigRegistrationController::class, 'register'])->name('registration.register');
    Route::delete('/cancel/{timeslot}', [SigRegistrationController::class, 'cancel'])->name('registration.cancel');

    // Favorites
    Route::post("/favorites", [SigFavoriteController::class, 'store'])->name('favorites.store');
    Route::delete("/favorites/{entry}", [SigFavoriteController::class, 'destroy'])->name('favorites.destroy');

    // Telegram auth
    Route::get("/telegram/connect", ConnectTelegramController::class)->name('telegram.connect');

    // Dealers Den
    Route::resource('/dealers', DealersDenController::class)
         ->only(['index', 'create'])
         ->names("dealers");

    //Artshow
    Route::resource('/artshow', ArtshowController::class)
         ->parameters(['artshow' => 'artshowItem'])
         ->only(['index', 'create', 'show']);

    // Lost and found
    Route::get("/lostfound", [LostFoundItemController::class, 'index'])->name("lostfound.index");

    // SIG dynamic signup forms
    Route::prefix('form')->name('forms.')->group(function() {
        Route::get('/{form}', [SigFormController::class, 'show'])->name('show');
        Route::post('/{form}', [SigFormController::class, 'store'])->name('store');
        Route::delete('/{form}', [SigFormController::class, 'destroy'])->name('destroy');
    });

    // User Settings
    Route::get("/settings", [SettingsController::class, "edit"])->name("user-settings.edit");
    Route::patch("/settings", [SettingsController::class, "update"])->name("user-settings.update");

    // Notifications
    Route::get("/notifications", [NotificationController::class, "index"])->name("notifications.index");
    Route::patch('/notifications', [NotificationController::class, 'update'])->name('notifications.update');

    // Chats
    Route::get("/chats", [ChatController::class, "index"])->name("chats.index");

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages');
    Route::post('/messages/store', [MessageController::class, 'store'])->name('messages.store');

});
