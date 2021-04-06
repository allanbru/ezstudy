@extends("adminlte::page")

@section('title', 'Editar Grupo')

@section("content_header")
    <h1>Editar Módulo</h1>
@endsection

@section('content')
  
    @if($errors->any())
        <div class="alert alert-danger alert-dismissable">
            <h5><i class="icon fas fa-ban"></i> Erro:</h5>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        
        <div class="card-body">

            <form action="{{route("groups.update", ['group' => $group->id])}}" method="POST" class="form-horizontal">
                @csrf
                @method('PUT')
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Nome do grupo</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nome do grupo" value="{{$group->name}}" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Descrição do grupo</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="description" class="form-control @error('description') is-invalid @enderror" style="resize:none" placeholder="Descrição do grupo">{{$group->description}}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Público</label>
                    <div class="col-sm-10">
                        <input style="width:40px" type="checkbox" name="public" class="form-control @error('public') is-invalid @enderror" @if($group->public) checked @endif>
                    </div>
                </div>
                
                <div class="form-group row">
                    <button type="submit" class="form-control btn-success btn-block">Salvar</button>
                </div>

            </form>

        </div>

    </div>   

@endsection

@section('css')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"/>
@endsection

@section('js')
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
@endsection