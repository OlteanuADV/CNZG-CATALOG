<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config('app.name')}}</title>
    <link rel="stylesheet" href="{{config('app.url')}}/assets/css/bootstrap.css">
    <link rel="stylesheet" href="{{config('app.url')}}/assets/css/all.min.css">
    <script src="{{config('app.url')}}/assets/js/jquery-3.4.1.min.js"></script>
    <script src="{{config('app.url')}}/assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <script>
    var _token = '{{csrf_token()}}';
    var _PAGE_URL = '{{URL::to("/")}}/';
    </script>
</head>
<body class="" style="background-color:#e9ecef;">

    <nav class="navbar navbar-expand-md fixed-top navbar-dark bg-dark">
      <a class="navbar-brand" href="#">{{config('app.name')}}</a>
      <button class="navbar-toggler p-0 border-0" type="button" data-toggle="offcanvas">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="{{URL::to('/')}}"><i class="fa fa-home"></i> Acasa</a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
        @if(!Auth::check())
            <li class="nav-item">
                <a class="nav-link" href="{{URL::to('/login')}}"><i class="fa fa-sign-in-alt"></i> Login</a>
            </li>
        @else
            <li class="nav-item">
                @php date_default_timezone_set('Europe/Bucharest'); @endphp
                <a class="nav-link" href="{{URL::to('/profile/'.Auth::user()->ID)}}">Buna 
                    <?php 
                    if(date('H') < 6 || date('H') >= 20)
                    echo 'seara';
                    elseif(date('H') >= 6 && date('H') <= 11)
                    echo 'dimineata';
                    else
                        echo 'ziua';
                    ?>, {{Auth::user()->LastName}} </a>
            </li>
        @endif
        </ul>
      </div>
    </nav>
    <div class="nav-scroller bg-white box-shadow">
      <nav class="nav nav-underline">
        <a class="nav-link active" href="{{URL::to('/')}}">Acasa</a>
        <a class="nav-link" href="#">
          Friends
          <span class="badge badge-pill bg-light align-text-bottom">27</span>
        </a>
        <a class="nav-link" href="#">Explore</a>
        <a class="nav-link" href="#">Suggestions</a>
        <a class="nav-link" href="#">Link</a>
        <a class="nav-link" href="#">Link</a>
        <a class="nav-link" href="#">Link</a>
        <a class="nav-link" href="#">Link</a>
        <a class="nav-link" href="#">Link</a>
      </nav>
    </div>
    <main role="main" class="container">
        <br>
        <div id="app">
            @yield('content')
        </div>
    </main>
</body>
<script>
$(function () {
  'use strict'
  $('[data-toggle="offcanvas"]').on('click', function () {
    $('.offcanvas-collapse').toggleClass('open')
  });
  $("[data-toggle='tooltip']").tooltip();
})
</script>
@yield('jscripts')
<script src="{{config('app.url')}}/resources/public/js/app.js?ver={{strtotime(date('Y-m-d H:i:s'))}}"></script>
</html>