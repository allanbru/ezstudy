@extends('adminlte::page')

@section('title', $group->name)

@section('content_header')
    <div class="row">
        <div class="col-md-10">
            <h1>{{$group->name}}</h1>
            <p>{{$group->description}}</p>
        </div>

        <div class="col-md-2">
            
                @if($is_member)
                    @if($canEditGroup)
                        <a href="{{route("groups.edit", ['group' => $group->id])}}" class="btn btn-info btn-block"><i class="fas fa-pen"></i> Editar Grupo</a>
                    @else
                        <form method="post" action="{{route("groups.removeMember", ['user' => $loggedId, 'group' => $group->id])}}">
                            @csrf
                            @method("DELETE")
                            <button onclick="if(confirm('Tem certeza que deseja sair do grupo?')){this.form.submit()};" class="btn btn-danger btn-block"><i class="fas fa-sign-out-alt"></i> Deixar Grupo</button>
                        </form>
                    @endif
                @else
                    <form method="post" action="{{route("groups.joinMember", ['group' => $group->id])}}">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block"><i class="fas fa-plus"></i> Entrar no Grupo</button>
                    </form>
                @endif
            
        </div>
    </div>

@endsection

@section('content')

    

    <div class="row">
        <div class="col">
            <h4>
                Módulos neste grupo
                @if($is_member)
                    <a href="#" data-toggle="modal" data-target="#addModule" class="btn btn-sm btn-success"><i class="fas fa-fw fa-plus"></i></a>
                @endif
            </h4>

            @if(count($modules))
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
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Este grupo ainda não possui módulos.
                </div>
            @endif

            <div class="row">
                <small>{{ $modules->links("pagination::bootstrap-4") }}</small>
            </div>

        </div>

        @if($is_member)

            <div class="col-md-3">
                <div class="card card-primary card-outline card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="pill" href="#messages" role="tab">Mensagens</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#members" role="tab">Membros</a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="messages">
                                <form method="post" action="{{route("groups.writeMsg", ['group' => $group->id])}}">
                                    @csrf
                                    <input type="text" name="msg" class="form-control" placeholder="Escreva uma mensagem..." />
                                </form>

                                <hr />

                                @if(count($messages))
                                    @foreach($messages as $msg)
                                        @include('admin.groups.msg')
                                    @endforeach
                                @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> Não há mensagens para mostrar.
                                    </div>
                                @endif
                                <div class="row">
                                    <small>{{ $messages->links("pagination::bootstrap-4") }}</small>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="members">
                                <h4>
                                    Membros
                                </h4>
                
                                @if(count($members))
                                    <ul>
                                        @foreach($members as $member)
                                            <li id="mem-{{$member->id}}">
                                                @if($canAddMembers && $member->privileges === 0)
                                                    <form id="rm-{{$member->id}}" method="post" action="{{route("groups.removeMember", ['user' => $member->id, 'group' => $group->id])}}">
                                                        @csrf
                                                        @method("DELETE")
                                                    </form>
                                                    {{$member->name}}
                                                    <a onclick="removeMember({{$member->id}})" href="#" class="btn btn-danger"><i class="fas fa-minus"></i></a>
                                                    
                                                @else
                                                    {{$member->name}}
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                
                                @if($canAddMembers)
                                    <a id="add-member" href="#" class="btn btn-success btn-block"><i class="fas fa-plus"></i> Adicionar membro</a>
                                    <div id="add-member-div" style="display:none">
                                        Envie esse link para quem você deseja adicionar no grupo: <br />
                                        <kbd id="add-member-link"></kbd>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

            <div id="addModule" class="modal modal-fade" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">Adicionar Módulo</h3>
                            <h3><button class="close" data-dismiss="modal">&times;</h3>
                        </div>
                        
                        <div class="modal-body">
                            @if(count($aval_modules))
                                <table class="table">
                                    <tr>
                                        <th>Ícone</th>
                                        <th>Título</th>
                                        <th>Ação</th>
                                    </tr>
                                    @foreach($aval_modules as $module)
                                        <tr>
                                            <td style="vertical-align: middle"><i class="{{$module->icon}}"></i></td>
                                            <td style="vertical-align: middle">{{$module->title}}</td>
                                            <td style="vertical-align: middle"><a href="{{route("groups.addModule", ['module' => $module->id, 'group' => $group->id])}}" class="btn btn-success"><i class="fas fa-plus"></i></a></td>
                                        </tr>
                                    @endforeach
                                </table>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Você não tem módulos para adicionar
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
    
        @endif
    </div>
    @if($canAddMembers)

        <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script>
            $("#add-member").on("click", function(){
                $.get("{{route('groups.genlink', ['group' => $group->id])}}", function(data){
                    data = JSON.parse(data);
                    if(data.STATUS === 1 ){
                        $("#add-member-div").fadeToggle();
                        $("#add-member-link").html(data.LINK);
                    }
                });
            });

            function removeMember(id){
                if(confirm("Tem certeza?")){
                $.post($("#rm-" + id).attr("action"), $("#rm-" + id).serialize(), function(result){
                    data = JSON.parse(result);
                    if(data.STATUS === 1){
                        $("#mem-" + id).remove();
                    }
                });
            }
            }

        </script>

    @endif

@endsection