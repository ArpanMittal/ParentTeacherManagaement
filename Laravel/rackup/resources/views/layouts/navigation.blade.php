
    @if(isset($user))
        @if($user->role_id==1)
            <li><a href="{{ route('registerUser')}}">Register</a></li>
        @endif
    @endif
