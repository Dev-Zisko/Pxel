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

class LudoController extends Controller
{
    public function stay_ready_ludo($id)
    {
        try{
            $room = Room::find($id);
        	$ready = $room->ready + 1;
        	$idUser = Auth::user()->id;
        	Room::where('id', $id)->update(['ready' => $ready]);
        	if($room->id_user2 == null && $room->id_user1 != $idUser){
        		Room::where('id', $id)->update(['id_user2' => $idUser]);
        	}
        	else if($room->id_user3 == null && $room->id_user1 != $idUser){
        		Room::where('id', $id)->update(['id_user3' => $idUser]);
        	}
        	else if($room->id_user4 == null && $room->id_user1 != $idUser){
        		Room::where('id', $id)->update(['id_user4' => $idUser]);
        	}
        } catch(Exception $ex){
            return view('errors.alert');
        }
    }

    public function status_ready_ludo($id)
    {
        try{
            $roomValidated = Room::find($id);
        	if($roomValidated->ready == 1){
        		return response()->json(['respuesta' => '1']);
        	}
        	else if($roomValidated->ready == 2){
        		return response()->json(['respuesta' => '2']);
        	}
        	else if($roomValidated->ready == 3){
        		return response()->json(['respuesta' => '3']);
        	}
        	else if($roomValidated->ready == 4){
        		return response()->json(['respuesta' => '4']);
        	}
        } catch(Exception $ex){
            return view('errors.alert');
        }
    }

    public function start_room_ludo(Request $request)
    {
        try{
            $idRoom = $request->idRoom;
            $idUser = Auth::user()->id;
            $room = Room::find($idRoom);
            if($idUser == $room->id_user1){
            	Board::where('id_room', $idRoom)->where('box', '1012')->update(['piececolor' => 'green', 'number' => 1, 'validation' => 0]);
            	Board::where('id_room', $idRoom)->where('box', '1013')->update(['piececolor' => 'green', 'number' => 2, 'validation' => 0]);
            	Board::where('id_room', $idRoom)->where('box', '1014')->update(['piececolor' => 'green', 'number' => 3, 'validation' => 0]);
            	Board::where('id_room', $idRoom)->where('box', '1015')->update(['piececolor' => 'green', 'number' => 4, 'validation' => 0]);
            	User::where('id', $idUser)->update(['turn' => 1]);
            	Room::where('id', $idRoom)->update(['status' => 'Playing']);
            	Room::where('id', $idRoom)->update(['ready' => '1']);
                User::where('id', $idUser)->update(['dice' => 0]);
            }
            else if($idUser == $room->id_user2){
            	Board::where('id_room', $idRoom)->where('box', '1008')->update(['piececolor' => 'yellow', 'number' => 1, 'validation' => 0]);
            	Board::where('id_room', $idRoom)->where('box', '1009')->update(['piececolor' => 'yellow', 'number' => 2, 'validation' => 0]);
            	Board::where('id_room', $idRoom)->where('box', '1010')->update(['piececolor' => 'yellow', 'number' => 3, 'validation' => 0]);
            	Board::where('id_room', $idRoom)->where('box', '1011')->update(['piececolor' => 'yellow', 'number' => 4, 'validation' => 0]);
            	User::where('id', $idUser)->update(['turn' => 2]);
                User::where('id', $idUser)->update(['dice' => 0]);
            }
            else if($idUser == $room->id_user3){
            	Board::where('id_room', $idRoom)->where('box', '1004')->update(['piececolor' => 'blue', 'number' => 1, 'validation' => 0]);
            	Board::where('id_room', $idRoom)->where('box', '1005')->update(['piececolor' => 'blue', 'number' => 2, 'validation' => 0]);
            	Board::where('id_room', $idRoom)->where('box', '1006')->update(['piececolor' => 'blue', 'number' => 3, 'validation' => 0]);
            	Board::where('id_room', $idRoom)->where('box', '1007')->update(['piececolor' => 'blue', 'number' => 4, 'validation' => 0]);
            	User::where('id', $idUser)->update(['turn' => 3]);
                User::where('id', $idUser)->update(['dice' => 0]);
            }
            else if($idUser == $room->id_user4){
            	Board::where('id_room', $idRoom)->where('box', '1000')->update(['piececolor' => 'red', 'number' => 1, 'validation' => 0]);
            	Board::where('id_room', $idRoom)->where('box', '1001')->update(['piececolor' => 'red', 'number' => 2, 'validation' => 0]);
            	Board::where('id_room', $idRoom)->where('box', '1002')->update(['piececolor' => 'red', 'number' => 3, 'validation' => 0]);
            	Board::where('id_room', $idRoom)->where('box', '1003')->update(['piececolor' => 'red', 'number' => 4, 'validation' => 0]);
            	User::where('id', $idUser)->update(['turn' => 4]);
                User::where('id', $idUser)->update(['dice' => 0]);
            }
            $boards = Board::where('id_room', $idRoom)->where('piececolor', '!=', '')->get();
            return redirect('game-ludo');
        } catch(Exception $ex){
            return view('errors.alert');
        }
    }

