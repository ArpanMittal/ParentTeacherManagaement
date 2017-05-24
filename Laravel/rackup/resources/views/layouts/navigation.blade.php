@extends('layouts.app')
@section('sidebar')
    @if(isset($user))
        {{--Admin--}}
        @if($user->role_id==1)
            <div class="container">
                <ul class="nav nav-tabs" role="tablist">
                    @if(!is_null($profilePath) )
                        <div>
                            <li><img src="{{$profilePath}}"  alt="HTML5 Icon" style="width:128px;height:128px;"></li>
                        </div>
                    @endif
                    <div><li><h1>{{$name}}</h1></li></div>
                    <div><li><h3>Adminstrator</h3></li></div>
                    <div><li><a class="btn btn-primary" href="{{ route('editProfileDetails')}}">Edit Profile</a></li>
                    </div>
                </ul>
            </div>
            <div class="container">
                <ul class="nav nav-tabs" role="tablist">
                    {{--<li><a href="{{ route('registerParent.index')}}">Register User</a></li>--}}
                    <li><a href="{{ route('calendar')}}">Slots and School Events</a></li>
                    <li><a href="{{ route('registerParent.index')}}">Register Parent</a></li>
                    <li><a href="{{ route('registerTeacher.index')}}">Register Teacher</a></li>
                    <li><a href="{{ route('admin.create')}}">new grade</a></li>
                    <li><a href="{{ route('getAssignTeacher')}}">Assign Teacher</a></li>
                    {{--<li><a href="{{ route('showAppointmentDetails')}}">Show Appointment Details</a></li>--}}
                    {{--<li><a href="{{ route('insertAppointmentsSlots')}}">Insert Slots</a></li>--}}
                    {{--<li><a href="{{ route('teachersList')}}">Teachers  List</a></li>--}}
                    {{--<li><a href="{{ route('parentsList')}}">Parents List</a></li>--}}

                </ul>
            </div>
        @endif
        {{--Teacher--}}
        @if($user->role_id==4)
            <div class="container">
                <ul class="nav nav-tabs" role="tablist">
                    @if(!is_null($profilePath) )
                        <div>
                            <li><img src="{{$profilePath}}"  alt="HTML5 Icon" style="width:128px;height:128px;"></li>
                        </div>
                    @endif
                    <div><li><h1>{{$name}}</h1></li></div>
                    <div><li><h3>TEACHER</h3></li></div>
                    <div><li><a class="btn btn-primary" href="{{ route('editProfileDetails')}}">Edit Profile</a></li>
                    </div>
                </ul>
            </div>
            <div class="container">
                <ul class="nav nav-tabs" role="tablist">
                    {{--<li><a href="{{ route('upload')}}">Upload File</a></li>--}}
                    {{--<li><a href="{{ route('uploadLink')}}">Upload Link</a></li>--}}
                    <li><a href="{{ route('upload.index')}}">Upload Links</a></li>
                    <li><a href="{{ route('uploadImage.index')}}">Upload Image</a></li>
                    <li><a href="{{ route('uploadPdf.index')}}">Upload Pdf</a></li>
                    <li><a href="{{ route('appointments.index')}}">View Appointments</a></li>
                    {{--<li><a href="{{route('teacherAppointments')}}">Show Appointments</a></li>--}}
                </ul>
            </div>
        @endif

        @if($user->role_id==2)
            <div class="container">
                <ul class="nav nav-tabs" role="tablist">
                    <li><a href="{{ route('getContent')}}">Contents</a></li>
                </ul>
             </div>

        @endif
    @endif
 @endsection