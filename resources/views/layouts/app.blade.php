<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ServiceNow') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
</head>
<body>
    <!--NAV-BAR-->
    <nav class="navbar navbar-light navbar-expand-md fixed-top text-dark navigation-clean-button" style="background-color: rgba(189,190,191,0.65);">
        <div class="container-fluid"><a class="navbar-brand" href="{{ url(env('APP_URL')) }}">{{ config('app.name', 'ServiceNow') }}</a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">{{__('Toggle navigation')}}</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse"
                id="navcol-1">
                <ul class="nav navbar-nav mr-auto"></ul>
                <span class="float-right navbar-text actions">
                    <a class="{{ Route::is('login') || Route::is('login.domain')  ? 'btn btn-light action-button' : 'login' }}" role="button" href="{{ route('login') }}">
                        {{ __('Login') }}
                    </a>
                    <a class="{{ Route::is('register') ? 'btn btn-light action-button' : 'login' }}" role="button" href="{{ route('register') }}">
                        {{ __('Register') }}
                    </a>
                </span>
                <ul class="nav navbar-nav float-right">
                    <li class="nav-item dropdown" style="width: 80px;">
                        <a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#">
                            <i class="fa fa-language" style="color: rgb(70,87,101);width: 25px;"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right bg-white border rounded border-secondary shadow-sm"
                            role="menu" style="background-color: rgb(86,198,198);">
                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                <a class="dropdown-item" role="presentation" hreflang="{{ $localeCode }}"
                                    href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                <img style="width: 25px;height: 25px;margin-right: 5px;" src="{{ asset('img/welcome/'. $localeCode .'_flag.jpg')}}">
                                    {{ $properties['native'] }}
                                </a>
                            @endforeach
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @yield('content')
    <!--FOOTER-->
    <footer data-aos="fade-up" class="footer bg-light" style="background-color: rgb(248,249,250);">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 my-auto h-100 text-center text-lg-left">
                    <ul class="list-inline mb-2">
                        <li class="list-inline-item"><a href="#">About</a></li>
                        <li class="list-inline-item"><span>⋅</span></li>
                        <li class="list-inline-item"><a href="#">Contact</a></li>
                        <li class="list-inline-item"><span>⋅</span></li>
                        <li class="list-inline-item"><a href="#">Terms of &nbsp;Use</a></li>
                        <li class="list-inline-item"><span>⋅</span></li>
                        <li class="list-inline-item"><a href="#">Privacy Policy</a></li>
                    </ul>
                    <p class="text-muted small mb-4 mb-lg-0">© Brand 2020. All Rights Reserved.</p>
                </div>
                <div class="col-lg-6 my-auto h-100 text-center text-lg-right">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item"><a href="#"><i class="fa fa-facebook fa-2x fa-fw"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="fa fa-twitter fa-2x fa-fw"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="fa fa-instagram fa-2x fa-fw"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.3.1/js/swiper.jquery.min.js"></script>
    <!-- Scripts -->
    <script src="{{ asset('js/welcome.js') }}" defer></script>
</body>
</html>
