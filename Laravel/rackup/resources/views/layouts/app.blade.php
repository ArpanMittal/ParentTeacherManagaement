<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Rackup Cambridge</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=PT+Sans|Changa">

    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">--}}
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}
    <link href="{!! asset('icomoon/style.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/select2.css') !!}" rel="stylesheet">
    <link href="{!! asset('css/main.css') !!}" rel="stylesheet">
    @yield('script_header')
    @yield('style')
    <style>
        body {
            font-family: 'PT Sans';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand">
               Rackup Cambridge
            </a>
        </div>


        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <ul class="nav navbar-nav navbar-left">
                @if(isset($user))
                    {{--Admin--}}
                @if($user->role_id==1)
                        <div class="btn-group" style="text-align:left; float: left;">
                            <a  class="btn btn-default" href="{{ route('home') }}" style="float: right;">Home</a>
                        </div>
                        <div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Registration<span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('registerParent.index')}}">Student's Registration</a></li>
                                <li><a href="{{ route('registerTeacher.index')}}">Teacher's Registraion</a></li>
                                <li><a href="{{ route('admin.create')}}">Add New Grade</a></li>
                                <li><a href="{{ route('getAssignTeacher')}}">Assign Teachers</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Appointments<span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('appointments.create')}}">Request Appointment</a></li>
                                <li><a href="{{ route('appointments.index')}}">My Appointments</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Slots and School Events <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="/calendar_events">Free Slots</a></li>
                                <li><a href="/school_events">School Events</a></li>
                                <li><a href="{{ route('calendar')}}">Show Calendar</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <a class="btn btn-default" href="{{ route('showAll')}}">Show Uploads</a>
                        </div>
                        <div class="btn-group" style="text-align: right; float: right;">
                            <a class="btn btn-default" href="{{ route('logout') }}" style="float: right;">Logout</a>
                        </div>
                    @endif


                {{--Teacher--}}
                @if($user->role_id==4)
                        <div class="btn-group" style="text-align:left; float: left;">
                            <a  class="btn btn-default" href="{{ route('home') }}" style="float: right;">Home</a>
                        </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Uploads<span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('upload.index')}}">Video Links</a></li>
                            <li><a href="{{ route('uploadImage.index')}}">Images</a></li>
                            <li><a href="{{ route('uploadPdf.index')}}">Files</a></li>
                        </ul>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Appointments<span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('appointments.create')}}">Request Appointment</a></li>
                            <li><a href="{{ route('appointments.index')}}">My Appointments</a></li>
                            <li><a href="/teacherCalendar">Show Calendar</a></li>
                        </ul>
                    </div>

                    <div class="btn-group" style="text-align: right; float: right;">
                        <a class="btn btn-default" href="{{ route('logout') }}" style="float: right;">Logout</a>
                    </div>
                    {{--<li><a href="{{route('teacherAppointments')}}">Show Appointments</a></li>--}}
                @endif
                @endif
            </ul>
            {{--<!-- Right Side Of Navbar -->--}}
            {{--<ul class="nav navbar-nav navbar-right">--}}
                {{--<!-- Authentication Links -->--}}
                {{--@if (!isset($user))--}}

                {{--@else--}}
                    {{--<li><a href="{{ route('home') }}">Home</a></li>--}}
                    {{--<li><a href="{{ route('logout') }}">Logout</a></li>--}}

                    {{--  <li class="dropdown">--}}
                           {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">--}}
                               {{--{{ $user->username }} <span class="caret"></span>--}}
                           {{--</a>--}}

                          {{--<ul class="dropdown-menu" role="menu">--}}
                              {{--<li><a href="{{ route('logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>--}}
                          {{--</ul>--}}
                      {{--</li>--}}
                {{--@endif--}}
            {{--</ul>--}}
        </div>
    </div>
</nav>
<div class="container">
    <div class="row">
        <div class="col-md-12">@yield('profile')</div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12">@yield('content')</div>
    </div>
</div>



        <!-- JavaScripts -->
<script src="{!! asset('js/jquery.min.js') !!}" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
@yield('script')
</body>
</html>
{{--<!DOCTYPE html>;
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Rackup Cambridge</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.id', 'Rackup Cambridge') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                            <!-- Authentication Links -->
                        @if (Auth::guest())
                            {{--<li><a href="{{ route('logout') }}">Logout</a></li>
                            {{--<li><a href="{{ route('register') }}">Register</a></li>
                       @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="/js/app.js"></script>
</body>
</html>--}}