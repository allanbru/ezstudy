@extends('adminlte::page')

@section('title', 'Meus Grupos')

@section('content_header')
    <div class="row">
        <div class="col-md-12">
            <h1>
                Meus Grupos
                <a href="{{route('groups.create')}}" class="btn btn-sm btn-success"><i class="fas fa-fw fa-plus"></i></a>
            </h1>
            <p>
                Com grupos, você pode compartilhar seus módulos, deixando-os acessíveis para amigos e colegas de turma! Além disso, interaja com membros e potencialize seu estudo!
            </p>
        </div>
    </div>
@endsection

@section('content')

    @foreach($groups as $group)
        <div class="card">
            <div class="card-header bg-secondary">
                <h3 class="card-title">{{$group->name}}</h3>
            </div>
            
            <div class="card-body">
                {{$group->description}}
            </div>

            <div class="card-footer">
                <a href="{{route("groups.show", ['group' => $group->grupo])}}"><i class="fas fa-arrow-right"></i> Acessar grupo</a>
            </div>
        </div>
    @endforeach
    
    <div class="row">
        <small>{{ $groups->links("pagination::bootstrap-4") }}</small>
    </div>

@endsection