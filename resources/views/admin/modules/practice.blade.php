@extends("adminlte::page")

@section('title', $module->title)

@section("content_header")
    <div class="row">
        <div class="col-md-10">
            <h1>
                Módulo: {{$module->title}}
                <a id="rating-container" class="float-right" @if(!$elo_user) style="display:none" @endif><i class="fas fa-chart-line" title="Rating"></i> <span id="rating">{{$elo_user}}</span></a>
                
            </h1>
        </div>

        <div class="col-md-2">
            <a href="{{route("modules.show", ["module" => $module->id])}}" class="btn btn-warning btn-block"><i class="fas fa-arrow-left"></i> Voltar</a>
        </div>
        
    </div>
    
@endsection

@section('content')

    <div class="container-fluid h-100">
        <div class="row justify-content-center h-100">

            <div class="col-md-12">

                <div id="alldone" class="alert alert-success" style="display: none">
                    <i class="fas fa-fw fa-check"></i> Todos os cards já foram revisados!
                </div>

                <div id="card-ex" class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-play"></i> Praticar</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div id="loading" class="alert alert-primary">
                            <i class="fas fa-sync-alt"></i> Carregando...
                        </div>

                        <div class="type" id="type-1">
                            <h3 id="front-1">Frente da Carta</h3>                       
                            <h3 id="back-1">Verso da Carta</h3>
                        </div>
                        
                        <div class="type" id="type-2">
                            <h3 id="front-2">Frente da Carta</h3>                       
                            <h3 id="back-2">Verso da Carta</h3>
                        </div>

                        <div class="type" id="type-3">
                            <h4 id="front-3">Texto da carta</h4>
                            <h4 id="back-3">Verso da Carta</h4>
                        </div>

                        <div class="type" id="type-4">
                            <h3 id="front-4">Frente da Carta</h3>                       
                            <h3 id="back-4">Verso da Carta</h3> 
                        </div>
                        

                    </div>

                    <form id="card-form" method="POST" action="{{route('cardsolve')}}">
                        @csrf
                        <input id="card-id" type="hidden" name="id" value="" />
                        <input id="card-result" type="hidden" name="result" value="" />
                    </form>

                    <div class="card-footer">

                        <div class="control col-md-12" id="control-1">
                            <button class="btn btn-info btn-block" onclick="flipCard1()">Virar carta</button>
                        </div>

                        <div class="control col-md-12" id="control-2">
                            <button class="btn btn-info btn-block" onclick="flipCard2()">Virar carta</button>
                        </div>

                        <div class="control col-md-12" id="control-3">
                            <button class="btn btn-info btn-block" onclick="flipCard3()">Virar carta</button>
                        </div>

                        <div class="control col-md-12" id="control-4">
                            <button class="btn btn-info btn-block" onclick="flipCard4()">Virar carta</button>
                        </div>

                        <div id="evaluate" class="col-md-12">
                            <hr />
                            <div class="btn-group d-flex" role="group">
                                <button name="result" type="button" class="btn btn-success w-100" onclick="eval(3)">Fácil</button>
                                <button name="result" type="button" class="btn btn-warning w-100" onclick="eval(2)">Médio</button>
                                <button name="result" type="button" class="btn btn-danger w-100" onclick="eval(1)">Difícil</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section("css")
    <style>
        .type{
            display:none;
            position: relative;
            padding: .75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: .25rem;
            
            color: white;
            background: dodgerblue;
            text-align: center;
        }

        #type-2 img, #back-2{
            height: 30vw;
        }

        #type-4 img, #back-4{
            height: 30vw;
        }

        .control{
            display:none;
        }

        #evaluate{
            display:none;
        }

    </style>
@endsection

