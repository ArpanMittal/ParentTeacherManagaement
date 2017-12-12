<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <link rel="icon" href="favicon.ico">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=PT+Sans|Changa">
    <!-- Custom styles for this template -->
    <!-- <link href="starter-template.css" rel="stylesheet"> -->

    <link rel="stylesheet" href="css/fullcalendar.min.css">
    <link rel="stylesheet" href="css/fullcalendar.print.css" media="print"/>

</head>

<body>
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
            <a class="navbar-brand" href="{{ route('home') }}">Rackup Cambridge</a>
        </div>


        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                @if(isset($user))
                    {{--Admin--}}
                    @if($user->role_id==1)
                        {{--<li><a href="#">Home</a></li>--}}
                        {{--<li><a href="#">About</a></li>--}}
                        {{--<li><a href="#">Services</a></li>--}}
                        {{--<li><a href="#">Works</a></li>--}}
                        {{--<li><a href="#">News</a></li>--}}
                        {{--<li><a href="#">Contact</a></li>--}}
                        {{--<li>--}}
                        {{--<a class="btn btn-default btn-outline btn-circle"  data-toggle="collapse" href="#nav-collapse1" aria-expanded="false" aria-controls="nav-collapse1">Categories</a>--}}
                        {{--</li>--}}

                        <li> <a  href="{{ route('home') }}">Home</a></li>

                        {{--<div>--}}
                        {{--<button type="button" class="btn btn-default dropdown-toggle"--}}
                        {{--data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span>--}}
                        {{--</button>--}}
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Registration <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('registerParent.index')}}">Student's Registration</a></li>
                                <li><a href="{{ route('registerTeacher.index')}}">Teacher's Registration</a></li>
                                {{--<li><a href="{{ route('admin.create')}}">Add New Grade</a></li>--}}
                                <li><a href="{{ route('getAssignTeacher')}}">Assign Teachers</a></li>
                            </ul>
                        </li>
                        {{--</div>--}}
                        {{--<div class="btn-group">--}}
                        {{--<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                        {{--Appointments<span class="caret"></span>--}}
                        {{--</button>--}}

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Appointments <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('appointments.create')}}">Request Appointment</a></li>
                                <li><a href="{{ route('appointments.index')}}">My Appointments</a></li>
                            </ul>
                        </li>
                        {{--</div>--}}
                        {{--<div class="btn-group">--}}
                        {{--<button type="button" class="btn btn-default dropdown-toggle"--}}
                        {{--data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                        {{--Uploads<span class="caret"></span>--}}
                        {{--</button>--}}
                        <li class = "dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Uploads <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                {{--<li><a href="{{ route('upload.index')}}">Video Links</a></li>--}}
                                {{--<li><a href="{{ route('uploadImage.index')}}">Images</a></li>--}}
                                <li><a href="{{ route('uploadPdf.index')}}">Images</a></li>
                            </ul>
                        </li>

                        {{--<div class="btn-group">--}}
                        {{--<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                        {{--Slots and School Events <span class="caret"></span>--}}
                        {{--</button>--}}
                        <li class = "dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Slots and School Events <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="/calendar_events">Free Slots</a></li>
                                <li><a href="/school_events">School Events</a></li>
                                <li><a href="{{ route('calendar')}}">Show Calendar</a></li>
                            </ul>
                        </li>
                        {{--</div>--}}
                        <li>
                            <a  href="{{ route('showAll')}}">Show Uploads</a>
                        </li>
                        <li>
                            <a  href="{{ route('logout') }}" style="float: right;">Logout</a>
                        </li>
                    @endif
                    {{--Content Provider--}}
                    @if($user->role_id == 7)
                        {{--<div class="btn-group">--}}
                        {{--<button type="button" class="btn btn-default dropdown-toggle"--}}
                        {{--data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                        {{--Uploads<span class="caret"></span>--}}
                        {{--</button>--}}
                        <li class = "dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Upload <b class="caret"></b></a>

                            <ul class="dropdown-menu">
                                <li><a href="{{ route('upload.index')}}">Video Links</a></li>
                                {{--<li><a href="{{ route('uploadImage.index')}}">Images</a></li>--}}
                                {{--<li><a href="{{ route('uploadPdf.index')}}">Files</a></li>--}}
                            </ul>
                        {{--</div>--}}
                        <li>
                            <a href="{{ route('logout') }}" >Logout</a>
                        </li>

                    @endif


                    {{--Teacher--}}
                    @if($user->role_id==4)
                        <li>
                            <a  href="{{ route('home') }}" style="float: right;">Home</a>
                        </li>
                        {{--<div class="btn-group">--}}
                        {{--<button type="button" class="btn btn-default dropdown-toggle"--}}
                        {{--data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                        {{--Uploads<span class="caret"></span>--}}
                        {{--</button>--}}
                        <li class = "dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Upload <b class="caret"></b></a>

                            <ul class="dropdown-menu">
                                {{--<li><a href="{{ route('upload.index')}}">Video Links</a></li>--}}
                                <li><a href="{{ route('uploadImage.index')}}">Images</a></li>
                                {{--<li><a href="{{ route('uploadPdf.index')}}">Files</a></li>--}}
                            </ul>
                        </li>

                        {{--<div class="btn-group">--}}
                        {{--<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                        {{--Appointments<span class="caret"></span>--}}
                        {{--</button>--}}
                        <li class = "dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Appointments <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('appointments.create')}}">Request Appointment</a></li>
                                <li><a href="{{ route('appointments.index')}}">My Appointments</a></li>
                                <li><a href="/teacherCalendar">Show Calendar</a></li>
                            </ul>
                        </li>

                        <li>
                            <a  href="{{ route('logout') }}" >Logout</a>
                        </li>
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
        <div class="col-md-12" style="z-index:auto">@yield('content')</div>
    </div>

</div><!-- /.container -->





<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"></script>
<script src="js/jquery.min.js"></script>
<script src="js/moment.min.js"></script>
<script src="js/fullcalendar.min.js"></script>




@yield('scripts')

</body>
</html>
