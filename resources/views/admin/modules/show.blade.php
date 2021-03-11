@extends("adminlte::page")

@section('title', $module->title)

@section("content_header")
    <div class="row">
        <div class="col-md-10">
            <h1>
                Módulo: {{$module->title}}
                @if($is_owner)
                    <a href="{{route('modules.edit', ['module' => $module->id])}}" class="btn btn-sm btn-info"><i class="fas fa-pen"></i></a>
                    <form class="d-inline" method="post" action="{{route('modules.destroy', ['module' => $module->id])}}" onSubmit="return confirm('Tem certeza que deseja apagar {{$module->title}}?')">
                        @method("DELETE")
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                    </form>
                @elseif($is_fav)
                    <a id="unfav" onclick="fav()" class="text-warning" title="Remover dos favoritos"><i class="fas fa-star"></i></a>
                    <a id="fav" onclick="fav()" class="text-warning" title="Adicionar aos favoritos" style="display:none"><i class="far fa-star"></i></a>
                @else
                    <a id="fav" onclick="fav()" class="text-warning" title="Adicionar aos favoritos"><i class="far fa-star"></i></a>
                    <a id="unfav" onclick="fav()" class="text-warning" title="Remover dos favoritos" style="display:none"><i class="fas fa-star"></i></a>
                @endif
                <a id="rating-container" class="float-right" @if(!$elo_user) style="display:none" @endif><i class="fas fa-chart-line" title="Rating"></i> <span id="rating">{{$elo_user}}</span></a>
                
            </h1>
        </div>

        <div class="col-md-2">
            <a href="{{route('modules.practice', ['module' => $module->id])}}" class="btn btn-success btn-block text-white"><i class="fas fa-play"></i> Praticar</a>
        </div>
        
    </div>
    
@endsection

