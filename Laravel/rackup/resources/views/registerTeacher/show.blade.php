@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Show Teacher Details</h1>
        <img src="{{$teacherDetails['profilePhoto']}}" alt="HTML5 Icon" style="width:128px;height:128px;">
    </div>


    <div class="row">
        <div class="col-md-12">

            <form action="#">
                {{--<div class="form-group">--}}
                    {{--<label for="teacherId">Teacher Id</label>--}}
                    {{--<p class="form-control-static">{{$teacher_details['teacherId']}}</p>--}}
                {{--</div>--}}
                <div class="form-group">
                    <label for="username">Username</label>
                    <p class="form-control-static">{{$teacher_details['username']}}</p>
                </div>
                <div class="form-group">
                    <label for="teacherName">Teacher's Name</label>
                    <p class="form-control-static">{{$teacher_details['teacherName']}}</p>
                </div>
                <div class="form-group">
                    <label for="teacherGender">Gender</label>
                    <p class="form-control-static">{{$teacher_details['teacherGender']}}</p>
                </div>
                <div class="form-group">
                    <label for="contact">Contact</label>
                    <p class="form-control-static">{{$teacher_details['contact']}}</p>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <p class="form-control-static">{{$teacher_details['address']}}</p>
                </div>
            </form>

            <a class="btn btn-default" href="{{ route('registerTeacher.index') }}">Back</a>
            <a class="btn btn-warning" href="{{ route('registerTeacher.edit', $teacher_details['teacherId']) }}">Edit</a>
            <form action="#/$teacher_details['teacherId']"
                  method="DELETE"
                  style="display: inline;"
                  onsubmit="if(confirm('Delete? Are you sure?'))
                  { return true }
                  else {return false };">
                <button class="btn btn-danger" type="submit">Delete</button>
            </form>
        </div>
    </div>


@endsection