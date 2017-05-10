@extends('layouts.calendarLayouts')

@section('content')

    <div class="page-header">
        <h1>Cancel Appointment</h1>
    </div>

    <div class="row">
        <div class="col-md-12">

            <form method="post" role="form" action="{{ route('postCancel',$appointmentDetails['requestId'])}}">
                <div class="form-group">
                    <label for="nome">REQUEST ID</label>
                    <p class="form-control-static">{{$appointmentDetails['requestId']}}</p>
                </div>
                <div class="form-group">
                    <label for="parentName">PARENT NAME</label>
                    <p class="form-control-static">{{$appointmentDetails['parentName']}}</p>
                </div>

                <div class="form-group">
                    <label for="parentContact">PARENT CONTACT</label>
                    <p class="form-control-static">{{$appointmentDetails['parentContact']}}</p>
                </div>
                <div class="form-group">
                    <label for="studentId">STUDENT ID</label>
                    <p class="form-control-static">{{$appointmentDetails['studentId']}}</p>
                </div>
                <div class="form-group">
                    <label for="studentName">STUDENT NAME</label>
                    <p class="form-control-static">{{$appointmentDetails['studentName']}}</p>
                </div>
                <div class="form-group">
                    <label for="grade">GRADE</label>
                    <p class="form-control-static">{{$appointmentDetails['grade']}}</p>
                </div>
                <div class="form-group">
                    <label for="title">TITLE</label>
                    <p class="form-control-static">{{$appointmentDetails['title']}}</p>
                </div>
                <div class="form-group">
                    <label for="start">START</label>
                    <p class="form-control-static">{{$appointmentDetails['start']}}</p>
                </div>
                <div class="form-group">
                    <label for="end">END</label>
                    <p class="form-control-static">{{$appointmentDetails['end']}}</p>
                </div>
                <div class="form-group {{$errors->has('cancellationReason')?'has-error':''}}">
                    <label for="cancellationReason">Reason for Cancellation</label>
                    <input type="text" name="cancellationReason" class="form-control" value="" required autofocus/>
                    @if ($errors->has('cancellationReason'))
                        <span class="help-block">
                            <strong>{{ $errors->first('cancellationReason') }}</strong>
                        </span>
                    @endif
                </div>
                <a class="btn btn-default" href="{{ route('appointments.index') }}">Back</a>
                <button class="btn btn-danger" type="submit" >Cancel</button>
            </form>
        </div>
    </div>
@endsection