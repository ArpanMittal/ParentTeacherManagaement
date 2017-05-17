@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Edit User Details</h1>
    </div>


    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('admin.update', $parent_details['parentId']) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">


                <div class="form-group">
                    <label for="studentId">Student Id</label>
                    <p class="form-control-static">{{$parent_details['studentId']}}</p>
                </div>
                <div class="form-group">
                    <label for="studentName">Student Name</label>
                    <input type="text" name="studentName" class="form-control" value="{{$parent_details['studentName']}}"/>
                </div>
                <div class="form-group">
                    <label for="studentGender">Student Gender</label>
                    <input type="radio" name="parentGender"value="F"/>Female
                    <input type="radio" name="parentGender"value="M"/>Male
                </div>
                <div class="form-group">
                    <label for="dob">DOB</label>
                    <input type="date" name="dob" class="form-control" value="{{$parent_details['dob']}}"/>
                </div>
                <div class="form-group">
                    <label for="gradeName">Grade</label>
                    <p class="form-control-static">{{$parent_details['gradeName']}}</p>
                </div>
                <div class="form-group">
                    <label for="parentName">Parent Name</label>
                    <input type="text" name="parentName" class="form-control" value="{{$parent_details['parentName']}}"/>
                </div>
                <div class="form-group">
                    <label for="parentGender">Parent Gender</label>
                    <p class="form-control-static">{{$parent_details['parentGender']}}</p>
                </div>
                <div class="form-group">
                    <label for="contact">Contact</label>
                    <input type="text" name="contact" class="form-control" value="{{$parent_details['contact']}}"/>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" class="form-control" value="{{$parent_details['address']}}"/>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <p class="form-control-static">{{$parent_details['username']}}</p>
                </div>

                <a class="btn btn-default" href="{{ route('admin.index') }}">Back</a>
                <button class="btn btn-primary" type="submit" >Save</button>
            </form>
        </div>
    </div>


@endsection
