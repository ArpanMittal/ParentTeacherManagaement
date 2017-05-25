@extends('layouts.calendarLayouts')

@section('content')
<html>
<head>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
          crossorigin="anonymous">
</head>
<body>

    <div class="page-header">
        <h1>Confirm Appointment</h1>
    </div>

    <div class="row">
        <div class="col-md-12">

            <form  id="contactNumber" method="post" role="form" action="{{ route('postConfirm',$appointmentDetails['requestId'])}}" >
                {{--<div class="form-group">--}}
                    {{--<label for="nome">REQUEST ID</label>--}}
                    {{--<p class="form-control-static">{{$appointmentDetails['requestId']}}</p>--}}
                {{--</div>--}}
                <div class="form-group">
                    <label for="parentName">PARENT NAME</label>
                    <p class="form-control-static">{{$appointmentDetails['parentName']}}</p>
                </div>
                <div class="form-group">
                    <label for="parentContact">PARENT CONTACT</label>
                    <p class="form-control-static">{{$appointmentDetails['parentContact']}}</p>
                </div>
                {{--<div class="form-group">--}}
                    {{--<label for="studentId">STUDENT ID</label>--}}
                    {{--<p class="form-control-static">{{$appointmentDetails['studentId']}}</p>--}}
                {{--</div>--}}
                <div class="form-group">
                    <label for="studentName">STUDENT NAME</label>
                    <p class="form-control-static">{{$appointmentDetails['studentName']}}</p>
                </div>
                <div class="form-group">
                    <label for="grade">GRADE</label>
                    <p class="form-control-static">{{$appointmentDetails['grade']}}</p>
                </div>
                {{--<div class="form-group">--}}
                    {{--<label for="title">TITLE</label>--}}
                    {{--<p class="form-control-static">{{$appointmentDetails['title']}}</p>--}}
                {{--</div>--}}
                <div class="form-group">
                    <label for="reasonOfAppointment">REASON OF APPOINTMENT</label>
                    <p class="form-control-static">{{$appointmentDetails['reasonOfAppointment']}}</p>
                </div>
                <div class="form-group">
                    <label for="start">START</label>
                    <p class="form-control-static">{{$appointmentDetails['start']}}</p>
                </div>
                <div class="form-group">
                    <label for="end">END</label>
                    <p class="form-control-static">{{$appointmentDetails['end']}}</p>
                </div>
                <div class="form-group">
                    <label for="contact">Contact Number</label>
                    <p class="form-control-static">{{$appointmentDetails['contact']}}</p>
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#changeContact">Enter New Contact Number</button>
                </div>
                <a class="btn btn-default" href="{{ route('appointments.index') }}">Cancel</a>
                <button class="btn btn-success" type="submit" id="confirm-appointment">Submit</button>
            </form>
         </div>
     </div>



    <!-- Modal -->
    <div id="changeContact" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Change Contact Number</h4>
                </div>
                <div class="modal-body">
                    <form id="contactNumber" method="post" role="form" action="{{ route('changeContactNumber',$appointmentDetails['requestId'])}}">
                        {{csrf_field()}}
                        <div class="form-group {{$errors->has('contact')?'has-error':''}}" id="contactNo">
                            <label for="contact">Enter new Contact Number</label>
                            <input type="text" id="contact" name="contact" class="form-control" value=""  required autofocus/>
                            @if ($errors->has('contact'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('contact') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="submit" id="change-contact" class="btn crud-submit btn-success">Change contact number</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.3.1/jquery.twbsPagination.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">

    <script type="text/javascript">
        var url = "<?php echo route('changeContactNumber',$appointmentDetails['requestId'])?>";
    </script>
    <script src="/js/changeContact-ajax.js"></script>

</body>
</html>
@endsection