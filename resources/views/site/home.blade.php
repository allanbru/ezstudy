@extends('site.layout')

@section('title', 'EZStudy')

@section('content')

   <!-- Masthead -->
  <header class="masthead text-white text-center">
    <div class="overlay"></div>
    <div class="container">
      <div class="row">
        <div class="col-xl-9 mx-auto">
          <h1 class="mb-5">{{$front_config['subtitle']}}</h1>
        </div>
        <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
          <div class="form-row">
            <div class="col-12 col-md-12">
              <a href="{{route('register')}}" class="btn btn-block btn-lg btn-primary">Cadastre-se!</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  

@endsection
