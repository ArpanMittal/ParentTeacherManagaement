@extends('layouts.app')
@section('profile')
    @if(isset($user))
        {{--Admin--}}
        @if($user->role_id==1)
            <table>
                <tbody>
                <tr>
                    <td style="padding-right: 20px">@if(!is_null($profilePath) )
                            <img src="{{$profilePath}}"  alt="HTML5 Icon" style="width:128px;height:128px;">
                        @endif
                    </td>
                    <td style="padding-right: 20px"><h2>{{$name}}</h2></td>
                    <td style="padding-right: 20px"><h2>Adminstrator</h2></td>
                    <td style="padding-right: 20px"><a class="btn btn-primary btn-lg" href="{{ route('editProfileDetails')}}">Edit Profile</a></td>
                </tr>
                </tbody>
            </table>
        @endif
        {{--Teacher--}}
        @if($user->role_id==4)
            <div class="container">
                <table>
                    <tbody>
                    <tr>
                        <td style="padding-right: 20px">@if(!is_null($profilePath) )
                                <img src="{{$profilePath}}"  alt="HTML5 Icon" style="width:128px;height:128px;">
                            @endif
                        </td>
                        <td style="padding-right: 20px"><h2>{{$name}}</h2></td>
                        <td style="padding-right: 20px"><h2>TEACHER</h2></td>
                        <td style="padding-right: 20px"><a class="btn btn-primary btn-lg" href="{{ route('editProfileDetails')}}">Edit Profile</a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        @endif

        {{--@if($user->role_id==2)--}}
            {{--<div class="container">--}}
                {{--<ul class="nav nav-tabs" role="tablist">--}}
                    {{--<li><a href="{{ route('getContent')}}">Contents</a></li>--}}
                {{--</ul>--}}
             {{--</div>--}}
        {{--@endif--}}
    @endif
 @endsection