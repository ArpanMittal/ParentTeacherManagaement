@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Register New Teacher</h1>
    </div>


    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('registerTeacher.store') }}" enctype="multipart/form-data" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group {{$errors->has('teacherName')?'has-error':''}}">
                    <label for="teacherName">Teacher Name</label>
                    <div>
                        <input type="text" name="teacherName" id="teacherName" value="{{ Input::old('teacherName') }}" required autofocus>
                        @if ($errors->has('teacherName'))
                            <span class="help-block">
                                <strong>{{ $errors->first('teacherName') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div></div>

                <div class="form-group {{$errors->has('teacherGender')?'has-error':''}}">
                    <label for="teacherGender">Gender</label>
                    <div>
                        <input type="radio" name="teacherGender"value="F"/>Female
                        <input type="radio" name="teacherGender"value="M"/>Male
                        @if ($errors->has('teacherGender'))
                            <span class="help-block">
                                <strong>{{ $errors->first('teacherGender') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div></div>

                <div class="form-group {{$errors->has('address')?'has-error':''}}">
                    <label for="address">Address</label>
                    <div>
                        <input type="text" name="address" id="address" value="{{ Input::old('address') }}" required autofocus>
                        @if ($errors->has('address'))
                            <span class="help-block">
                                <strong>{{ $errors->first('address') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div></div>

                <div class="form-group {{$errors->has('contact')?'has-error':''}}">
                    <label for="contact">Contact</label>
                    <div>
                        <input type="text" name="contact" id="contact" value="{{ Input::old('contact') }}" required autofocus>
                        @if ($errors->has('contact'))
                            <span class="help-block">
                                <strong>{{ $errors->first('contact') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div></div>

                <div class="form-group {{$errors->has('username')?'has-error':''}}">
                    <label for="username">Username</label>
                    <div>
                        <input type="email" name="username" id="username" value="{{ Input::old('username') }}" required autofocus>
                        @if ($errors->has('username'))
                            <span class="help-block">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div></div>

                <div class="form-group {{$errors->has('password')?'has-error':''}}">
                    <label for="password">Password</label>
                    <div>
                        <input type="password" name="password" id="password" required autofocus>
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group {{$errors->has('pancard')?'has-error':''}}">
                    <label for="pancard">Pancard</label>
                    <div>
                        <input type="pancard" name="pancard" id="pancard" required autofocus>
                        @if ($errors->has('pancard'))
                            <span class="help-block">
                                <strong>{{ $errors->first('pancard') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group {{$errors->has('adharcard')?'has-error':''}}">
                    <label for="adharcard">Adharcard</label>
                    <div>
                        <input type="adharcard" name="adharcard" id="adharcard" required autofocus>
                        @if ($errors->has('adharcard'))
                            <span class="help-block">
                                <strong>{{ $errors->first('adharcard') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{$errors->has('profilePhoto')?'has-error':''}}">
                    <label for="profilePhoto">Profile Photo</label>
                    <input id="profilePhoto" type="file" name="profilePhoto">
                    @if ($errors->has('profilePhoto'))
                        <span class="help-block">
                                <strong>{{ $errors->first('profilePhoto') }}</strong>
                            </span>
                    @endif
                </div>
                <div></div>

                <a class="btn btn-default" href="{{ route('registerTeacher.index') }}">Back</a>
                <button class="btn btn-primary" type="submit" >Register</button>
            </form>
        </div>
    </div>


@endsection