    public function move_piece($id, $idPiece, $numberDice)
    {
        try{
            $roomValidated = Room::find($id);
            $idUser = Auth::user()->id;
            User::where('id', $idUser)->update(['dice' => $numberDice]);
            $user = User::find($idUser);
            $piece = Board::find($idPiece);
            $count = 0;
            // Verificando si la pieza esta en el spawn
            $pieceSpawn = piece_in_spawn($piece);
            // Si esta en el spawn
            if ($pieceSpawn) {
            	if($user->dice == 1 || $user->dice == 6){
                    if($user->turn == 1){
                        $moveFinal = 41 + $user->dice; 
                    }
                    else if($user->turn == 2){
                        $moveFinal = 28 + $user->dice;
                    }
                    else if($user->turn == 3){
                        $moveFinal = 15 + $user->dice;
                    }
                    else if($user->turn == 4){
                        $moveFinal = 2 + $user->dice;
                    }
                    piece_on_piece($piece, $user, $moveFinal);
                    pass_turn($id);
                    return response()->json(['respuesta' => true]);
                }
                else{
                    return response()->json(['respuesta' => 'Mueva otra ficha, solo puede sacar con 1 o 6. En caso de que no pueda mover nada, por favor pase el turno.']);
                }
            }
            // Verificando si la pieza esta en home o pasillo de ganar
            $pieceHome = piece_in_home($piece);
            if ($pieceHome) {

            }
            return response()->json(['respuesta' => true]);
        } catch(Exception $ex){
            return view('errors.alert');
        }
    }

    public function piece_in_spawn ($piece)
    {
    	try {
    		if($piece->box >= 1000){
                return true;
            }
            else{
            	return false;
            }
    	} catch(Exception $ex){
            return view('errors.alert');
        }
    }

    public function piece_in_board($piece, $user)
    {
    	try {

    	} catch(Exception $ex){
            return view('errors.alert');
        }
    }

    public function piece_in_home($piece, $user)
    {
    	try {
    		if($piece->box >= 100){
                return true;
            }
            else{
            	return false;
            }
    	} catch(Exception $ex){
            return view('errors.alert');
        }
    }

