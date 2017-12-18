@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Show Student Details</h1>
    </div>


    <div class="row">
        <div class="col-md-12">

            <form action="#">
                <div class="form-group">
                    <label for="studentName">Student's Name</label>
                    <p class="form-control-static">{{$parent_details['studentName']}}</p>
                </div>
                <div class="form-group">
                    <label for="studentGender">Student's Gender</label>
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
                    <label for="fatherName">Father's Name</label>
                    <p class="form-control-static">{{$parent_details['fatherName']}}</p>
                </div>
                <div class="form-group">
                    <label for="motherName">Mother's Name</label>
                    <p class="form-control-static">{{$parent_details['motherName']}}</p>
                </div>
                <div class="form-group">
                    <label for="contact">Primary Contact</label>
                    <p class="form-control-static">{{$parent_details['contact']}}</p>
                </div>
                <div class="form-group">
                    <label for="secondaryContact">Secondary Contact</label>
                    <p class="form-control-static">{{$parent_details['secondaryContact']}}</p>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <p class="form-control-static">{{$parent_details['address']}}</p>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <p class="form-control-static">{{$parent_details['username']}}</p>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <p class="form-control-static">{{$parent_details['password']}}</p>
                </div>
            </form>



            <a class="btn btn-default" href="{{ route('registerParent.index') }}">Back</a>
            <a class="btn btn-warning" href="{{ route('registerParent.edit', $parent_details['parentId']) }}">Edit</a>
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