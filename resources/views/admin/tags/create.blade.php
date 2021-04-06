@extends("adminlte::page")

@section('title', 'Criar Tag')

@section("content_header")
    <h1>Criar Tag</h1>
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

            <form action="{{route("tags.store")}}" method="POST" class="form-horizontal">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Título</label>
                    <div class="col-sm-10">
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{old("title")}}" />
                    </div>
                </div>
        
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Texto</label>
                    <div class="col-sm-10">
                        <textarea name="text" class="form-control body-field">{{old("text")}}</textarea>
                    </div>
                </div>

                <input type="hidden" name="module" value="{{$module->id}}">
                
                <div class="form-group row">
                    <button type="submit" class="form-control btn-success btn-block">Salvar</button>
                </div>
            </form>

        </div>

    </div>   

    <script src="https://cdn.tiny.cloud/1/fum26a6nth02bmjtvd5u2b0c00lja3rysa6zno5m8tmvlcqx/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
        tinymce.init({
            selector:"textarea.body-field",
            height:300,
            menubar:false,
            plugins:['link', 'table', 'image', 'autoresize', 'lists'],
            toolbar:'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | table | link image | bullist numlist',
            content_css:[
                '{{asset('assets/css/content.css')}}'
            ],
            images_upload_url:'{{route('imageupload')}}',
            images_upload_credentials:true,
            convert_urls:false
        });
    </script>

@endsection