    public function piece_on_piece($piece, $user, $moveFinal)
    {
    	try {
    		$idRoom = $piece->id_room;
    		$piecePosition = Board::where('id_room', $idRoom)->where('box', $moveFinal)->first();
    		// Si hay una pieza en la casilla donde se va a mover la nueva
    		if ($piecePosition->piececolor != null) {
    			// Si las piezas son del mismo color, se montan
    			if ($piecePosition->piececolor == $piece->piececolor) {
    				// Borramos ambas piezas para proceder a colocarla montada en la nueva casilla
    				Board::where('id', $piecePosition->id)->update(['piececolor' => null, 'number' => null, 'validation' => null, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
    				Board::where('id', $piece->id)->update(['piececolor' => null, 'number' => null, 'validation' => null, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
    				// Dependiendo del numero de pieza se le asigna a la otra, para saber que esta montada allí
                    if($piecePosition->number == 1){
                        Board::where('id_room', $idRoom)->where('box', $moveFinal)->update(['piececolor' => $piece->piececolor, 'number' => $piece->number, 'validation' => $piece->validation, 'uno' => '1']);
                    }
                    else if($piecePosition->number == 2){
                        Board::where('id_room', $idRoom)->where('box', $moveFinal)->update(['piececolor' => $piece->piececolor, 'number' => $piece->number, 'validation' => $piece->validation, 'dos' => '2']);
                    }
                    else if($piecePosition->number == 3){
                        Board::where('id_room', $idRoom)->where('box', $moveFinal)->update(['piececolor' => $piece->piececolor, 'number' => $piece->number, 'validation' => $piece->validation, 'tres' => '3']);
                    }
                    else if($piecePosition->number == 4){
                        Board::where('id_room', $idRoom)->where('box', $moveFinal)->update(['piececolor' => $piece->piececolor, 'number' => $piece->number, 'validation' => $piece->validation, 'cuatro' => '4']);
                    }
    			}
    			// Si las piezas son de distintos colores, se comen
    			else {
    				// Borramos la pieza donde estaba y la colocamos en la nueva casilla
    				Board::where('id', $piecePosition->id)->update(['piececolor' => null, 'number' => null, 'validation' => null, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
    				Board::where('id_room', $idRoom)->where('box', $moveFinal)->update(['piececolor' => $piece->piececolor, 'number' => $piece->number, 'validation' => $piece->validation, 'uno' => $piece->uno, 'dos' => $piece->dos, 'tres' => $piece->tres, 'cuatro' => $piece->cuatro]);
    				// Colocamos la pieza comida en el spawn que le corresponde
    				eat_piece($piecePosition, $idRoom);
    			}
    		}
    		// Si no hay pieza ya en la casilla donde se va a mover la nueva
    		else {
    			// Borramos la pieza donde estaba y la colocamos en la nueva casilla
    			Board::where('id', $piece->id)->update(['piececolor' => null, 'number' => null, 'validation' => null, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);            
                Board::where('id_room', $idRoom)->where('box', $moveFinal)->update(['piececolor' => $piece->piececolor, 'number' => $piece->number, 'validation' => $piece->validation, 'uno' => $piece->uno, 'dos' => $piece->dos, 'tres' => $piece->tres, 'cuatro' => $piece->cuatro]);
    		}
    	} catch(Exception $ex){
            return view('errors.alert');
        }
    }

    public function eat_piece($piecePosition, $idRoom) {
    	try {
    		if($piecePosition->number == 1 && $piecePosition->piececolor == "green"){
                Board::where('id_room', $idRoom)->where('box', '1012')->update(['piececolor' => 'green', 'number' => 1, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                if($piecePosition->dos != null){
                    Board::where('id_room', $idRoom)->where('box', '1013')->update(['piececolor' => 'green', 'number' => 2, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->tres != null){
                    Board::where('id_room', $idRoom)->where('box', '1014')->update(['piececolor' => 'green', 'number' => 3, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->cuatro != null){
                    Board::where('id_room', $idRoom)->where('box', '1015')->update(['piececolor' => 'green', 'number' => 4, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
            }
            else if($piecePosition->number == 2 && $piecePosition->piececolor == "green"){
                Board::where('id_room', $idRoom)->where('box', '1013')->update(['piececolor' => 'green', 'number' => 2, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                if($piecePosition->uno != null){
                    Board::where('id_room', $idRoom)->where('box', '1012')->update(['piececolor' => 'green', 'number' => 1, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->tres != null){
                    Board::where('id_room', $idRoom)->where('box', '1014')->update(['piececolor' => 'green', 'number' => 3, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->cuatro != null){
                    Board::where('id_room', $idRoom)->where('box', '1015')->update(['piececolor' => 'green', 'number' => 4, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
            }
            else if($piecePosition->number == 3 && $piecePosition->piececolor == "green"){
                Board::where('id_room', $idRoom)->where('box', '1014')->update(['piececolor' => 'green', 'number' => 3, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                if($piecePosition->uno != null){
                    Board::where('id_room', $idRoom)->where('box', '1012')->update(['piececolor' => 'green', 'number' => 1, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->dos != null){
                    Board::where('id_room', $idRoom)->where('box', '1013')->update(['piececolor' => 'green', 'number' => 2, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->cuatro != null){
                    Board::where('id_room', $idRoom)->where('box', '1015')->update(['piececolor' => 'green', 'number' => 4, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
            }
            else if($piecePosition->number == 4 && $piecePosition->piececolor == "green"){
                Board::where('id_room', $idRoom)->where('box', '1015')->update(['piececolor' => 'green', 'number' => 4, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                if($piecePosition->uno != null){
                    Board::where('id_room', $idRoom)->where('box', '1012')->update(['piececolor' => 'green', 'number' => 1, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->dos != null){
                    Board::where('id_room', $idRoom)->where('box', '1013')->update(['piececolor' => 'green', 'number' => 2, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->tres != null){
                    Board::where('id_room', $idRoom)->where('box', '1014')->update(['piececolor' => 'green', 'number' => 3, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
            }
            else if($piecePosition->number == 1 && $piecePosition->piececolor == "yellow"){
                Board::where('id_room', $idRoom)->where('box', '1008')->update(['piececolor' => 'yellow', 'number' => 1, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                if($piecePosition->dos != null){
                    Board::where('id_room', $idRoom)->where('box', '1009')->update(['piececolor' => 'yellow', 'number' => 2, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->tres != null){
                    Board::where('id_room', $idRoom)->where('box', '1010')->update(['piececolor' => 'yellow', 'number' => 3, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->cuatro != null){
                    Board::where('id_room', $idRoom)->where('box', '1011')->update(['piececolor' => 'yellow', 'number' => 4, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
            }
            else if($piecePosition->number == 2 && $piecePosition->piececolor == "yellow"){
                Board::where('id_room', $idRoom)->where('box', '1009')->update(['piececolor' => 'yellow', 'number' => 2, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                if($piecePosition->uno != null){
                    Board::where('id_room', $idRoom)->where('box', '1008')->update(['piececolor' => 'yellow', 'number' => 1, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->tres != null){
                    Board::where('id_room', $idRoom)->where('box', '1010')->update(['piececolor' => 'yellow', 'number' => 3, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->cuatro != null){
                    Board::where('id_room', $idRoom)->where('box', '1011')->update(['piececolor' => 'yellow', 'number' => 4, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
            }
            else if($piecePosition->number == 3 && $piecePosition->piececolor == "yellow"){
                Board::where('id_room', $idRoom)->where('box', '1010')->update(['piececolor' => 'yellow', 'number' => 3, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                if($piecePosition->uno != null){
                    Board::where('id_room', $idRoom)->where('box', '1008')->update(['piececolor' => 'yellow', 'number' => 1, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->dos != null){
                    Board::where('id_room', $idRoom)->where('box', '1009')->update(['piececolor' => 'yellow', 'number' => 2, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->cuatro != null){
                    Board::where('id_room', $idRoom)->where('box', '1011')->update(['piececolor' => 'yellow', 'number' => 4, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
            }
            else if($piecePosition->number == 4 && $piecePosition->piececolor == "yellow"){
                Board::where('id_room', $idRoom)->where('box', '1011')->update(['piececolor' => 'yellow', 'number' => 4, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                if($piecePosition->uno != null){
                    Board::where('id_room', $idRoom)->where('box', '1008')->update(['piececolor' => 'yellow', 'number' => 1, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->dos != null){
                    Board::where('id_room', $idRoom)->where('box', '1009')->update(['piececolor' => 'yellow', 'number' => 2, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->tres != null){
                    Board::where('id_room', $idRoom)->where('box', '1010')->update(['piececolor' => 'yellow', 'number' => 3, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
            }
            else if($piecePosition->number == 1 && $piecePosition->piececolor == "blue"){
                Board::where('id_room', $idRoom)->where('box', '1004')->update(['piececolor' => 'blue', 'number' => 1, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                if($piecePosition->dos != null){
                    Board::where('id_room', $idRoom)->where('box', '1005')->update(['piececolor' => 'blue', 'number' => 2, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->tres != null){
                    Board::where('id_room', $idRoom)->where('box', '1006')->update(['piececolor' => 'blue', 'number' => 3, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->cuatro != null){
                    Board::where('id_room', $idRoom)->where('box', '1007')->update(['piececolor' => 'blue', 'number' => 4, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
            }
            else if($piecePosition->number == 2 && $piecePosition->piececolor == "blue"){
                Board::where('id_room', $idRoom)->where('box', '1005')->update(['piececolor' => 'blue', 'number' => 2, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                if($piecePosition->uno != null){
                    Board::where('id_room', $idRoom)->where('box', '1004')->update(['piececolor' => 'blue', 'number' => 1, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->tres != null){
                    Board::where('id_room', $idRoom)->where('box', '1006')->update(['piececolor' => 'blue', 'number' => 3, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->cuatro != null){
                    Board::where('id_room', $idRoom)->where('box', '1007')->update(['piececolor' => 'blue', 'number' => 4, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
            }
            else if($piecePosition->number == 3 && $piecePosition->piececolor == "blue"){
                Board::where('id_room', $idRoom)->where('box', '1006')->update(['piececolor' => 'blue', 'number' => 3, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                if($piecePosition->uno != null){
                    Board::where('id_room', $idRoom)->where('box', '1004')->update(['piececolor' => 'blue', 'number' => 1, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->dos != null){
                    Board::where('id_room', $idRoom)->where('box', '1005')->update(['piececolor' => 'blue', 'number' => 2, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->cuatro != null){
                    Board::where('id_room', $idRoom)->where('box', '1007')->update(['piececolor' => 'blue', 'number' => 4, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
            }
            else if($piecePosition->number == 4 && $piecePosition->piececolor == "blue"){
                Board::where('id_room', $idRoom)->where('box', '1007')->update(['piececolor' => 'blue', 'number' => 4, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                if($piecePosition->uno != null){
                    Board::where('id_room', $idRoom)->where('box', '1004')->update(['piececolor' => 'blue', 'number' => 1, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->dos != null){
                    Board::where('id_room', $idRoom)->where('box', '1005')->update(['piececolor' => 'blue', 'number' => 2, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->tres != null){
                    Board::where('id_room', $idRoom)->where('box', '1006')->update(['piececolor' => 'blue', 'number' => 3, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
            }
            else if($piecePosition->number == 1 && $piecePosition->piececolor == "red"){
                Board::where('id_room', $idRoom)->where('box', '1000')->update(['piececolor' => 'red', 'number' => 1, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                if($piecePosition->dos != null){
                    Board::where('id_room', $idRoom)->where('box', '1001')->update(['piececolor' => 'red', 'number' => 2, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->tres != null){
                    Board::where('id_room', $idRoom)->where('box', '1002')->update(['piececolor' => 'red', 'number' => 3, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->cuatro != null){
                    Board::where('id_room', $idRoom)->where('box', '1003')->update(['piececolor' => 'red', 'number' => 4, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
            }
            else if($piecePosition->number == 2 && $piecePosition->piececolor == "red"){
                Board::where('id_room', $idRoom)->where('box', '1001')->update(['piececolor' => 'red', 'number' => 2, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                if($piecePosition->uno != null){
                    Board::where('id_room', $idRoom)->where('box', '1000')->update(['piececolor' => 'red', 'number' => 1, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->tres != null){
                    Board::where('id_room', $idRoom)->where('box', '1002')->update(['piececolor' => 'red', 'number' => 3, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->cuatro != null){
                    Board::where('id_room', $idRoom)->where('box', '1003')->update(['piececolor' => 'red', 'number' => 4, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
            }
            else if($piecePosition->number == 3 && $piecePosition->piececolor == "red"){
                Board::where('id_room', $idRoom)->where('box', '1002')->update(['piececolor' => 'red', 'number' => 3, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                if($piecePosition->uno != null){
                    Board::where('id_room', $idRoom)->where('box', '1000')->update(['piececolor' => 'red', 'number' => 1, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->dos != null){
                    Board::where('id_room', $idRoom)->where('box', '1001')->update(['piececolor' => 'red', 'number' => 2, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->cuatro != null){
                    Board::where('id_room', $idRoom)->where('box', '1003')->update(['piececolor' => 'red', 'number' => 4, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
            }
            else if($piecePosition->number == 4 && $piecePosition->piececolor == "red"){
                Board::where('id_room', $idRoom)->where('box', '1003')->update(['piececolor' => 'red', 'number' => 4, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                if($piecePosition->uno != null){
                    Board::where('id_room', $idRoom)->where('box', '1000')->update(['piececolor' => 'red', 'number' => 1, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->dos != null){
                    Board::where('id_room', $idRoom)->where('box', '1001')->update(['piececolor' => 'red', 'number' => 2, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
                if($piecePosition->tres != null){
                    Board::where('id_room', $idRoom)->where('box', '1002')->update(['piececolor' => 'red', 'number' => 3, 'validation' => 0, 'uno' => null, 'dos' => null, 'tres' => null, 'cuatro' => null]);
                }
            }
    	} catch(Exception $ex){
            return view('errors.alert');
        }
    }

    public function pass_turn($id)
    {
        try{
            $room = Room::find($id);
            $user1 = User::find($room->id_user1);
            $user2 = User::find($room->id_user2);
            $user3 = User::find($room->id_user3);
            $user4 = User::find($room->id_user4);
            if ($user1->winner == 4) {
            	$turnOne = true;
            }
            if ($user2->winner == 4) {
            	$turnTwo = true;
            }
            if ($user3->winner == 4) {
            	$turnThree = true;
            }
            if ($user4->winner == 4) {
            	$turnFour = true;
            }
            $validation = true;
            $newturn = $room->ready;
            while($validation) {
            	$newturn = $newturn + 1;
            	if ($newturn > 4) {
	                $newturn = 1;
	            }
            	if ($newturn == $user1->turn && ($user1->winner < 4 || $user1->winner == null)) {
            		$validation = false;
            	}
            	else if ($newturn == $user2->turn && ($user2->winner < 4 || $user2->winner == null)) {
            		$validation = false;
            	}
            	else if ($newturn == $user3->turn && ($user3->winner < 4 || $user3->winner == null)) {
            		$validation = false;
            	}
            	else if ($newturn == $user4->turn && ($user4->winner < 4 || $user4->winner == null)) {
            		$validation = false;
            	}
            }
            Room::where('id', $id)->update(['ready' => $newturn]);
            return response()->json(['respuesta' => true]);
        } catch(Exception $ex){
            return view('errors.alert');
        }
    }
}
