
    @if(isset($user))
        @if($user->role_id==1)
            <li><a href="{{ route('registerParent')}}">Register Parent</a></li>
            <li><a href="{{ route('registerTeacher')}}">Register Teacher</a></li>
            <li><a href="{{ route('showAppointmentDetails')}}">Show Appointment Details</a></li>
            <li><a href="{{ route('insertAppointmentsSlots')}}">Insert Appointments</a></li>
        @endif
        @if($user->role_id==4)
            <li><a href="{{ route('upload')}}">Upload File</a></li>
            <li><a href="{{ route('uploadLink')}}">Upload Link</a></li>
            <li><a href="{{route('teacherAppointments')}}">Show Appointments</a></li>

        @endif
        @if($user->role_id==2)
            <li><a href="{{ route('getContent')}}">Contents</a></li>
        @endif
    @endif
