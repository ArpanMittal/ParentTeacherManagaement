@extends('layouts.calendarLayouts')

@section('content')
    <div class="page-header">
        <h1>Appointments</h1>
    </div>


    <div class="row">
        <div class="col-md-12">

            <div>
                @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif
            </div>
            <table class="table table-striped">
                <thead>
                @foreach($appointmentDetails as $appointmentDetail)
                <tr>
                    <th>REQUEST ID</th>
                    <th>PARENT NAME</th>
                    <th>STUDENT ID</th>
                    <th>STUDENT NAME</th>
                    <th>GRADE</th>
                    <th>TITLE</th>
                    @if($appointmentDetail['status']=="Awaited" || $appointmentDetail['status']=="Confirmed")
                        <th>REASON OF APPOINTMENT</th>
                    @endif
                    @if($appointmentDetail['status']=="Cancelled")
                        <th>REASON OF CANCELLATION</th>
                    @endif
                    <th>START</th>
                    <th>END</th>
                    <th>STATUS</th>
                    <th class="text-right">OPTIONS</th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td>{{$appointmentDetail['requestId']}}</td>
                    <td>{{$appointmentDetail['parentName']}}</td>
                    <td>{{$appointmentDetail['studentId']}}</td>
                    <td>{{$appointmentDetail['studentName']}}</td>
                    <td>{{$appointmentDetail['grade']}}</td>
                    <td>{{$appointmentDetail['title']}}</td>
                    @if($appointmentDetail['status']=="Awaited" || $appointmentDetail['status']=="Confirmed")
                        <td>{{$appointmentDetail['reasonOfAppointment']}}</td>
                    @endif
                    @if($appointmentDetail['status']=="Cancelled")
                        <td>{{$appointmentDetail['cancellationReason']}}</td>
                    @endif
                    <td>{{$appointmentDetail['start']}}</td>
                    <td>{{$appointmentDetail['end']}}</td>
                    <td>{{$appointmentDetail['status']}}</td>
                    <td class="text-right">
                        <a class="btn btn-primary" href="{{ route('appointments.show', $appointmentDetail['requestId']) }}">View</a>
                        @if($appointmentDetail['status']=="Awaited")
                            <a class="btn btn-success" href="{{ route('getConfirm',$appointmentDetail['requestId'])}}">Confirm</a>
                        @endif
                        @if($appointmentDetail['status']=="Confirmed" || $appointmentDetail['status']=="Awaited" )
                            <a class="btn btn-danger" href="{{ route('getCancel',$appointmentDetail['requestId'])}}">Cancel</a>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>


@endsection