@extends('layouts.calendarLayouts')

@section('content')
    <div class="page-header">
        <h1>Appointment</h1>
    </div>

    <div class="row">
        <div class="col-md-12">

            <form action="#">
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
                {{--<div class="form-group">--}}
                    {{--<label for="title">TITLE</label>--}}
                    {{--<p class="form-control-static">{{$appointmentDetails['title']}}</p>--}}
                {{--</div>--}}
                @if($appointmentDetails['status']=="Confirmed" || $appointmentDetails['status']=="Awaited")
                    <div class="form-group">
                        <label for="reasonOfAppointment">REASON OF APPOINTMENT</label>
                        <p class="form-control-static">{{$appointmentDetails['reasonOfAppointment']}}</p>
                    </div>
                @endif
                @if($appointmentDetails['status']=="Cancelled")
                    <div class="form-group">
                        <label for="cancellationReason">REASON OF CANCELLATION</label>
                        <p class="form-control-static">{{$appointmentDetails['cancellationReason']}}</p>
                    </div>
                @endif
                <div class="form-group">
                    <label for="start">START</label>
                    <p class="form-control-static">{{$appointmentDetails['start']}}</p>
                </div>
                <div class="form-group">
                    <label for="end">END</label>
                    <p class="form-control-static">{{$appointmentDetails['end']}}</p>
                </div>
                <div class="form-group">
                    <label for="status">STATUS</label>
                    <p class="form-control-static">{{$appointmentDetails['status']}}</p>
                </div>

                <a class="btn btn-default" href="{{ route('appointments.index') }}">Index</a>
                @if($appointmentDetails['status']=="Awaited")
                    <a class="btn btn-success" href="{{ route('getConfirm',$appointmentDetails['requestId'])}}">Confirm</a>
                @endif
                @if($appointmentDetails['status']=="Confirmed" || $appointmentDetails['status']=="Awaited")
                    <a class="btn btn-danger" href="{{ route('getCancel',$appointmentDetails['requestId'])}}">Cancel</a>
                @endif
            </form>
        </div>
    </div>


@endsection