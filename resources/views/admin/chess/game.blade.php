@extends("adminlte::page")

@section('title', 'Minha Partida')

@section("content_header")
    <h1>Minha partida</h1>
@endsection

@section('content')
    <div class="row">
        FEN: {{$fen}}
    </div>
@endsection