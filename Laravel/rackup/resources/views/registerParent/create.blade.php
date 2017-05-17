@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Create New User</h1>
    </div>


    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('admin.store') }}" method="POST">
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
                    <label for="gradeId">Grade</label>
                    <div>
                        <input type="radio" name="gradeId"value="1"/>Playgroup
                        <input type="radio" name="gradeId"value="2"/>Nursery
                        <input type="radio" name="gradeId"value="3"/>J.K.G.
                        <input type="radio" name="gradeId"value="4"/>S.K.G.
                        @if ($errors->has('gradeId'))
                            <span class="help-block">
                                <strong>{{ $errors->first('gradeId')}}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div></div>

                <label><h2>Parent Details</h2></label>
                <div class="form-group {{$errors->has('parentName')?'has-error':''}}">
                    <label for="parentName">Parent Name</label>
                    <div>
                        <input type="text" name="parentName" id="parentName" value="{{ Input::old('parentName') }}" required autofocus>
                        @if ($errors->has('parentName'))
                            <span class="help-block">
                                <strong>{{ $errors->first('parentName') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div></div>

                <div class="form-group {{$errors->has('parentGender')?'has-error':''}}">
                    <label for="parentGender">Gender</label>
                    <div>
                        <input type="radio" name="parentGender"value="F"/>Female
                        <input type="radio" name="parentGender"value="M"/>Male
                        @if ($errors->has('parentGender'))
                            <span class="help-block">
                                <strong>{{ $errors->first('parentGender') }}</strong>
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
                <a class="btn btn-default" href="{{ route('admin.index') }}">Back</a>
                <button class="btn btn-primary" type="submit" >Create</button>
            </form>
        </div>
    </div>


@endsection
