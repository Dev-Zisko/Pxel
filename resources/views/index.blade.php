@extends('layouts.index')

@section('content')
	@if(Auth::guest())
	    <nav class="buttons-header">
	        <ul>
	            <li><a href="{{ route('login') }}">Log in</a></li>
	            <li><a href="{{ route('register') }}">Registro</a></li>
	        </ul>
	    </nav>
    @else
	    <nav class="buttons-header">
	        <ul>
	            <li><a href="{{ route('room') }}">Entrar</a></li>
	        </ul>
	    </nav>
    @endif

    <h1 class="border-title">Pxel</h1>
    <h1 class="main-title">Pxel</h1>
@endsection