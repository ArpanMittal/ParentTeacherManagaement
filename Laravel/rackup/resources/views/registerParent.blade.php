<html>
<head>
    <title>Register User</title>
    <link href="/css/app.css" rel="stylesheet">
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-md-12 ">
            <div class="panel panel-default">
                <div class="panel-heading"><h1>Registration Form</h1></div>
                <div class="panel-body">
                    <form id="registerForm" method="post" role="form">

                        <label class="col-md-12 control-label"><h2>Student Details</h2></label>

                        <div class="form-group {{$errors->has('studentName')?'has-error':''}}">
                            <label for="studentName"  class="col-md-4 control-label">Student Name</label>
                            <div class="col-md-6">
                                <input type="text" name="studentName" id="studentName" value="{{ Input::old('studentName') }}" required autofocus>
                                @if ($errors->has('studentName'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('studentName') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12"></div>

                        <div class="form-group {{$errors->has('dob')?'has-error':''}}">
                            <label for="dob"  class="col-md-4 control-label">Date Of Birth</label>
                            <div class="col-md-6">
                                <input type="date" name="dob" id="dob" value="{{ Input::old('dob') }}" required autofocus>
                                @if ($errors->has('dob'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('dob') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>


                        <div class="col-md-12"></div>

                        <div class="form-group {{$errors->has('studentGender')?'has-error':''}}">
                            <label for="studentGender"  class="col-md-4 control-label">Gender</label>
                            <div class="col-md-6">
                                <input type="radio" name="studentGender"value="F"/>Female
                                <input type="radio" name="studentGender"value="M"/>Male
                                @if ($errors->has('studentGender'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('studentGender') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12"></div>

                        <div class="form-group {{$errors->has('gradeId')?'has-error':''}}">
                            <label for="gradeId"  class="col-md-4 control-label">Grade</label>
                            <div class="col-md-6">
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

                        <div class="col-md-12"></div>

                        <label class="col-md-12 control-label"><h2>Parent Details</h2></label>
                        <div class="form-group {{$errors->has('parentName')?'has-error':''}}">
                            <label for="parentName"  class="col-md-4 control-label">Parent Name</label>
                            <div class="col-md-6">
                                <input type="text" name="parentName" id="parentName" value="{{ Input::old('parentName') }}" required autofocus>
                                @if ($errors->has('parentName'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('parentName') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12"></div>

                        <div class="form-group {{$errors->has('parentGender')?'has-error':''}}">
                            <label for="parentGender"  class="col-md-4 control-label">Gender</label>
                            <div class="col-md-6">
                                <input type="radio" name="parentGender"value="F"/>Female
                                <input type="radio" name="parentGender"value="M"/>Male
                                @if ($errors->has('parentGender'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('parentGender') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12"></div>

                        <div class="form-group {{$errors->has('address')?'has-error':''}}">
                            <label for="address"  class="col-md-4 control-label">Address</label>
                            <div class="col-md-6">
                                <input type="text" name="address" id="address" value="{{ Input::old('address') }}" required autofocus>
                                @if ($errors->has('address'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('address') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12"></div>

                        <div class="form-group {{$errors->has('contact')?'has-error':''}}">
                            <label for="contact"  class="col-md-4 control-label">Contact</label>
                            <div class="col-md-6">
                                <input type="text" name="contact" id="contact" value="{{ Input::old('contact') }}" required autofocus>
                                @if ($errors->has('contact'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('contact') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>


                        <div class="col-md-12"></div>

                        <div class="form-group {{$errors->has('role')?'has-error':''}}">
                            <label for="role"  class="col-md-4 control-label">Role</label>
                            <div class="col-md-6">
                                <input type="radio" name="role"value="2"/>Parent
                                <input type="radio" name="role"value="3"/>Principal
                                <input type="radio" name="role"value="4"/>Teacher
                                @if ($errors->has('role'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('role') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12"></div>

                        <div class="form-group {{$errors->has('username')?'has-error':''}}">
                            <label for="username"  class="col-md-4 control-label">Username</label>
                            <div class="col-md-6">
                                <input type="email" name="username" id="username" value="{{ Input::old('username') }}" required autofocus>
                                @if ($errors->has('username'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12"></div>

                        <div class="form-group {{$errors->has('password')?'has-error':''}}">
                            <label for="password"  class="col-md-4 control-label">Password</label>
                            <div class="col-md-6">
                                <input type="password" name="password" id="password" required autofocus>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12"></div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>