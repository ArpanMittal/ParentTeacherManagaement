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

                        <label class="col-md-12 control-label"><h2>Teacher Details</h2></label>

                        <div class="form-group {{$errors->has('teacherName')?'has-error':''}}">
                            <label for="teacherName"  class="col-md-4 control-label">Teacher Name</label>
                            <div class="col-md-6">
                                <input type="text" name="teacherName" id="teacherName" value="{{ Input::old('teacherName') }}" required autofocus>
                                @if ($errors->has('teacherName'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('teacherName') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12"></div>

                        <div class="form-group {{$errors->has('teacherGender')?'has-error':''}}">
                            <label for="teacherGender"  class="col-md-4 control-label">Gender</label>
                            <div class="col-md-6">
                                <input type="radio" name="teacherGender"value="F"/>Female</br>
                                <input type="radio" name="teacherGender"value="M"/>Male</br>
                                @if ($errors->has('teacherGender'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('teacherGender') }}</strong>
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
                                <input type="radio" name="role"value="2"/>Parent</br>
                                <input type="radio" name="role"value="3"/>Principal</br>
                                <input type="radio" name="role"value="4"/>Teacher</br>
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