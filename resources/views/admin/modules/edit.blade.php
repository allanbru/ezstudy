@extends("adminlte::page")

@section('title', 'Editar Módulo')

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

            <form action="{{route("modules.update", ['module' => $module->id])}}" method="POST" class="form-horizontal">
                @csrf
                @method('PUT')
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Título</label>
                    <div class="col-sm-10">
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{$module->title}}" />
                    </div>
                </div>
        
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Ícone</label>
                    <div class="col-sm-10">
                        <button type="button" name="icon" role="iconpicker" style="width:40px" class="form-control @error('icon') is-invalid @enderror" data-icon="{{$module->icon}}"></button>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Público</label>
                    <div class="col-sm-10">
                        <input style="width:40px" type="checkbox" name="public" class="form-control @error('public') is-invalid @enderror" @if($module->public) checked @endif>
                    </div>
                </div>
                
                <div class="form-group row">
                    <button type="submit" class="form-control btn-success btn-block">Salvar</button>
                </div>

            </form>

        </div>

    </div>   

    <div id="module-cards" class="card card-primary collapsed-card">
        
        <div class="card-header">
            <h3 class="card-title">Cards nesse módulo</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">
            <table id="cards" class="table table-hover">
                <thead>
                <tr>
                        <th>Frente</th>
                        <th>Verso</th>
                        <th>Dificuldade</th>
                        <th style="width:150px">Ações</th>
                    </tr> 
                </thead>
                <tbody>
                    @foreach($cards as $card)
                        <tr id="card-{{$card->id}}">
                            <td>{{$card->front}}</td>
                            <td>{{$card->back}}</td>
                            <td>{{$card->elo}}</td>
                            <td>
                                <form id="form-delete-{{$card->id}}" class="d-inline" method="post" action="{{route('cards.destroy', ['card' => $card->id])}}" onSubmit="return confirm('Tem certeza?')">
                                    @method("DELETE")
                                    @csrf
                                    <button type="button" data-id="{{$card->id}}" class="btn btn-sm btn-danger deleteCard" onclick="deleteCard({{$card->id}})"><i class="fas fa-trash"></i></a>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    
    </div>

    <div id="module-tags" class="card card-primary collapsed-card">
        <div class="card-header">
            <h3 class="card-title">Tags nesse módulo</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">
            <table id="cards" class="table table-hover">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th style="width:150px">Ações</th>
                    </tr> 
                </thead>
                <tbody>
                    @foreach($tags as $tag)
                        <tr id="tag-{{$tag->id}}">
                            <td><a href="{{route("tags.show", ['tag' => $tag->id])}}">{{$tag->title}}</a></td>
                            <td>
                                <a href="{{route("tags.show", ['tag' => $tag->id])}}" class="btn btn-sm btn-secondary" title="Ver"><i class="fas fa-eye"></i></a>
                                <a href="{{route("tags.edit", ['tag' => $tag->id])}}" class="btn btn-sm btn-warning" title="Editar"><i class="fas fa-pen"></i></a>
                                <form id="form-delete-tag-{{$tag->id}}" class="d-inline" method="post" action="{{route('tags.destroy', ['tag' => $tag->id])}}" onSubmit="return confirm('Tem certeza?')">
                                    @method("DELETE")
                                    @csrf
                                    <button type="button" data-id="{{$tag->id}}" class="btn btn-sm btn-danger" onclick="deleteTag({{$tag->id}})" title="Deletar"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    

@endsection

@section('css')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"/>
    <link rel="stylesheet" href="{{asset('vendor/iconpicker/css/bootstrap-iconpicker.min.css')}}"/>
@endsection

@section('js')
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="{{asset('vendor/iconpicker/js/bootstrap-iconpicker.bundle.min.js')}}"></script>    
    <script>
        
        function deleteCard(id){
            if(confirm("Tem certeza?")){
                $.post($("#form-delete-" + id).attr("action"), $("#form-delete-" + id).serialize(), function(result){
                    data = JSON.parse(result);
                    if(data === 1){
                        $("#card-" + id).remove();
                    }
                });
            }
        }

        function deleteTag(id){
            if(confirm("Tem certeza?")){
                $.post($("#form-delete-tag-" + id).attr("action"), $("#form-delete-tag-" + id).serialize(), function(result){
                    data = JSON.parse(result);
                    if(data === 1){
                        $("#tag-" + id).remove();
                    }
                });
            }
        }

    </script>
@endsection