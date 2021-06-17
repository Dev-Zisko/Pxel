<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\Room;
use App\Models\User;
use App\Models\Board;
use Exception;
use Auth;

class ViewController extends Controller
{
	/* ############################################################## */
	/* VISTAS DE SALAS (ROOM) */
	/* ############################################################## */

    public function view_room()
    {
        try{
        	$rooms = Room::where('status', '!=', 'Playing')->get();
            return view('rooms.room', compact('rooms'));
        } catch(Exception $ex){
            return view('errors.alert');
        }
    }

    public function view_create_room()
    {
        try{
            return view('rooms.createroom');
        } catch(Exception $ex){
            return view('errors.alert');
        }
    }

    /* ############################################################## */
    /* VISTAS DE SALA DE ESPERA (STAY) */
    /* ############################################################## */

    public function view_stay()
    {
        try{
            $room = Room::where('id_user1', Auth::user()->id)->first();
            return view('games.stay', compact('room'));
        } catch(Exception $ex){
            return view('errors.alert');
        }
    }

    public function view_stay_ludo()
    {
        try{
            $room = Room::where('id_user1', Auth::user()->id)->first();
            return view('games.ludo.stay', compact('room'));
        } catch(Exception $ex){
            return view('errors.alert');
        }
    }

    /* ############################################################## */
    /* VISTAS DE LOS JUEGOS (GAME) */
    /* ############################################################## */

    public function view_game_ludo()
    {
        try{
            $user = User::find(Auth::user()->id);
            $room = Room::where('id_user1', $user->id)->orWhere('id_user2', $user->id)->orWhere('id_user3', $user->id)->orWhere('id_user4', $user->id)->first();
            $boards = Board::where('id_room', $room->id)->where('piececolor', '!=', '')->get();
            $boards->map(function($board){
                if($board->piececolor == "green"){
                    $turn = 1;   
                }
                else if($board->piececolor == "yellow"){
                    $turn = 2;  
                }
                else if($board->piececolor == "blue"){
                    $turn = 3;  
                }
                else if($board->piececolor == "red"){
                    $turn = 4;  
                }
                $board->turn = $turn;
            });
            return view('games.ludo.game', compact('boards', 'room', 'user'));
        } catch(Exception $ex){
            return view('errors.alert');
        }
    }

    public function view_game_ludo_api()
    {
        try{
            $user = User::find(Auth::user()->id);
            $room = Room::where('id_user1', $user->id)->orWhere('id_user2', $user->id)->orWhere('id_user3', $user->id)->orWhere('id_user4', $user->id)->first();
            $boards = Board::where('id_room', $room->id)->where('piececolor', '!=', '')->get();
            $boards->map(function($board){
                if($board->piececolor == "green"){
                    $turn = 1;   
                }
                else if($board->piececolor == "yellow"){
                    $turn = 2;  
                }
                else if($board->piececolor == "blue"){
                    $turn = 3;  
                }
                else if($board->piececolor == "red"){
                    $turn = 4;  
                }
                $board->turn = $turn;
            });
            return response()->json(['boards' => $boards, 'room' => $room, 'user' => $user]);
        } catch(Exception $ex){
            return view('errors.alert');
        }
    }
}
