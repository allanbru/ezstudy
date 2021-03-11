@extends('adminlte::page')

@section('plugins.Chartjs', true)

@section('title', 'Início')

@section('content_header')
    <h1>Início</h1>
@endsection

@section('content')

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
                    <h3 class="card-title">Progresso diário</h3>
                </div>
                <div class="card-body">
                    <canvas id="dailyGraph"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
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
    </script>

@endsection