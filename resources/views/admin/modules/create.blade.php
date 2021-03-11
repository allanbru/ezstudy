@extends("adminlte::page")

@section('title', 'Novo Módulo')

@section("content_header")
    <h1>Novo Módulo</h1>
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

            <form action="{{route("modules.store")}}" method="POST" class="form-horizontal">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Título</label>
                    <div class="col-sm-10">
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{old('title')}}" />
                    </div>
                </div>
        
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Ícone</label>
                    <div class="col-sm-10">
                        <button type="button" name="icon" role="iconpicker" style="width:40px" class="form-control @error('icon') is-invalid @enderror" data-icon="{{old('icon')}}"></button>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Público</label>
                    <div class="col-sm-10">
                        <input type="checkbox" name="public" style="width:40px" class="form-control @error('public') is-invalid @enderror" @if(old('public') !== null) checked @endif>
                    </div>
                </div>
        
                <div class="form-group row">
                    <button type="submit" class="form-control btn-success btn-block">Criar</button>
                </div>
            </form>

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
@endsection