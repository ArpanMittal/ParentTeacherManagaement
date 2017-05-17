@extends('layouts.app')

@section('content')


    <div class="page-header">
        <h1>User Details</h1>
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
                    <th>Student Name</th>
                    <th>Student Gender</th>
                    <th>DOB</th>
                    <th>Grade Name</th>
                    <th>Parent Name</th>
                    <th>Parent Gender</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Username</th>
                    <th class="text-right">OPTIONS</th>
                </tr>
                </thead>

                <tbody>

                @foreach($parent_details as $parent_detail)
                    <tr>
                        <td>{{$parent_detail['studentId']}}</td>
                        <td>{{$parent_detail['studentName']}}</td>
                        <td>{{$parent_detail['studentGender']}}</td>
                        <td>{{$parent_detail['dob']}}</td>
                        <td>{{$parent_detail['gradeName']}}</td>
                        <td>{{$parent_detail['parentName']}}</td>
                        <td>{{$parent_detail['parentGender']}}</td>
                        <td>{{$parent_detail['contact']}}</td>
                        <td>{{$parent_detail['address']}}</td>
                        <td>{{$parent_detail['username']}}</td>

                        <td class="text-right">
                            <a class="btn btn-primary" href="{{ route('admin.show', $parent_detail['parentId']) }}">View</a>
                            <a class="btn btn-warning " href="{{ route('admin.edit', $parent_detail['parentId']) }}">Edit</a>
                            <form action="{{ route('admin.destroy', $parent_detail['parentId']) }}" method="POST" style="display: inline;"
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

            <a class="btn btn-success" href="{{ route('admin.create') }}">Create New User</a>
        </div>
    </div>


@endsection