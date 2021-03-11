<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="@yield('description')">
  <meta name="author" content="">

  <title>@yield('title')</title>

  <!-- Bootstrap core CSS -->
  <link href="{{asset('vendor/adminlte/dist/css/adminlte.min.css')}}" rel="stylesheet">

  <!-- Custom fonts for this template -->
  <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
  <link href="{{asset('vendor/simple-line-icons/css/simple-line-icons.css')}}" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template -->
  <link href="{{asset('assets/css/landing-page.min.css')}}" rel="stylesheet">

</head>

<body>

  <!-- Navigation -->
  <nav class="navbar navbar-light bg-light static-top">
    <div class="container">
      <a class="navbar-brand" href="{{route('home')}}">{{$front_config['title']}}</a>
      <a class="btn btn-primary" href="{{route('login')}}">Acessar</a>
    </div>
  </nav>


  @yield('content')


  <!-- Footer -->
  <footer class="footer bg-light">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 h-100 text-center text-lg-left my-auto">
          <ul class="list-inline mb-2">
            <li class="list-inline-item">
              <a href="{{$front_config['about']}}">Sobre</a>
            </li>
            <li class="list-inline-item">&sdot;</li>
            <li class="list-inline-item">
              <a href="mailto:{{$front_config['email']}}">Contato</a>
            </li>
            <li class="list-inline-item">&sdot;</li>
            <li class="list-inline-item">
              <a href="{{$front_config['termsofuse']}}">Termos de Uso</a>
            </li>
            <li class="list-inline-item">&sdot;</li>
            <li class="list-inline-item">
              <a href="{{$front_config['privacypolicy']}}">Políticas de Privacidade</a>
            </li>
          </ul>
          <p class="text-muted small mb-4 mb-lg-0">&copy; {{$front_config['title']}}. Todos os direitos reservados.</p>
        </div>
        <div class="col-lg-6 h-100 text-center text-lg-right my-auto">
          <ul class="list-inline mb-0">
            <li class="list-inline-item mr-3">
              <a href="{{$front_config['facebook']}}">
                <i class="fab fa-facebook fa-2x fa-fw"></i>
              </a>
            </li>
            <li class="list-inline-item mr-3">
              <a href="{{$front_config['twitter']}}">
                <i class="fab fa-twitter-square fa-2x fa-fw"></i>
              </a>
            </li>
            <li class="list-inline-item">
              <a href="{{$front_config['instagram']}}">
                <i class="fab fa-instagram fa-2x fa-fw"></i>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer>

  <!-- Bootstrap core JavaScript -->
  <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
  <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

</body>

</html>
