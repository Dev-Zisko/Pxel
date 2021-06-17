@extends('layouts.index')

@section('content')
    @if($room->id_user1 == Auth::user()->id)
    <nav class="buttons-header">
        <ul>
            <li><a href="{{ route('close-room') }}">Cerrar sala</a></li>
        </ul>
    </nav>
    @else
    <nav class="buttons-header">
        <ul>
            <li><a href="{{ route('room') }}">Salir</a></li>
        </ul>
    </nav>
    @endif
    <br>
    <h3 class="welcome-name">{{ $room->name }}</h3>
    <div class="board-ludo"></div>
    <div id="pieces-zone">
        @foreach($boards as $board)
            <div id="{{ $board->piececolor }}{{ $board->number }}" class="pawns" onclick="movePiece({{ $board->id }}, {{ $board->turn }})" style="background-color: {{ $board->piececolor }}; top: {{ $board->x }}px; left: {{ $board->y }}px; border: 2px solid #282828;"></div>
        @endforeach
    </div>
    <h2 id="turn-now" class="welcome-name">Turno actual: {{ $room->ready }}</h2>
    <h2 class="welcome-name">Tu turno es: {{ Auth::user()->turn }}</h2>
    <div id="dice" class="dice" onclick="rollDice()"></div>
    <div id="turn" class="turn" onclick="passTurn()">Pasar turno</div>
    <input id="idRoom" name="idRoom" type="hidden" value="{{ $room->id }}">
@endsection

@section('scripts')
    <script type="text/javascript">

        var boards = {!! json_encode($boards, JSON_HEX_TAG) !!};
        var room = {!! json_encode($room, JSON_HEX_TAG) !!};
        var user = {!! json_encode($user, JSON_HEX_TAG) !!};
        var numberDice = 0;

        function statusGame(){
            var route = "http://localhost:8000/game-ludo-api";
            $.get(route, function(res){
                $(res).each(function(key, value){
                    boards = value.boards;
                    room = value.room;
                    user = value.user;
                    $('#pieces-zone').empty();
                    for(board of boards){
                        $('#pieces-zone').append('<div id="'+board.piececolor+board.number+'" class="pawns" onclick="movePiece('+board.id+','+board.turn+')" style="background-color: '+board.piececolor+'; top: '+board.x+'px; left: '+board.y+'px; border: 2px solid #282828;"></div>');
                    }
                    const turnNow = document.getElementById('turn-now');
                    turnNow.innerHTML = "";
                    turnNow.innerHTML = "Turno actual: " + room['ready'];
                });
            });
        }

        setInterval('statusGame()', 3000);

        function passTurn(){
            if(room['ready'] == user['turn']){
                const input = $("#idRoom");
                const dice = document.getElementById('turn');
                var route = "http://localhost:8000/pass-turn";
                var id = $(input).val();
                $.get(route+"/"+id, function(res){
                    $(res).each(function(key, value){
                        respuesta = value.respuesta;
                        numberDice = 0;
                    });
                });
            }
            else{
                alert("No es tu turno aún, por favor espera.");
            }
        }

        function rollDice(){
            if(room['ready'] == user['turn']){
                if(numberDice == 0){
                    const dice = document.getElementById('dice');
                    numberDice = Math.floor(Math.random()*7);
                    dice.style.backgroundImage = "url(assets/images/" + numberDice + ".jpg)";
                    setTimeout('changeDice()', 3000);
                }
                else{
                    alert("Ya tiraste el dado, mueve la ficha por favor.");
                }
            }
            else{
                alert("No es tu turno aún, por favor espera.");
            }
        }

        function changeDice(){
            const dice = document.getElementById('dice');
            dice.style.backgroundImage = "url(assets/images/dado.gif)";
        }

        function movePiece(piece, usercolor){
            if(room['ready'] == user['turn'] && numberDice != 0){
                var color = 0;
                color = user['turn'];

                if(color == usercolor){
                    const input = $("#idRoom");
                    var route = "http://localhost:8000/move-piece";
                    var id = $(input).val();
                    $.get(route+"/"+id+"/"+piece+"/"+numberDice, function(res){
                        $(res).each(function(key, value){
                            respuesta = value.respuesta;
                            if(respuesta == "true" || respuesta == true){
                                numberDice = 0;
                                statusGame();
                            }
                            else{
                                alert(respuesta);
                            }
                        });
                    });
                }
                else{
                    alert("Esa pieza no es tuya, selecciona una pieza correcta.");
                }
            }
            else{
                alert("No has tirado el dado, por favor hazlo primero.");
            }
        }
    </script>
@endsection