@extends('layouts.index')

@section('content')
    <nav class="buttons-header">
        <ul>
            <li><a href="{{ route('index') }}">Principal</a></li>
            <li><a href="{{ route('register') }}">Registro</a></li>
        </ul>
    </nav>
    <div class="container-form">
        <h1 class="title-form">Log in</h1>

        <form method="POST" action="{{ route('login') }}">
        @csrf

            <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                <div class="col-md-6">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">Contraseña</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-0">
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="button-normal">
                        Entrar
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
