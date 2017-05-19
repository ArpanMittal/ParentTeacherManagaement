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
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            @if(isset($user))
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('logout') }}">Logout</a></li>
                </ul>
                <ul class="nav navbar-nav">
                    @if($user->role_id==1)
                        <li><a href="registerParent.index">Register Parent</a></li>
                        <li><a href="registerTeacher.index">Register Teacher</a></li>
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
{{--<!-- Placed at the end of the document so the pages load faster -->--}}
{{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"></script>--}}
{{--<script src="js/jquery.min.js"></script>--}}
{{--<script src="js/moment.min.js"></script>--}}
{{--<script src="js/fullcalendar.min.js"></script>--}}





</body>
</html>
