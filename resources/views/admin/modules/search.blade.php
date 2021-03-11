@extends("adminlte::page")

@section('title', "Search Modules")

@section("content_header")
    <h1>Busca de Módulos</h1>
@endsection

@section("content")
    <form id="searchform" method="post">
        @csrf
        <input id="search" type="text" name="title" class="form-control" placeholder="Digite o título para pesquisar..." /> 
    </form>

    <br />

    <div class="container row modules-widgets">

    </div>

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>

        function throttle(f, delay){
            var timer = null;
            return function(){
                var context = this, args = arguments;
                clearTimeout(timer);
                timer = window.setTimeout(function(){
                    f.apply(context, args);
                },
                delay || 500);
            };
        }

        $("#search").on("keyup", function(){
            $(".modules-widgets").html("Pesquisando...");
        });

        $("#search").keyup(throttle(function(){
            $.post("{{route('modulessearch')}}", $("#searchform").serialize(), function(result){
                data = JSON.parse(result);
                if(data.result && data.result.length > 0){
                    $(".modules-widgets").html("");
                    data.result.forEach(function(obj){
                        $(".modules-widgets").append("<div class=\"col-lg-4 col-6\"><div class=\"small-box bg-info\"><div class=\"inner\"><h3>"+ obj.title +"</h3><p>&nbsp;</p></div><div class=\"icon\"><i class=\"" + obj.icon + "\"></i></div><a href=\"" + obj.link + "\" class=\"small-box-footer\">Acessar <i class=\"fas fa-arrow-circle-right\"></i></a></div></div>");
                    });
                }else if(data.error === 9){
                    $(".modules-widgets").html("");
                }else{
                    $(".modules-widgets").html("Não foram encontrados resultados para a sua pesquisa.");
                }
            });
        }, 1000));
    </script>
@endsection