@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Edit User Details</h1>
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


                <div class="form-group">
                    <label for="studentId">Student Id</label>
                    <p class="form-control-static">{{$parent_details['studentId']}}</p>
                </div>
                <div class="form-group {{$errors->has('studentName')?'has-error':''}}">
                    <label for="studentName">Student Name</label>
                    <input type="text" name="studentName" class="form-control" value="{{$parent_details['studentName']}}"/>
                    @if ($errors->has('studentName'))
                        <span class="help-block">
                                <strong>{{ $errors->first('studentName') }}</strong>
                            </span>
                    @endif
                </div>
                {{--<div class="form-group">--}}
                    {{--<label for="studentGender">Student Gender</label>--}}
                    {{--<input type="radio" name="studentGender"value="F"/>Female--}}
                    {{--<input type="radio" name="studentGender"value="M"/>Male--}}
                {{--</div>--}}
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
                <div class="form-group {{$errors->has('parentName')?'has-error':''}}">
                    <label for="parentName">Parent Name</label>
                    <input type="text" name="parentName" class="form-control" value="{{$parent_details['parentName']}}"/>
                    @if ($errors->has('parentName'))
                        <span class="help-block">
                                <strong>{{ $errors->first('parentName') }}</strong>
                            </span>
                    @endif
                </div>
                {{--<div class="form-group">--}}
                    {{--<label for="parentGender">Parent Gender</label>--}}
                    {{--<input type="radio" name="parentGender"value="F"/>Female--}}
                    {{--<input type="radio" name="parentGender"value="M"/>Male--}}
                {{--</div>--}}
                <div class="form-group {{$errors->has('contact')?'has-error':''}}">
                    <label for="contact">Contact</label>
                    <input type="text" name="contact" class="form-control" value="{{$parent_details['contact']}}"/>
                    @if ($errors->has('contact'))
                        <span class="help-block">
                                <strong>{{ $errors->first('contact') }}</strong>
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
