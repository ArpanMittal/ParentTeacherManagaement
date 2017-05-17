@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Show User Details</h1>
    </div>


    <div class="row">
        <div class="col-md-12">

            <form action="#">
                <div class="form-group">
                    <label for="studentId">Student Id</label>
                    <p class="form-control-static">{{$parent_details['studentId']}}</p>
                </div>
                <div class="form-group">
                    <label for="studentName">Student Name</label>
                    <p class="form-control-static">{{$parent_details['studentName']}}</p>
                </div>
                <div class="form-group">
                    <label for="studentGender">Student Gender</label>
                    <p class="form-control-static">{{$parent_details['studentGender']}}</p>
                </div>
                <div class="form-group">
                    <label for="dob">DOB</label>
                    <p class="form-control-static">{{$parent_details['dob']}}</p>
                </div>
                <div class="form-group">
                    <label for="gradeName">Grade</label>
                    <p class="form-control-static">{{$parent_details['gradeName']}}</p>
                </div>
                <div class="form-group">
                    <label for="parentName">Parent Name</label>
                    <p class="form-control-static">{{$parent_details['parentName']}}</p>
                </div>
                <div class="form-group">
                    <label for="parentGender">Parent Gender</label>
                    <p class="form-control-static">{{$parent_details['parentGender']}}</p>
                </div>
                <div class="form-group">
                    <label for="contact">Contact</label>
                    <p class="form-control-static">{{$parent_details['contact']}}</p>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <p class="form-control-static">{{$parent_details['address']}}</p>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <p class="form-control-static">{{$parent_details['username']}}</p>
                </div>
            </form>



            <a class="btn btn-default" href="{{ route('admin.index') }}">Back</a>
            <a class="btn btn-warning" href="{{ route('admin.edit', $parent_details['parentId']) }}">Edit</a>
            <form action="#/$parent_details['parentId']"
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