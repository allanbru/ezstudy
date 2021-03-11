@extends("adminlte::page")

@section('title', 'Usuários')

@section("content_header")
    <h1>Meus Usuários
        <a href="{{route('users.create')}}" class="btn btn-sm btn-success"><i class="fas fa-fw fa-plus"></i></a>
    </h1>
@endsection

@section('content')

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                   <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Ações</th>
                    </tr> 
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{$user->id}}</td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>
                                <a href="{{route('users.edit', ['user' => $user->id])}}" class="btn btn-sm btn-info"><i class="fas fa-pen"></i></a>
                                @if($loggedId !== intval($user->id))
                                    <form class="d-inline" method="post" action="{{route('users.destroy', ['user' => $user->id])}}" onSubmit="return confirm('Tem certeza?')">
                                        @method("DELETE")
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <small>{{ $users->links("pagination::bootstrap-4") }}</small>
@endsection