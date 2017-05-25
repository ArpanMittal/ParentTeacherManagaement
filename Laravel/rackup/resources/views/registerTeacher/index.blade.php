@extends('layouts.app')

@section('content')


    <div class="page-header">
        <h1>Teacher Details</h1>
    </div>

    <div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>
    <div>
        @if (session('failure'))
            <div class="alert alert-danger">
                {{ session('failure') }}
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Teacher's Name</th>
                    <th>Gender</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Username</th>
                    <th class="text-right">OPTIONS</th>
                </tr>
                </thead>

                <tbody>

                @foreach($teacher_details as $teacher_detail)
                    <tr>
                        <td>{{$teacher_detail['teacherId']}}</td>
                        <td>{{$teacher_detail['teacherName']}}</td>
                        <td>{{$teacher_detail['teacherGender']}}</td>
                        <td>{{$teacher_detail['contact']}}</td>
                        <td>{{$teacher_detail['address']}}</td>
                        <td>{{$teacher_detail['username']}}</td>

                        <td>
                            <a class="btn btn-primary" href="{{ route('registerTeacher.show', $teacher_detail['teacherId']) }}">View</a>
                        </td>
                        <td>
                            <a class="btn btn-warning " href="{{ route('registerTeacher.edit', $teacher_detail['teacherId']) }}">Edit</a>
                        </td>
                        <td>
                            <form action="{{ route('registerTeacher.destroy', $teacher_detail['teacherId']) }}" method="POST" style="display: inline;"
                                  onsubmit="if(confirm('Delete? Are you sure?'))
                                  {
                                  return true
                                  }
                                  else
                                  {
                                  return false
                                  };">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>

                @endforeach

                </tbody>
            </table>

            <a class="btn btn-success" href="{{ route('registerTeacher.create') }}">Register New Teacher</a>
        </div>
    </div>


@endsection