@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Edit Teacher Details</h1>
        <img src="{{$teacherDetails['profilePhoto']}}" alt="HTML5 Icon" style="width:128px;height:128px;">
    </div>


    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('registerTeacher.update', $teacher_details['teacherId']) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">


                {{--<div class="form-group">--}}
                    {{--<label for="teacherId">Teacher Id</label>--}}
                    {{--<p class="form-control-static">{{$teacher_details['teacherId']}}</p>--}}
                {{--</div>--}}
                <div class="form-group">
                    <label for="username">Username</label>
                    <p class="form-control-static">{{$teacher_details['username']}}</p>
                </div>
                <div class="form-group {{$errors->has('teacherName')?'has-error':''}}">
                    <label for="teacherName">Teacher's Name</label>
                    <input type="text" name="teacherName" class="form-control" value="{{$teacher_details['teacherName']}}"/>
                    @if ($errors->has('teacherName'))
                        <span class="help-block">
                                <strong>{{ $errors->first('teacherName') }}</strong>
                            </span>
                    @endif
                </div>
                {{--<div class="form-group">--}}
                    {{--<label for="teacherGender">Gender</label>--}}
                    {{--<input type="radio" name="teacherGender"value="F"/>Female--}}
                    {{--<input type="radio" name="teacherGender"value="M"/>Male--}}
                {{--</div>--}}
                <div class="form-group {{$errors->has('contact')?'has-error':''}}">
                    <label for="contact">Contact</label>
                    <input type="text" name="contact" class="form-control" value="{{$teacher_details['contact']}}"/>
                    @if ($errors->has('contact'))
                        <span class="help-block">
                                <strong>{{ $errors->first('contact') }}</strong>
                            </span>
                    @endif
                </div>
                <div class="form-group $errors->has('address')?'has-error':''}}">
                    <label for="address">Address</label>
                    <input type="text" name="address" class="form-control" value="{{$teacher_details['address']}}"/>
                    @if ($errors->has('address'))
                        <span class="help-block">
                                <strong>{{ $errors->first('address') }}</strong>
                            </span>
                    @endif
                </div>

                <a class="btn btn-default" href="{{ route('registerTeacher.index') }}">Back</a>
                <button class="btn btn-primary" type="submit" >Save</button>
            </form>
        </div>
    </div>


@endsection
