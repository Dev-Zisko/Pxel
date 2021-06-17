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
        <form method="POST" action="{{ route('stay') }}">
            @csrf
            <h2 class="title-table">Elija su clase</h2>
            <select class="select-class" id="class" name="class">
                <option value="Humano" selected>Humano</option>
                <option value="Orco" >Orco</option>
            </select>

            <label class="container welcome-name">Listo?
                <input id="checkbox-enter" name="checkbox-enter" onChange="ready(this)" type="checkbox">
                <span class="checkmark"></span>
            </label>
            <label class="container welcome-name">Rival
                <input id="checkbox-rival" name="checkbox-rival" type="checkbox" disabled>
                <span class="checkmark"></span>
            </label>

            <input id="idRoom" name="idRoom" type="hidden" value="{{ $room->id }}">

            <button id="send-enter" class="button-stay" type="submit" disabled>Espere...</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        function ready(e){
            const checkEnter = document.getElementById("checkbox-enter");
            const input = $("#idRoom");
            if(e.checked){
                checkEnter.setAttribute('disabled', true);
                var route = "http://localhost:8000/stay-ready";
                var id = $(input).val();
                $.get(route+"/"+id, function(res){
                    //
                });
            }
        }

        function statusReady(){
            const input = $("#idRoom");
            const checkRival = document.getElementById("checkbox-rival");
            const sendEnter = document.getElementById("send-enter");
            var route = "http://localhost:8000/status-ready";
            var id = $(input).val();
            $.get(route+"/"+id, function(res){
                $(res).each(function(key, value){
                    respuesta = value.respuesta;
                    if(respuesta == "true"){
                        sendEnter.removeAttribute('disabled');
                        sendEnter.innerHTML = "";
                        sendEnter.innerHTML = "Empezar..";
                        checkRival.checked = true;
                    }
                });
            });
        }

        setInterval('statusReady()', 3000);
    </script>
@endsection