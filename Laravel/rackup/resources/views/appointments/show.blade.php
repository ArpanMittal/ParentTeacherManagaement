@extends('layouts.calendarLayouts')

@section('content')
    <div class="page-header">
        <h1>Appointment Details</h1>
    </div>

    <div class="row">
        <div class="col-md-12">

            <form action="#">
                {{--<div class="form-group">--}}
                    {{--<label for="nome">REQUEST ID</label>--}}
                    {{--<p class="form-control-static">{{$appointmentDetails['requestId']}}</p>--}}
                {{--</div>--}}
                <table class="table ">
                    <thead>
                    <tr>
                        <th>REQUESTED BY</th>
                        @if($appointmentLog['confirmedBy']!=null)
                            <th>CONFIRMED BY</th>
                        @endif
                        @if($appointmentLog['cancelledBy']!=null)
                            <th>CANCELLED BY</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                       <td bgcolor="#00bfff">
                           <label class="label" for="requestedBy    ">{{$appointmentLog['initiatedBy']}}
                               <br>Requested At: {{$appointmentLog['initiatedAt']}}</label>
                       </td>
                        @if($appointmentLog['confirmedBy']==null && $appointmentLog['cancelledBy']==null )
                            <td bgcolor="orange">
                                <label class="label" for="awaited">Appointment Awaited</label>
                            </td>
                        @endif
                        @if($appointmentLog['expired']==1)
                            <td bgcolor="#808080">
                                <label class="label" for="expired">Appointment Expired</label>
                                <label class="label" for="expiredOn">Expired On: $appointmentLog['expiredOn']</label>

                            </td>
                        @endif
                        @if($appointmentLog['confirmedBy']!=null)
                            <td bgcolor="green">
                                <label class="label" for="confirmed">{{$appointmentLog['confirmedBy']}}
                                    <br>Confirmed At: {{$appointmentLog['confirmedAt']}}</label>
                            </td>
                        @endif
                        @if($appointmentLog['cancelledBy']!=null)
                            <td bgcolor="red">
                                <label class="label" for="cancelled">{{$appointmentLog['cancelledBy']}}
                                    <br>Cancelled At: {{$appointmentLog['cancelledAt']}}</label>
                            </td>
                        @endif
                    </tr>
                    </tbody>
                </table>

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
                    <label for="requestedBy">REQUESTED BY</label>
                    <p class="form-control-static">{{$appointmentDetails['requestedBy']}}</p>
                </div>
                <div class="form-group">
                    <label for="status">STATUS</label>
                    <p class="form-control-static">{{$appointmentDetails['status']}}</p>
                </div>
                <a class="btn btn-default" href="{{ route('home') }}">Home</a>
                @if(($appointmentDetails['requestedBy']=="Parent") &&
                ($appointmentDetails['status']=="Awaited"))
                    <a class="btn btn-success" href="{{ route('getConfirm',$appointmentDetails['requestId'])}}">Confirm Appointment</a>
                @endif
                @if(($appointmentDetails['requestedBy']=="Parent") &&
                ($appointmentDetails['status']=="Confirmed" || $appointmentDetails['status']=="Awaited"))
                    <a class="btn btn-danger" href="{{ route('getCancel',$appointmentDetails['requestId'])}}">Cancel Appointment</a>
                @endif
            </form>
        </div>
    </div>


@endsection