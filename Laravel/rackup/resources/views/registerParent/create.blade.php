@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Register New Student</h1>
    </div>

    @if (session('failure'))
        <div class="alert alert-danger">
            {{ session('failure') }}
        </div>
    @endif


    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('registerParent.store') }}" enctype="multipart/form-data" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">


                <label><h2>Student Details</h2></label>

                <div class="form-group {{$errors->has('studentName')?'has-error':''}}">
                    <label for="studentName">Student Name</label>
                    <div>
                        <input type="text" name="studentName" id="studentName" value="{{ Input::old('studentName') }}" required autofocus>
                        @if ($errors->has('studentName'))
                            <span class="help-block">
                                <strong>{{ $errors->first('studentName') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div></div>

                <div class="form-group {{$errors->has('dob')?'has-error':''}}">
                    <label for="dob">Date Of Birth</label>
                    <div>
                        <input type="date" name="dob" id="dob" value="{{ Input::old('dob') }}" required autofocus>
                        @if ($errors->has('dob'))
                            <span class="help-block">
                                <strong>{{ $errors->first('dob') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>


                <div></div>

                <div class="form-group {{$errors->has('studentGender')?'has-error':''}}">
                    <label for="studentGender">Gender</label>
                    <div>
                        <input type="radio" name="studentGender"value="F"/>Female
                        <input type="radio" name="studentGender"value="M"/>Male
                        @if ($errors->has('studentGender'))
                            <span class="help-block">
                                <strong>{{ $errors->first('studentGender') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div></div>

                <div class="form-group {{$errors->has('gradeId')?'has-error':''}}">
                    <label for="grade">Grade</label>
                    <select  id="gradeId" name="gradeId" class="form-control" required autofocus>
                        @foreach($grades as $grade)
                            <option value = "{{$grade['gradeId']}}" >{{$grade['gradeName']}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('gradeId'))
                        <span class="help-block">
                            <strong>{{ $errors->first('gradeId') }}</strong>
                        </span>
                    @endif
                </div>


                <div></div>

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

                <label><h2>Parent Details</h2></label>

                <div class="form-group {{$errors->has('fatherName')?'has-error':''}}">
                    <label for="fatherName">Father's Name</label>
                    <div>
                        <input type="text" name="fatherName" id="fatherName" value="{{ Input::old('fatherName') }}" required autofocus>
                        @if ($errors->has('fatherName'))
                            <span class="help-block">
                                <strong>{{ $errors->first('fatherName') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div></div>
                <div class="form-group {{$errors->has('motherName')?'has-error':''}}">
                    <label for="motherName">Mother's Name</label>
                    <div>
                        <input type="text" name="motherName" id="motherName" value="{{ Input::old('motherName') }}" required autofocus>
                        @if ($errors->has('motherName'))
                            <span class="help-block">
                                <strong>{{ $errors->first('motherName') }}</strong>
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
                    <label for="contact">Primary Contact</label>
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

                <div class="form-group {{$errors->has('secondaryContact')?'has-error':''}}">
                    <label for="secondaryContact">Secondary Contact</label>
                    <div>
                        <input type="text" name="secondaryContact" id="secondaryContact" value="{{ Input::old('secondaryContact') }}" required autofocus>
                        @if ($errors->has('secondaryContact'))
                            <span class="help-block">
                                <strong>{{ $errors->first('secondaryContact') }}</strong>
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
                <a class="btn btn-default" href="{{ route('registerParent.index') }}">Back</a>
                <button class="btn btn-primary" type="submit" >Register</button>
            </form>
        </div>
    </div>


@endsection
