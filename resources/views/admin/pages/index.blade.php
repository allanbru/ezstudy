@extends("adminlte::page")

@section('title', 'Páginas')

@section("content_header")
    <h1>Minhas páginas
        <a href="{{route('pages.create')}}" class="btn btn-sm btn-success"><i class="fas fa-fw fa-plus"></i></a>
    </h1>
@endsection

@section('content')

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                   <tr>
                        <th width="50">ID</th>
                        <th>Título</th>
                        <th width="150">Ações</th>
                    </tr> 
                </thead>
                <tbody>
                    @foreach($pages as $page)
                        <tr>
                            <td>{{$page->id}}</td>
                            <td>{{$page->title}}</td>
                            <td>
                                <a href="{{route('home')}}/{{$page->slug}}" target="_blank" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Visualizar"><i class="fas fa-eye"></i></a>
                                <a href="{{route('pages.edit', ['page' => $page->id])}}" class="btn btn-sm btn-info"  data-toggle="tooltip" title="Editar"><i class="fas fa-pen"></i></a>
                                <form class="d-inline" method="post" action="{{route('pages.destroy', ['page' => $page->id])}}" onSubmit="return confirm('Tem certeza?')">
                                    @method("DELETE")
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger"  data-toggle="tooltip" title="Excluir"><i class="fas fa-trash"></i></a>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <small>{{ $pages->links("pagination::bootstrap-4") }}</small>
@endsection