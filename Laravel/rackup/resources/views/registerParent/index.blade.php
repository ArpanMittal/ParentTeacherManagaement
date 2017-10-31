@extends('layouts.app')

@section('content')


    <div class="page-header">
        <h1>Student Details</h1>
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
                    <th>Student Name</th>
                    <th>Student Gender</th>
                    <th>DOB</th>
                    <th>Grade Name</th>
                    <th>Father's Name</th>
                    <th>Mother's Name</th>
                    <th>Primary Contact</th>
                    <th>Secondary Contact</th>
                    <th>Address</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th class="text-right">OPTIONS</th>
                </tr>
                </thead>

                <tbody>

                @foreach($parent_details as $parent_detail)
                    <tr>
                        <td>{{$parent_detail['studentName']}}</td>
                        <td>{{$parent_detail['studentGender']}}</td>
                        <td>{{$parent_detail['dob']}}</td>
                        <td>{{$parent_detail['gradeName']}}</td>
                        <td>{{$parent_detail['fatherName']}}</td>
                        <td>{{$parent_detail['motherName']}}</td>
                        <td>{{$parent_detail['contact']}}</td>
                        <td>{{$parent_detail['secondaryContact']}}</td>
                        <td>{{$parent_detail['address']}}</td>
                        <td>{{$parent_detail['username']}}</td>
                        <td>{{$parent_detail['password']}}</td>
                        <td>
                            <a class="btn btn-primary" href="{{ route('registerParent.show', $parent_detail['parentId']) }}">View</a>
                        </td>
                        <td>
                            <a class="btn btn-warning " href="{{ route('registerParent.edit', $parent_detail['parentId']) }}">Edit</a>
                        </td>

                        <td>
                            <form action="{{ route('registerParent.destroy', $parent_detail['parentId']) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?'))
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

            <a class="btn btn-success" href="{{ route('registerParent.create') }}">Create New User</a>
        </div>
    </div>


@endsection