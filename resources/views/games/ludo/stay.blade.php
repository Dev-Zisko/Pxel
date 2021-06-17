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
    <div class="container-form">
        <form method="POST" action="{{ route('stay-ludo') }}">
            @csrf
            <label class="container welcome-name">Listo?
                <input id="checkbox-enter" name="checkbox-enter" onChange="ready(this)" type="checkbox">
                <span class="checkmark"></span>
            </label>
            <label class="container welcome-name">Jugador 2
                <input id="checkbox-rival1" name="checkbox-rival1" type="checkbox" disabled>
                <span class="checkmark"></span>
            </label>
            <label class="container welcome-name">Jugador 3
                <input id="checkbox-rival2" name="checkbox-rival2" type="checkbox" disabled>
                <span class="checkmark"></span>
            </label>
            <label class="container welcome-name">Jugador 4
                <input id="checkbox-rival3" name="checkbox-rival3" type="checkbox" disabled>
                <span class="checkmark"></span>
            </label>

            <input id="idRoom" name="idRoom" type="hidden" value="{{ $room->id }}">

            <button id="send-enter" class="button-stay" type="submit" disabled>Espere...</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        var myCheck = false;

        function ready(e){
            const checkEnter = document.getElementById("checkbox-enter");
            const input = $("#idRoom");
            if(e.checked){
                checkEnter.setAttribute('disabled', true);
                myCheck = true;
                var route = "http://localhost:8000/stay-ready-ludo";
                var id = $(input).val();
                $.get(route+"/"+id, function(res){
                    //
                });
            }
        }

        function statusReady(){
            const input = $("#idRoom");
            const checkRival1 = document.getElementById("checkbox-rival1");
            const checkRival2 = document.getElementById("checkbox-rival2");
            const checkRival3 = document.getElementById("checkbox-rival3");
            const sendEnter = document.getElementById("send-enter");
            var route = "http://localhost:8000/status-ready-ludo";
            var id = $(input).val();
            $.get(route+"/"+id, function(res){
                $(res).each(function(key, value){
                    respuesta = value.respuesta;
                    if(respuesta == "1" && myCheck == false){
                        checkRival1.checked = true;
                    }
                    else if(respuesta == "1" && myCheck == true){
                        //
                    }
                    else if(respuesta == "2" && myCheck == false){
                        checkRival1.checked = true;
                        checkRival2.checked = true;
                    }
                    else if(respuesta == "2" && myCheck == true){
                        checkRival1.checked = true;
                    }
                    else if(respuesta == "3" && myCheck == false){
                        checkRival1.checked = true;
                        checkRival2.checked = true;
                        checkRival3.checked = true;
                    }
                    else if(respuesta == "3" && myCheck == true){
                        checkRival1.checked = true;
                        checkRival2.checked = true;
                    }
                    else if(respuesta == "4"){
                        checkRival1.checked = true;
                        checkRival2.checked = true;
                        checkRival3.checked = true;
                        sendEnter.removeAttribute('disabled');
                        sendEnter.innerHTML = "";
                        sendEnter.innerHTML = "Empezar..";
                    }
                });
            });
        }
        setInterval('statusReady()', 3000);
    </script>
@endsection