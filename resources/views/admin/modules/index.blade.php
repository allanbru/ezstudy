@extends("adminlte::page")

@section('title', 'Meus Módulos')

@section("content_header")
    <h1>Meus Módulos
        <a href="{{route('modules.create')}}" class="btn btn-sm btn-success"><i class="fas fa-fw fa-plus"></i></a>
    </h1>
@endsection

@section('content')

    <div class="row">
        @foreach($modules as $module)
            <div class="col-lg-4 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{$module->title}}</h3>
                        <p>&nbsp;</p>
                    </div>
                

                    <div class="icon">
                        <i class="{{$module->icon}}"></i>
                    </div>

                    <a href="{{route('modules.show', ['module' => $module->id])}}" class="small-box-footer">Acessar <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        @endforeach
    </div>
    
    
    <small>{{ $modules->links("pagination::bootstrap-4") }}</small>
@endsection