@section("js")

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <script>

        var pvar = 0;

        function getCard(){
            $.get("{{route('queuenext', ['module' => $module->id])}}", function(result){
                data = JSON.parse(result);
                if(data !== 0){

                    $("#loading").hide();
                    $(".type").hide();
                    $(".control").hide();

                    pvar = 0;
                    card = data[0];
                    $("#card-id").val(card['id']);
                    if(card["type"] === 1){
                        $("#control-1").show();
                        $("#type-1").show();
                        $("#back-1").html(card['back']).hide();
                        $("#front-1").html(card['front']).fadeIn("slow");
                    }else if(card["type"] === 2){
                        $("#control-2").show();
                        $("#type-2").show();
                        $("#back-2").html(card['back']).hide();
                        $("#front-2").html("<img src=\""+ card['bgimg'] + "\" />").fadeIn("slow");
                    }else if(card["type"] === 3){
                        front = card["front"].replace(/(\[\[)(.+?)(\]\])/g, t3replacer);
                        $("#control-3").show();
                        $("#type-3").show();
                        $("#back-3").html(card['back']).hide();
                        $("#front-3").html(front).fadeIn("slow");
                    }else if(card["type"] === 4){
                        $("#control-4").show();
                        $("#type-4").show();
                        $("#back-4").html(card['back']).hide();
                        $("#front-4").html("<img src=\""+ card['bgimg'] + "\" />").fadeIn("slow");                   
                    }

                    $("#evaluate").hide();

                }else{
                    $("#card-ex").hide();
                    $("#evaluate").hide();
                    $(".control").hide();
                    $("#alldone").fadeIn("slow");
                }
            });
        }

        function t3replacer(match, p1, p2, p3, offset, string){
            return p1.slice(0,-2) + "<kbd onclick=\"t3answer(this)\" data-answer=\"" + p2 + "\">[...]</kbd>" + p3.slice(2,0);
        }

        function t3answer(obj){
            var objHtml = $(obj).html();
            $(obj).html($(obj).data("answer"));
            $(obj).data("answer", objHtml);
        }

        function eval(val){
            $("#card-result").val(val);
            $.post($("#card-form").attr("action"), $("#card-form").serialize()).done(function(result){
                getCard();
                result = JSON.parse(result);
                if(result && typeof(result) == 'number' && result > 100){
                    rating = Math.round(result);
                    $("#rating-container").fadeIn("slow");
                    $("#rating").html(rating);
                }
            });
        }

        function flipCard1(){
            pvar++;
            if(pvar%2 == 1){
                $("#front-1").fadeOut("slow").promise().done(function(){
                    $("#back-1").fadeIn("fast");
                    if(pvar === 1) $("#evaluate").fadeIn("slow");
                });
                
                return;
                
            } 

            $("#back-1").fadeOut("slow").promise().done(function(){
                $("#front-1").fadeIn("fast");
            });
        }

        function flipCard2(){
            pvar++;
            if(pvar%2 == 1){
                $("#front-2").fadeOut("slow").promise().done(function(){
                    $("#back-2").fadeIn("fast");
                    if(pvar === 1) $("#evaluate").fadeIn("slow");
                });
                
                return;
                
            } 

            $("#back-2").fadeOut("slow").promise().done(function(){
                $("#front-2").fadeIn("fast");
            });
        }

        function flipCard3(){
            pvar++;
            if(pvar%2 == 1){
                $("#front-3").fadeOut("slow").promise().done(function(){
                    $("#back-3").fadeIn("fast");
                    if(pvar === 1) $("#evaluate").fadeIn("slow");
                });
                
                return;
                
            } 

            $("#back-3").fadeOut("slow").promise().done(function(){
                $("#front-3").fadeIn("fast");
            });
        }

        function flipCard4(){
            pvar++;
            if(pvar%2 == 1){
                $("#front-4").fadeOut("slow").promise().done(function(){
                    $("#back-4").fadeIn("fast");
                    if(pvar === 1) $("#evaluate").fadeIn("slow");
                });
                
                return;
                
            } 

            $("#back-4").fadeOut("slow").promise().done(function(){
                $("#front-4").fadeIn("fast");
            });
        }

        getCard();
    </script>

@endsection