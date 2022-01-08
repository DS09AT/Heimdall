<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\HomeController;

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

if(Config::get('app.url') !== 'http://localhost') {
    URL::forceRootUrl(Config::get('app.url'));
}

Route::get('/userselect/{user}', [LoginController::class, 'setUser'])->name('user.set');
Route::get('/userselect', [UserController::class, 'selectUser'])->name('user.select');
Route::get('/autologin/{uuid}', [LoginController::class, 'autologin'])->name('user.autologin');

Route::get('/', [ItemController::class, 'dash'])->name('dash');
Route::get('check_app_list', [ItemController::class, 'checkAppList'])->name('applist');

Route::resources([
    'items' => ItemController::class,
    'tags' => TagController::class,
    'users' => UserController::class,
]);

Route::get('tag/{slug}', [TagController::class, 'show'])->name('tags.show');
Route::get('tag/add/{tag}/{item}', [TagController::class, 'add'])->name('tags.add');
Route::get('tag/restore/{id}', [TagController::class, 'restore'])->name('tags.restore');


Route::get('items/pin/{id}', [ItemController::class, 'pin'])->name('items.pin');
Route::get('items/restore/{id}', [ItemController::class, 'restore'])->name('items.restore');
Route::get('items/unpin/{id}', [ItemController::class, 'unpin'])->name('items.unpin');
Route::get('items/pintoggle/{id}/{ajax?}/{tag?}', [ItemController::class, 'pinToggle'])->name('items.pintoggle');
Route::post('order', [ItemController::class, 'setOrder'])->name('items.order');

Route::post('appload', [ItemController::class, 'appload'])->name('appload');
Route::post('test_config', [ItemController::class, 'testConfig'])->name('test_config');
Route::get('/get_stats/{id}', [ItemController::class, 'getStats'])->name('get_stats');

Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('view/{name_view}', function ($name_view) {
    return view('SupportedApps::'.$name_view)->render();
});

Route::get('titlecolour', function (Request $request) {
    $color = $request->input('color');
    if($color) {
        return title_color($color);
    };

})->name('titlecolour');


/**
 * Settings.
 */
Route::group([
    'as'     => 'settings.',
    'prefix' => 'settings',
], function () {
    Route::get('/', [SettingsController::class, 'index'])
        ->name('index');
    Route::get('edit/{id}', [SettingsController::class, 'edit'])
        ->name('edit');
    Route::get('clear/{id}', [SettingsController::class, 'clear'])
        ->name('clear');
    Route::patch('edit/{id}', [SettingsController::class, 'update']);
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
