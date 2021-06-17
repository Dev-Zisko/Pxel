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

class RoomController extends Controller
{
    public function create_room(Request $request)
    {
        try{
            $room = new Room;
            $room->name = $request->name;
            $room->game = $request->game;
            $room->status = 'Waiting';
            if($request->password != "" && $request->password != null){
            	$room->password = Hash::make($request->password);
            }
            $room->ready = 0;
            $room->id_user1 = Auth::user()->id;
            $room->save();
            if($request->game == "Ludo"){
            	$boardSquares = [];
            	$boardX = [];
            	$boardY = [];
            	$x = 96;
        		$y = 253;
        		$addX = 74;
        		$addY = 8;
        		$maxBoard = 52;
        		for ($i=1; $i <= $maxBoard; $i++) {
        			array_push($boardSquares, $i);
    		        if ($i == 52){ // Casillas Normales
    		            $i = 99;
    		            $maxBoard = 105;
    		        }
    		        else if ($i == 105){ // Casa Roja
    		            $i = 199;
    		            $maxBoard = 205;
    		        }
    		        else if ($i == 205){ // Casa Azul
    		            $i = 299;
    		            $maxBoard = 305;
    		        }
    		        else if ($i == 305){ // Casa Amarilla
    		            $i = 399;
    		            $maxBoard = 405;
    		        }
    		        else if ($i == 405){ // Casa Verde
    		            $i = 999;
    		            $maxBoard = 1015;
    		        } // Spawns
        		}
        		foreach ($boardSquares as $square) {
        			if ($square == 1){ // Primera Casilla = Entrada Casa Roja
        				array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square == 2){
    		            $y = $y + 33;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square > 2 && $square <= 7){
    		            $x = $x + 33;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square == 8){
    		            $x = $x + 33;
    		            $y = $y + 33;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square > 8 && $square <= 13){
    		            $y = $y + 33;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square > 13 && $square <= 15){
    		            $x = $x + 33;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square > 15 && $square <= 20){
    		            $y = $y - 33;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square == 21){
    		            $x = $x + 33;
    		            $y = $y - 33;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square > 21 && $square <= 26){
    		            $x = $x + 33;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square > 26 && $square <= 28){
    		            $y = $y - 33;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square > 28 && $square <= 33){
    		            $x = $x - 33;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square == 34){
    		            $x = $x - 33;
    		            $y = $y - 33;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square > 34 && $square <= 39){
    		            $y = $y - 33;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square > 39 && $square <= 41){
    		            $x = $x - 33;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square > 41 && $square <= 46){
    		            $y = $y + 33;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square == 47){
    		            $x = $x - 33;
    		            $y = $y + 33;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square > 47 && $square <= 52){ // Ultima Casilla Normal
    		            $x = $x - 33;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square == 100){ // Casillas Casa Roja
    		            $x = 55 + $addX; 
    		            $y = 245 + $addY;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square > 100 && $square <= 105){
    		            $x = $x + 33;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square == 200){ // Casillas Casa Azul
    		            $x = 253 + $addX;
    		            $y = 444 + $addY;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square > 200 && $square <= 205){
    		            $y = $y - 33;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square == 300){ // Casillas Casa Amarilla
    		            $x = 451 + $addX;
    		            $y = 246 + $addY;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square > 300 && $square <= 305){
    		            $x = $x - 33;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square == 400){ // Casillas Casa Verde
    		            $x = 253 + $addX;
    		            $y = 48 + $addY;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square > 400 && $square <= 405){
    		            $y = $y + 33;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square > 2 && $square <= 7){
    		            $x = $x + 33;
    		            array_push($boardX, $x);
        				array_push($boardY, $y);
    		        }
    		        else if ($square == 1000){ // Spawns Rojo
    		            array_push($boardX, 138 + $addX);
        				array_push($boardY, 395 + $addY);
    		        }
    		        else if ($square == 1001){
    		            array_push($boardX, 104 + $addX);
        				array_push($boardY, 362 + $addY);
    		        }
    		        else if ($square == 1002){
    		            array_push($boardX, 70 + $addX);
        				array_push($boardY, 395 + $addY);
    		        }
    		        else if ($square == 1003){
    		            array_push($boardX, 104 + $addX);
        				array_push($boardY, 429 + $addY);
    		        }
    		        else if ($square == 1004){ // Spawns Azul
    		            array_push($boardX, 403 + $addX);
        				array_push($boardY, 429 + $addY);
    		        }
    		        else if ($square == 1005){
    		            array_push($boardX, 404 + $addX);
        				array_push($boardY, 362 + $addY);
    		        }
    		        else if ($square == 1006){
    		            array_push($boardX, 370 + $addX);
        				array_push($boardY, 395 + $addY);
    		        }
    		        else if ($square == 1007){
    		            array_push($boardX, 437 + $addX);
        				array_push($boardY, 395 + $addY);
    		        }
    		        else if ($square == 1008){ // Spawns Amarillo
    		            array_push($boardX, 403 + $addX);
        				array_push($boardY, 63 + $addY);
    		        }
    		        else if ($square == 1009){
    		            array_push($boardX, 404 + $addX);
        				array_push($boardY, 129 + $addY);
    		        }
    		        else if ($square == 1010){
    		            array_push($boardX, 370 + $addX);
        				array_push($boardY, 96 + $addY);
    		        }
    		        else if ($square == 1011){
    		            array_push($boardX, 437 + $addX);
        				array_push($boardY, 96 + $addY);
    		        }
    		        else if ($square == 1012){ // Spawns Verde
    		            array_push($boardX, 138 + $addX);
        				array_push($boardY, 95 + $addY);
    		        }
    		        else if ($square == 1013){
    		            array_push($boardX, 104 + $addX);
        				array_push($boardY, 129 + $addY);
    		        }
    		        else if ($square == 1014){
    		            array_push($boardX, 70 + $addX);
        				array_push($boardY, 95 + $addY);
    		        }
    		        else if ($square == 1015){
    		            array_push($boardX, 104 + $addX);
        				array_push($boardY, 63 + $addY);
    		        }
        		}
        		for ($i=0; $i < count($boardSquares); $i++) { 
        			$board = new Board;
    		        $board->box = $boardSquares[$i];
    		        $board->x = $boardX[$i];
    		        $board->y = $boardY[$i];
    		        $board->id_room = $room->id;
    		        $board->save();
        		}
            	return redirect('stay-ludo');
            }
            else{
            	return redirect('stay');
            }
        } catch(Exception $ex){
            return view('errors.alert');
        }
    }

    public function enter_room($id)
    {
        try{
            $roomId = Crypt::decrypt($id);
        	$room = Room::find($roomId);
        	if($room->game == "Ludo"){
        		return view('games.ludo.stay', compact('room'));
        	}
        	else{
        		return view('games.stay', compact('room'));
        	}
        } catch(Exception $ex){
            return view('errors.alert');
        }
    }

    public function close_room()
    {
        try{
            $room = Room::where('id_user1', Auth::user()->id)->first();
        	if($room->game == "Ludo"){
        		Board::where('id_room', $room->id)->delete();
        		Room::where('id_user1', Auth::user()->id)->delete();
        	}else{
        		Room::where('id_user1', Auth::user()->id)->delete();
        	}
            return redirect('room');
        } catch(Exception $ex){
            return view('errors.alert');
        }
    }
}
