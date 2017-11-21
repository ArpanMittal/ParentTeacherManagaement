@extends('layouts.navigation')
@section('content')
        <div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">
                    @if(isset($user))
                        {{--Admin--}}
                        @if($user->role_id==1)
                            <div>
                                <img src="{{$profilePath}}"  alt="{{$background}}" style="display: block; margin: auto; width: 40%;" align="middle">
                            </div>
                            <div style="text-align: center" >
                                <p >
                                <h2 >Welcome To Rackup Cambridge Admin Portal, {{$name}}</h2>
                                </p>
                                <a class="btn btn-primary btn-lg" href="{{ route('editProfileDetails')}}">Edit Profile</a>
                            </div>




                            {{--<table>--}}
                                {{--<tbody>--}}
                                {{--<tr>--}}
                                    {{--<td style="padding-right: 20px">@if(!is_null($profilePath) )--}}
                                            {{--<img src="{{$profilePath}}"  alt="{{"/storage/profilePhotos/1.jpg"}}" style="width:128px;height:128px;">--}}
                                        {{--@endif--}}
                                    {{--</td>--}}
                                    {{--<td style="padding-right: 20px"><h2>{{$name}}</h2></td>--}}
                                    {{--<td style="padding-right: 20px"><h2>Adminstrator</h2></td>--}}
                                    {{--<td style="padding-right: 20px"><a class="btn btn-primary btn-lg" href="{{ route('editProfileDetails')}}">Edit Profile</a></td>--}}
                                {{--</tr>--}}
                                {{--</tbody>--}}
                            {{--</table>--}}
                        @endif
                        {{--Teacher--}}
                        @if($user->role_id==4)
                            <div>
                                <img src="{{$profilePath}}"  alt="{{$background}}" style="display: block; margin: auto; width: 40%;" align="middle">
                            </div>
                            <div style="text-align: center" >
                                <p >
                                <h2 >Welcome To Rackup Cambridge Teacher Portal, {{$name}}</h2>
                                </p>
                                <a class="btn btn-primary btn-lg" href="{{ route('editProfileDetails')}}">Edit Profile</a>
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

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
