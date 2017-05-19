@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Uploaded Images</h1>
    </div>

    <div class="row">
        <div class="col-md-12">

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

            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Title</th>
                    <th>Image URL</th>
                    <th>Description</th>
                    <th class="text-right">OPTIONS</th>
                </tr>
                </thead>

                <tbody>

                @foreach($uploadedImages as $uploadedImage)
                    <tr>
                        <td>{{$uploadedImage['studentName']}}</td>
                        <td>{{$uploadedImage['title']}}</td>
                        <td>{{$uploadedImage['filePath']}}</td>
                        <td>{{$uploadedImage['description']}}<td>

                        <td class="text-right">
                            <a class="btn btn-primary" href="{{ route('uploadImage.show', $uploadedImage['imageId']) }}">View</a>
                            <form action="{{ route('uploadImage.destroy', $uploadedImage['imageId']) }}" method="POST"
                                  style="display: inline;"
                                  onsubmit="if(confirm('Delete? Are you sure?'))
                                   { return true }
                                   else {return false };">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>

                @endforeach

                </tbody>
            </table>

            <a class="btn btn-success" href="{{ route('uploadFile') }}">Upload New Image</a>
        </div>
    </div>


@endsection