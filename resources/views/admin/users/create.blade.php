@extends("adminlte::page")

@section('title', 'Novo Usuário')

@section("content_header")
    <h1>Novo Usuário</h1>
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

            <form action="{{route("users.store")}}" method="POST" class="form-horizontal">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Nome Completo</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{old('name')}}" />
                    </div>
                </div>
        
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">E-mail</label>
                    <div class="col-sm-10">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{old('email')}}" />
                    </div>
                </div>
        
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Senha</label>
                    <div class="col-sm-10">
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" />
                    </div>
                </div>
        
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Confirme a senha</label>
                    <div class="col-sm-10">
                        <input type="password" name="password_confirmation" class="form-control @error('password') is-invalid @enderror" />
                    </div>
                </div>
        
                <div class="form-group row">
                    <button type="submit" class="form-control btn-success btn-block">Cadastrar</button>
                </div>
            </form>

        </div>

    </div>   

@endsection