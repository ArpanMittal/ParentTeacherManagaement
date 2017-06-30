@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Edit Student Details</h1>
    </div>

    @if (session('failure'))
        <div class="alert alert-danger">
            {{ session('failure') }}
        </div>
    @endif


    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('registerParent.update', $parent_details['parentId']) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group {{$errors->has('studentName')?'has-error':''}}">
                    <label for="studentName">Student Name</label>
                    <input type="text" name="studentName" class="form-control" value="{{$parent_details['studentName']}}"/>
                    @if ($errors->has('studentName'))
                        <span class="help-block">
                                <strong>{{ $errors->first('studentName') }}</strong>
                            </span>
                    @endif
                </div>
                <div class="form-group {{$errors->has('dob')?'has-error':''}}">
                    <label for="dob">DOB</label>
                    <input type="date" name="dob" class="form-control" value="{{$parent_details['dob']}}"/>
                    @if ($errors->has('dob'))
                        <span class="help-block">
                                <strong>{{ $errors->first('dob') }}</strong>
                            </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="gradeName">Grade</label>
                    <p class="form-control-static">{{$parent_details['gradeName']}}</p>
                </div>
                <div class="form-group {{$errors->has('fatherName')?'has-error':''}}">
                    <label for="fatherName">Father's Name</label>
                    <input type="text" name="fatherName" class="form-control" value="{{$parent_details['fatherName']}}"/>
                    @if ($errors->has('fatherName'))
                        <span class="help-block">
                                <strong>{{ $errors->first('fatherName') }}</strong>
                            </span>
                    @endif
                </div>
                <div class="form-group {{$errors->has('motherName')?'has-error':''}}">
                    <label for="motherName">Mother's Name</label>
                    <input type="text" name="motherName" class="form-control" value="{{$parent_details['motherName']}}"/>
                    @if ($errors->has('motherName'))
                        <span class="help-block">
                                <strong>{{ $errors->first('parentName') }}</strong>
                            </span>
                    @endif
                </div>
                <div class="form-group {{$errors->has('contact')?'has-error':''}}">
                    <label for="contact">Primary Contact</label>
                    <input type="text" name="contact" class="form-control" value="{{$parent_details['contact']}}"/>
                    @if ($errors->has('contact'))
                        <span class="help-block">
                                <strong>{{ $errors->first('contact') }}</strong>
                            </span>
                    @endif
                </div>
                <div class="form-group {{$errors->has('secondaryContact')?'has-error':''}}">
                    <label for="secondaryContact">Secondary Contact</label>
                    <input type="text" name="secondaryContact" class="form-control" value="{{$parent_details['secondaryContact']}}"/>
                    @if ($errors->has('secondaryContact'))
                        <span class="help-block">
                                <strong>{{ $errors->first('secondaryContact') }}</strong>
                            </span>
                    @endif
                </div>
                <div class="form-group {{$errors->has('address')?'has-error':''}}">
                    <label for="address">Address</label>
                    <input type="text" name="address" class="form-control" value="{{$parent_details['address']}}"/>
                    @if ($errors->has('address'))
                        <span class="help-block">
                                <strong>{{ $errors->first('address') }}</strong>
                            </span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <p class="form-control-static">{{$parent_details['username']}}</p>
                </div>

                <a class="btn btn-default" href="{{ route('registerParent.index') }}">Back</a>
                <button class="btn btn-primary" type="submit" >Save</button>
            </form>
        </div>
    </div>


@endsection
