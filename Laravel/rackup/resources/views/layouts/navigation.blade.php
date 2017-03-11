
    @if(isset($user))
        @if($user->role_id==1)
            <li><a href="{{ route('registerParent')}}">Register Parent</a></li>
            <li><a href="{{ route('registerTeacher')}}">Register Teacher</a></li>
        @endif
    @endif
