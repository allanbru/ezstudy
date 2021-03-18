@extends("adminlte::page")

@section('title', $tag->title)

@section("content_header")
    <div class="row">
        <div class="col-md-10">
            <h1>{{$tag->title}}</h1>
        </div>
        <div class="col-md-2">
            <a class="btn btn-info btn-block" href="{{route("modules.show", ["module" => $tag->module])}}"><i class="fas fa-arrow-left"></i> Voltar ao módulo</a>
        </div>
    </div>    
@endsection

@section("content")
    <div class="col-md-12 my-auto showcase-text">
        <p class="lead mb-0">
            {!! $tag->text !!}
        </p>
    </div>
@endsection
