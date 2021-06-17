@extends('layouts.index')

@section('content')
    @if(Auth::user()->id)
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
    <div class="board"></div>
    <div class="board" hidden="true"></div>
@endsection

@section('scripts')
@endsection