@section('content')
    <div class="row" id="cards">

        @if($is_owner)
            <div class="col-md-3 my-4" data-toggle="modal" data-target="#create">
                <div class="flip-card">
                    <div class="flip-card-inner">
                        <div class="flip-card-front bg-success">
                            <h1><i class="fas fa-plus"></i></h1>
                        </div>
                        <div class="flip-card-back bg-success">
                            <h1>Adicionar novo card</h1>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @foreach($cards as $card)
            <div id="card-{{$card->id}}" class="col-md-3 my-4">
                <div class="flip-card">
                    <div class="flip-card-inner">
                        <div class="flip-card-front">
                            @if($card->type === 2)
                                <img src="{{$card->bgimg}}" style="width:100%; height:100%" />
                            @elseif($card->type === 3)
                                <?php
                                    $card_text = preg_replace('/(\[\[)(.+?)(\]\])/', "<kbd>[...]</kbd>", strip_tags($card->front)); 
                                ?>
                                <h1>{!! $card_text !!}</h1>
                            @else
                                <h1>{{$card->front}}</h1>
                            @endif
                        </div>
                        <div class="flip-card-back">
                            <h1>{{$card->back}}</h1>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div id="exercise" class="modal modal-fade" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Exercício</h3>
                    <h3><button class="close" data-dismiss="modal">&times;</h3>
                </div>

                <div class="modal-body">
                    <center>

                        <div id="card_ex" class="card my-0" style="width:100%; background-color: dodgerblue; color: white">

                            <div class="card-body my-0">
                                <h3 id="frente" style="width:100%; height:100%">Frente da carta</h3>
                                <h3 id="verso" style="width:100%; height:100%; display:none">Verso da carta</h3>
                            </div>
                            
                        </div>

                        <div id="tudofeito" class="alert alert-success" style="display: none">
                            <i class="fas fa-fw fa-check"></i> Todos os cards já foram revisados!
                        </div>

                    </center>
                    
                </div>

                <form id="card_form" method="POST" action="{{route('cardsolve')}}">
                    @csrf
                    <input id="card_id" type="hidden" name="id" value="" />
                    <input id="card_result" type="hidden" name="result" value="" />
                </form>

                <div class="modal-footer">
                    <div id="virar" class="col-md-12">
                        <button onclick="virarCarta()" class="btn btn-info btn-block">Virar carta</button>
                    </div>

                    <div id="avaliar"  class="col-md-12" style="display:none">
                        <div class="btn-group d-flex" role="group" style="width:100%">
                            <button name="result" type="button" class="btn btn-success w-100" onclick="avaliar(3)">Fácil</button>
                            <button name="result" type="button" class="btn btn-warning w-100" onclick="avaliar(2)">Médio</button>
                            <button name="result" type="button" class="btn btn-danger w-100" onclick="avaliar(1)">Difícil</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($is_owner)

        <div id="create" class="modal modal-fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Criar card</h3>
                        <h3><button class="close" data-dismiss="modal">&times;</h3>
                    </div>

                    <div class="modal-body">
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a class="nav-link active" href="#card-default" data-toggle="tab">Padrão</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#card-img" data-toggle="tab">Imagem</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#card-txt" data-toggle="tab">Texto</a>
                            </li>
                        </ul>

                        <hr />

                        <div class="tab-content">
                            <div class="tab-pane active" id="card-default">
                                <form id="form-default" method="post" action="{{route("cards.store")}}">
                                    @csrf
                                    <div class="form-group">
                                        <label>Texto da frente</label>
                                        <input type="text" class="form-control" name="front" placeholder="Texto da frente..." />
                                    </div>

                                    <div class="form-group">
                                        <label>Texto do verso</label>
                                        <input type="text" class="form-control" name="back" placeholder="Texto do verso..." />
                                    </div>

                                    <input type="hidden" name="module" value="{{$module->id}}" />

                                    <button id="submit-default" type="button" class="btn btn-success btn-block">Inserir</button>
                                </form>
                            </div>

                            <div class="tab-pane" id="card-img">

                                <form id="form-img" method="post" action="{{route("cards.imgstore")}}" enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-group">
                                        <label>Nome da Carta</label>
                                        <input type="text" class="form-control" name="front" placeholder="Nome da carta" />
                                    </div>

                                    <div class="form-group">
                                        <label>Imagem de fundo</label>
                                        <div class="custom-file">
                                            <input type="file" name="file" accept="image/*" class="custom-file-input" id="inputImgFundo" />
                                            <label class="custom-file-label">Escolha uma imagem...</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Texto do verso</label>
                                        <input type="text" class="form-control" name="back" placeholder="Texto do verso..." />
                                    </div>
                                    
                                    <input type="hidden" name="module" value="{{$module->id}}" />

                                    <button id="submit-img" type="button" class="btn btn-success btn-block">Inserir</button>
                                </form>

                            </div>
                        
                            <div class="tab-pane" id="card-txt">

                                <form id="form-txt" method="post" action="{{route("cards.txtstore")}}">
                                    @csrf
        
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> Coloque o texto oculto entre [[dois colchetes]]
                                    </div>

                                    <div class="form-group">
                                        <label>Texto da Carta</label>
                                        <textarea class="form-control" name="text" placeholder="Texto da carta..."></textarea>
                                    </div>
                                    
                                    <input type="hidden" name="module" value="{{$module->id}}" />
    
                                    <button id="submit-txt" type="button" class="btn btn-success btn-block">Inserir</button>
                                </form>
    
                            </div>

                        </div>    
                        
                    </div>

                    </div>

                </div>
            </div>
        </div>

    @endif

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>

        var pvar = 0;

        function virarCarta(){
            pvar++;
            if(pvar%2 == 1){
                $("#frente").fadeOut("slow").promise().done(function(){
                    $("#verso").fadeIn("fast");
                    if(pvar === 1) $("#avaliar").fadeIn("slow");
                });
                
                return;
                
            } 

            $("#verso").fadeOut("slow").promise().done(function(){
                $("#frente").fadeIn("fast");
            });  
        }

        function pegarCarta(){
            $.get("{{route('queuenext', ['module' => $module->id])}}", function(result){
                data = JSON.parse(result);
                if(data !== 0){
                    pvar = 0;
                    $("#card_id").val(data[0]['id']);
                    $("#verso").html(data[0]['back']).hide();
                    $("#avaliar").hide();
                    if(data[0]['bgimg'] != null){
                        $("#frente").html("<img src=\""+ data[0]['bgimg'] + "\" style=\"width:100%;height:100%\" />").fadeIn("slow");
                    }else{
                        $("#frente").html(data[0]['front']).fadeIn("slow");
                    }
                    
                }else{
                    $("#card_ex").hide();
                    $("#avaliar").hide();
                    $("#virar").hide();
                    $("#tudofeito").fadeIn("slow");
                }
            });
        }

        function avaliar(valor){
            $("#card_result").val(valor);
            $.post($("#card_form").attr("action"), $("#card_form").serialize()).done(function(result){
                pegarCarta();
                result = JSON.parse(result);
                if(result && typeof(result) == 'number' && result > 100){
                    rating = Math.round(result);
                    $("#rating-container").fadeIn("slow");
                    $("#rating").html(rating);
                }
            });
        }

        function fav(){
            $.get("{{route('togglefav', ['module' => $module->id])}}", function(result){
                var data = JSON.parse(result);
                if(data === -1 || data === 1){
                    $("#fav").toggle();
                    $("#unfav").toggle();
                }
            });
        }

        pegarCarta();
    </script>

    @if($is_owner)

        <script>
            $('#inputImgFundo').on('change',function(){
                var fileName = $(this).val();
                $(this).next('.custom-file-label').html(fileName);
            });

            $("#submit-default").on("click", function(){
                $.post($("#form-default").attr("action"),$("#form-default").serialize(), function(result){
                    var data = JSON.parse(result);
                    if(data.ERROR === 1){
                        alert(data.RESULT);
                    }else{
                        $("#cards").append("<div id=\"card-"+ data.RESULT['id'] + "\" class=\"col-md-3 my-4\"><div class=\"flip-card\"><div class=\"flip-card-inner\"><div class=\"flip-card-front\"><h1>"+data.RESULT['front'] + "</h1></div><div class=\"flip-card-back\"><h1>" + data.RESULT['back'] + "</h1></div></div></div></div>");
                        $("#form-default").trigger("reset");
                    }
                });
            });


            $("#submit-img").on("click", function(){
                var url = $("#form-img").attr("action");
                var formData = new FormData(document.getElementById("form-img"));

                $.ajax({
                    url: url,
                    data: formData,
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    success:function(result){
                        var data = JSON.parse(result);
                        console.log(data);
                        if(data.ERROR === 1){
                            alert(data.RESULT);
                        }else{
                            $("#cards").append("<div id=\"card-"+ data.RESULT['id'] + "\" class=\"col-md-3 my-4\"><div class=\"flip-card\"><div class=\"flip-card-inner\"><div class=\"flip-card-front\"><img src=\"" + data.RESULT['bgimg'] + "\" style=\"width:100%; height:100%\" /></div><div class=\"flip-card-back\"><h1>" + data.RESULT['back'] + "</h1></div></div></div></div>");
                            $("#form-img").trigger("reset");
                        }
                    },
                });
            });

            $("#submit-txt").on("click", function(){
                $.post($("#form-txt").attr("action"),$("#form-txt").serialize(), function(result){
                    var data = JSON.parse(result);
                    if(data.ERROR === 1){
                        alert(data.RESULT);
                    }else{
                        card_front = data.RESULT["front"].replace(/(\[\[)(.+?)(\]\])/g, t3replacer);
                        $("#cards").append("<div id=\"card-"+ data.RESULT['id'] + "\" class=\"col-md-3 my-4\"><div class=\"flip-card\"><div class=\"flip-card-inner\"><div class=\"flip-card-front\"><h1>"+ card_front + "</h1></div><div class=\"flip-card-back\"><h1>" + data.RESULT['back'] + "</h1></div></div></div></div>");
                        $("#form-txt").trigger("reset");
                    }
                });
            });

            function t3replacer(match, p1, p2, p3, offset, string){
                return p1.slice(0,-2) + "<kbd>[...]</kbd>" + p3.slice(2,0);
            }

        </script>

    @endif

    <style>
        /* The flip card container - set the width and height to whatever you want. We have added the border property to demonstrate that the flip itself goes out of the box on hover (remove perspective if you don't want the 3D effect */
        .flip-card {
        background-color: transparent;
        width: 300px;
        height: 200px;
        border: 1px solid #f1f1f1;
        perspective: 1000px; /* Remove this if you don't want the 3D effect */
        }

        /* This container is needed to position the front and back side */
        .flip-card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        text-align: center;
        transition: transform 0.8s;
        transform-style: preserve-3d;
        }

        /* Do an horizontal flip when you move the mouse over the flip box container */
        .flip-card:hover .flip-card-inner {
        transform: rotateY(180deg);
        }

        /* Position the front and back side */
        .flip-card-front, .flip-card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        -webkit-backface-visibility: hidden; /* Safari */
        backface-visibility: hidden;
        }

        /* Style the front side (fallback if image is missing) */
        .flip-card-front {
        background-color: dodgerblue;
        color: white;
        }

        /* Style the back side */
        .flip-card-back {
        background-color: #28a745;
        color: white;
        transform: rotateY(180deg);
        }
    </style>

@endsection