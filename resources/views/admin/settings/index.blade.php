@extends('adminlte::page')

@section('title', 'Configurações')

@section('content_header')
    <h1>Configurações do Site</h1>
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

    @if(session('warning'))
        <div class="alert alert-success alert-dismissable">
            <i class="icon fas fa-check"></i> {{session('warning')}}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{route('settings.save')}}" method="POST" class="form-horizontal">
                @method('PUT')
                @csrf

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Título do Site</label>
                    <div class="col-sm-10">
                        <input type="text" name="title" value="{{$settings['title']}}" class="form-control" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Subtítulo</label>
                    <div class="col-sm-10">
                        <input type="text" name="subtitle" value="{{$settings['subtitle']}}" class="form-control" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">E-mail de contato</label>
                    <div class="col-sm-10">
                        <input type="email" name="email" value="{{$settings['email']}}" class="form-control" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Facebook</label>
                    <div class="col-sm-10">
                        <input type="text" name="facebook" value="{{$settings['facebook']}}" class="form-control" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Twitter</label>
                    <div class="col-sm-10">
                        <input type="text" name="twitter" value="{{$settings['twitter']}}" class="form-control" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Instagram</label>
                    <div class="col-sm-10">
                        <input type="text" name="instagram" value="{{$settings['instagram']}}" class="form-control" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Sobre</label>
                    <div class="col-sm-10">
                        <input type="text" name="about" value="{{$settings['about']}}" class="form-control" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Termos de Uso</label>
                    <div class="col-sm-10">
                        <input type="text" name="termsofuse" value="{{$settings['termsofuse']}}" class="form-control" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Políticas de Privacidade</label>
                    <div class="col-sm-10">
                        <input type="text" name="privacypolicy" value="{{$settings['privacypolicy']}}" class="form-control" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Cor do fundo</label>
                    <div class="col-sm-10">
                        <input type="color" name="bgcolor" value="{{$settings['bgcolor']}}" class="form-control" style="width:60px" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Cor do Texto</label>
                    <div class="col-sm-10">
                        <input type="color" name="textcolor" value="{{$settings['textcolor']}}" class="form-control" style="width:60px" />
                    </div>
                </div>

                <div class="form-group row">
                    <button type="submit" class="btn btn-success btn-block">Salvar</button>
                </div>

            </form>
        </div>
    </div>
@endsection