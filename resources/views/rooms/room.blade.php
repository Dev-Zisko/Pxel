@extends('layouts.index')

@section('content')
    <nav class="buttons-header">
        <ul>
            <li><a href="{{ route('create-room') }}">Crear sala</a></li>
            <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Salir</a></li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
        </ul>
    </nav>
    <br>
    <h3 class="welcome-name">Hola de vuelta, {{ Auth::user()->name }} que te diviertas!</h3>
    <div class="container-form">
        <h1 class="title-table">Salas creadas</h1>

        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($rooms as $room)
                    <tr>
                        <td>{{ $room['name'] }}</td>
                        <td><a href="{{url('enter-room',Crypt::encrypt($room['id']))}}">Entrar</a></td>
                    </tr>
                @endforeach
                @if(count($rooms) == 0)
                    <tr>
                        <td>No hay salas creadas.</td>
                        <td></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
