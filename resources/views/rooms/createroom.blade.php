@extends('layouts.index')

@section('content')
    <nav class="buttons-header">
        <ul>
            <li><a href="{{ route('room') }}">Volver</a></li>
            <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Salir</a></li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
        </ul>
    </nav>
    <br>
    <h3 class="welcome-name">Hola de vuelta, {{ Auth::user()->name }} que te diviertas!</h3>
    <div class="container-form">
        <h1 class="title-form">Creando sala</h1>

        <form method="POST">
            @csrf

            <h2 class="title-table">Seleccione el juego:</h2>
            <select class="select-class" id="game" name="game">
                <option value="Ludo" selected>Ludo</option>
            </select>
            <br>
            <br>

            <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">Nombre de sala</label>

                <div class="col-md-6">
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <!--
            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">Contraseña de la sala (Opcional)</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            -->
            <div class="row mb-0">
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="button-normal">
                        Crear
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
