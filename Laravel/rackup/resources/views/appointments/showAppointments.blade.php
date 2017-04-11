<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.css">
</head>
<body>
<div class="container">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Date</th>
            <th>Day</th>
            <th>Duration</th>
            <th>Booked</th>
            <th>Confirm</th>
        </tr>
        @foreach($teacherAppointmentData as $appointment)
        <tr>
            <td>{{$appointment['date']}}</td>
            <td>{{$appointment['day']}}</td>
            <td>{{$appointment['duration']}}</td>
            <td>{{$appointment['booked']}}</td>
            <td><button data-toggle="modal" data-target="#confirm-booking" class="btn crud-submit btn-success" id="{{$appointment['id']}}">Confirm</button> </td>
        </tr>
        @endforeach
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.3.1/jquery.twbsPagination.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">

<script type="text/javascript">
    var url = "<?php echo route('teacherAppointments')?>";
</script>
<script src="/js/teacherAppointments-ajax.js"></script>
</body>
</html>