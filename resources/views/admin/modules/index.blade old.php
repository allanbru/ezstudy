@extends("adminlte::page")

@section('title', 'Meus Módulos')

@section("content_header")
    <h1>Meus Módulos
        <a href="{{route('modules.create')}}" class="btn btn-sm btn-success"><i class="fas fa-fw fa-plus"></i></a>
    </h1>
@endsection

@section('content')

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                   <tr>
                        <th>Título</th>
                        <th style="width:150px">Ações</th>
                    </tr> 
                </thead>
                <tbody>
                    @foreach($modules as $module)
                        <tr>
                            <td><i class="{{$module->icon}}"></i> {{$module->title}}</td>
                            <td>
                                <a href="{{route('modules.show', ['module' => $module->id])}}" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></a>
                                <a href="{{route('modules.edit', ['module' => $module->id])}}" class="btn btn-sm btn-info"><i class="fas fa-pen"></i></a>
                                <form class="d-inline" method="post" action="{{route('modules.destroy', ['module' => $module->id])}}" onSubmit="return confirm('Tem certeza?')">
                                    @method("DELETE")
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="row">
        @foreach($modules as $module)
            <div class="col-lg-4 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{$module->title}}</h3>
                        <p>3 cards</p>
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