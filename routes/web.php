<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\LudoController;

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

Route::get('/', function () {
    return view('index');
});

Route::get('/index', function () {
    return view('index');
})->name('index');

Auth::routes();

Route::group(['middleware'=>'auth'], function(){

	Route::get('/room', [ViewController::class, 'view_room'])->name('room');

	Route::get('/create-room', [ViewController::class, 'view_create_room'])->name('create-room');

	Route::post('/create-room', [RoomController::class, 'create_room'])->name('create-room');

	Route::get('/stay', [ViewController::class, 'view_stay'])->name('stay');

	Route::get('/close-room', [RoomController::class, 'close_room'])->name('close-room');

	Route::get('/enter-room/{id}', [RoomController::class, 'enter_room'])->name('enter-room');

	Route::get('/stay-ready/{id}', [RoomController::class, 'stay_ready'])->name('stay-ready');

	Route::get('/status-ready/{id}', [RoomController::class, 'status_ready'])->name('status-ready');

	Route::post('/stay', [RoomController::class, 'start_room'])->name('stay');

	Route::get('/game', [ViewController::class, 'view_game'])->name('game');

	Route::get('/game-api', [ViewController::class, 'view_game_api'])->name('game-api');

	/* LUDO */

	Route::get('/stay-ludo', [ViewController::class, 'view_stay_ludo'])->name('stay-ludo');

	Route::get('/stay-ready-ludo/{id}', [LudoController::class, 'stay_ready_ludo'])->name('stay-ready-ludo');

	Route::get('/status-ready-ludo/{id}', [LudoController::class, 'status_ready_ludo'])->name('status-ready-ludo');

	Route::post('/stay-ludo', [LudoController::class, 'start_room_ludo'])->name('stay-ludo');

	Route::get('/game-ludo', [ViewController::class, 'view_game_ludo'])->name('game-ludo');

	Route::get('/game-ludo-api', [ViewController::class, 'view_game_ludo_api'])->name('game-ludo-api');

	Route::get('/roll-dice/{id}', [LudoController::class, 'roll_dice'])->name('roll-dice');

	Route::get('/move-piece/{id}/{piece}/{dice}', [LudoController::class, 'move_piece'])->name('move-piece');

	Route::get('/pass-turn/{id}', [LudoController::class, 'pass_turn'])->name('pass-turn');

});
