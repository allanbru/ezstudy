@extends('adminlte::page')

@section('plugins.Chartjs', true)

@section('title', 'Início')

@section('content_header')
    <h1>Início</h1>
@endsection

@section('content')
    

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="{{asset("assets/js/jquery.CalendarHeatmap.min.js")}}"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{$modules_count}}</h3>
                    <p>Módulos</p>
                </div>
            

                <div class="icon">
                    <i class="fas fa-fw fa-th-large"></i>
                </div>

                <a href="{{route('modules.index')}}" class="small-box-footer">Acessar <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{$solved_count}}</h3>
                    <p>Resolvidos essa semana</p>
                </div>
            

                <div class="icon">
                    <i class="fas fa-fw fa-check"></i>
                </div>

                <a href="{{route('modules.index')}}" class="small-box-footer">Acessar <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{$review_count}}</h3>
                    <p>Cards para revisar</p>
                </div>
            

                <div class="icon">
                    <i class="far fa-fw fa-clock"></i>
                </div>

                <a href="{{route('modules.index')}}" class="small-box-footer">Acessar <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Histórico de Cards</h3>
                </div>
                <div class="card-body">
                    <div id="heatmap"></div>
                    
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Progresso diário</h3>
                </div>
                <div class="card-body">
                    <canvas id="dailyGraph"></canvas>           
                </div>
            </div>
        </div>
        <div class="col-md-6" style="display:none">
            <div class="card bg-secondary">
                <div class="card-header">
                    <h3 class="card-title">Últimas notícias</h3>
                </div>
                <div class="card-body">
                    <?php 
                        $front_menu = array_reverse($front_menu); 
                        $front_menu = array_slice($front_menu, 0, 5);
                    ?>
                    @foreach($front_menu as $slug => $title)
                        <div class="alert alert-default">
                            <a href="{{route("home")}}/{{$slug}}">{{$title}}</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function(){
            let ctx = document.getElementById("dailyGraph").getContext('2d');
            window.dailyGraph = new Chart(ctx, {
                type:'line',
                data:{
                    datasets: [{
                        data: {{$graphValues}},
                    }],
                    labels:{!! $graphLabels !!}
                },
                options:{
                    responsive:true,
                    legend:{
                        display:false
                    }
                }
            });
        }

        var events = {!! $cardsSolved !!};
        var data = [];
        for (var i = 0; i < events.length; i++ ) {
            
            var d = events[i]["y"]
            + "-"
            + events[i]["m"].toLocaleString('en-US', {
                minimumIntegerDigits: 2,
                useGrouping: false
            }) 
            + "-" 
            + events[i]["d"].toLocaleString('en-US', {
                minimumIntegerDigits: 2,
                useGrouping: false
            }); 
			data.push({
				count: parseInt( events[i]["c"] ),
				date: d
			});
		}
        $("#heatmap").CalendarHeatmap(data, {
			title: null,
            months: 6,
            labels: {
                days: true,
                custom: {
                    weekDayLabels: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
                    monthLabels: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"]
                }
            },
            legend: {
                show: false
            },
            tooltips: {
                show: true,
                options: {

                }
            }
		});

    </script>

@endsection

@section("css")
    <link rel="stylesheet" type="text/css" href="{{asset("assets/css/jquery.CalendarHeatmap.min.css")}}">
@endsection

