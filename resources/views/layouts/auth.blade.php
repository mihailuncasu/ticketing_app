@extends('layouts.app')

@section('content')
    <div class="masthead"
         style="padding-top: 10%; min-height: 60em; background: url('{{asset('img/welcome/bg-masthead.jpg')}}')no-repeat center center;background-size: cover">
        @yield('auth')
    </div>
@endsection