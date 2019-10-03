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
      <a class="navbar-brand" href="{{URL::to('/')}}">{{config('app.name')}}</a>
      <button class="navbar-toggler p-0 border-0" type="button" data-toggle="offcanvas">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <!--<li class="nav-item">
            <a class="nav-link" href="{{URL::to('/')}}"><i class="fa fa-home"></i> Acasa</a>
          </li>-->
        </ul>
        <ul class="navbar-nav ml-auto">
        @if(!Auth::check())
            <li class="nav-item">
                <a class="nav-link" href="{{URL::to('/login')}}"><i class="fa fa-sign-in-alt"></i> Login</a>
            </li>
        @else
            <li class="nav-item dropdown">
              <a class="nav-link" href="{{URL::to('/inbox')}}">
                @php
                $nedeschis = Auth::user()->notifications()->where('UserID',Auth::user()->ID)->where('Read',0)->count();
                @endphp
                @if($nedeschis == 0)
                <i class="fa fa-bell text-white"></i>
                @else
                <i class="fa fa-bell text-danger"></i>
                @endif
              </a>
            </li>
            <li class="nav-item dropdown">
                @php date_default_timezone_set('Europe/Bucharest'); @endphp
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Buna 
                    <?php 
                    if(date('H') < 6 || date('H') >= 20)
                    echo 'seara';
                    elseif(date('H') >= 6 && date('H') <= 11)
                    echo 'dimineata';
                    else
                        echo 'ziua';
                    ?>, {{Auth::user()->LastName}} 
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="{{URL::to('/profile/'.Auth::user()->ID)}}">Profil</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="{{url::to('/logout')}}">Deconectare</a>
                </div>
            </li>
        @endif
        </ul>
      </div>
    </nav>
    <div class="nav-scroller bg-white box-shadow">
      <nav class="nav nav-underline">
        <a class="nav-link active" href="{{URL::to('/')}}">Acasa</a>
        @if(Auth::check() && Auth::user()->Class !== 0)
        <a class="nav-link" href="{{Url::to('/classes/'.Auth::user()->Class)}}">
          Clasa mea
          <span class="badge badge-pill bg-light align-text-bottom">{{App\User::where('Class',Auth::user()->Class)->where('InSchoolFunction',0)->count()}}</span>
        </a>
        @endif
        @if(Auth::check() && Auth::user()->InSchoolFunction !== 0)
          <a class="nav-link" href="{{URL::to('/classes/mine')}}">Clasele mele</a>
        @endif
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
            @if(session('success'))
            <div class="alert alert-success" role="alert">
              {{session('success')}}
            </div>
            @endif
            @if(session('danger'))
            <div class="alert alert-danger" role="alert">
              {{session('danger')}}
            </div>
            @endif
            @yield('content')
            <br>
        </div>
    </main>
</body>
<script src="//cdn.ckeditor.com/4.13.0/standard/ckeditor.js"></script>
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