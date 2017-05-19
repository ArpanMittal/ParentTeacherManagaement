@extends('layouts.app')
@section('sidebar')
    @if(isset($user))
        @if($user->role_id==1)
            <div class="container">
                <ul class="nav nav-tabs" role="tablist">
                    {{--<li><a href="{{ route('registerParent.index')}}">Register User</a></li>--}}
                    <li><a href="{{ route('calendar')}}">Slots and School Events</a></li>
                    <li><a href="{{ route('editProfileDetails')}}">Edit Profile</a></li>
                    <li><a href="{{ route('registerParent.index')}}">Register Parent</a></li>
                    <li><a href="{{ route('registerTeacher.index')}}">Register Teacher</a></li>
                    {{--<li><a href="{{ route('showAppointmentDetails')}}">Show Appointment Details</a></li>--}}
                    {{--<li><a href="{{ route('insertAppointmentsSlots')}}">Insert Slots</a></li>--}}
                    {{--<li><a href="{{ route('teachersList')}}">Teachers  List</a></li>--}}
                    {{--<li><a href="{{ route('parentsList')}}">Parents List</a></li>--}}

                </ul>
            </div>
        @endif

        @if($user->role_id==4)
            <div class="container">
                <ul class="nav nav-tabs" role="tablist">
                    {{--<li><a href="{{ route('upload')}}">Upload File</a></li>--}}
                    {{--<li><a href="{{ route('uploadLink')}}">Upload Link</a></li>--}}
                    <li><a href="{{ route('upload.index')}}">Upload Links</a></li>
                    <li><a href="{{ route('uploadImage.index')}}">Upload Image</a></li>
                    <li><a href="{{ route('appointments.index')}}">View Appointments</a></li>
                    <li><a href="{{ route('editProfileDetails')}}">Edit Profile</a></li>
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