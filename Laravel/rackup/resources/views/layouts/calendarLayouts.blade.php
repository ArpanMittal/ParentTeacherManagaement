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

    <!-- Custom styles for this template -->
    <!-- <link href="starter-template.css" rel="stylesheet"> -->

    <link rel="stylesheet" href="css/fullcalendar.min.css">
    <link rel="stylesheet" href="css/fullcalendar.print.css" media="print"/>

</head>

<body>



<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand">
                Rackup Cambridge
            </a>

        </div>

        <div id="navbar" class="collapse navbar-collapse">
            @if(isset($user))
            {{--<ul class="nav navbar-nav navbar-right">--}}
                {{--<li><a href="{{ route('home') }}">Home</a></li>--}}
                {{--<li><a href="{{ route('logout') }}">Logout</a></li>--}}
            {{--</ul>--}}
            <ul class="nav navbar-nav">
                    @if($user->role_id==1)
                    <li><a href="{{ route('registerParent.index')}}">Student's Registration</a></li>
                    <li><a href="{{ route('registerTeacher.index')}}">Teacher's Registraion</a></li>
                    <li><a href="{{ route('admin.create')}}">Add New Grade</a></li>
                    <li><a href="{{ route('getAssignTeacher')}}">Assign Teachers</a></li>
                    <li><a href="/calendar_events">Free Slots</a></li>
                    <li><a href="/school_events">School Events</a></li>
                    <li><a href="/calendar">Show Calendar</a></li>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('logout') }}">Logout</a></li>
                    @endif
                    @if($user->role_id==4)
                        <li><a href="{{ route('upload.index')}}">Upload Link</a></li>
                        <li><a href="{{ route('uploadImage.index')}}">Upload Image</a></li>
                        <li><a href="{{ route('uploadPdf.index')}}">Upload Pdf</a></li>
                        <li><a href="/appointments">My Appointments</a></li>
                        <li><a href="{{ route('appointments.create')}}">Request Appointment</a></li>
                        <li><a href="/teacherCalendar">Show Calendar</a></li>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('logout') }}">Logout</a></li>
                    @endif
            </ul>
            @endif
        </div><!--/.nav-collapse -->
    </div>
</nav>


<div class="container">
    <div class="row">
        <div class="col-md-12">@yield('content')</div>
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
