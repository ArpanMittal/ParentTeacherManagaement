@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Uploaded Content</h1>
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
                    <th>ID</th>
                    <th>Category</th>
                    <th>Content</th>
                    <th>Url</th>
                    <th>Grade</th>
                    <th class="text-right">OPTIONS</th>
                </tr>
                </thead>

                <tbody>

                @foreach($uploadedContentDetails as $uploadedContentDetail)
                    <tr>
                        <td>{{$uploadedContentDetail['contentId']}}</td>
                        <td>{{$uploadedContentDetail['categoryName']}}</td>
                        <td>{{$uploadedContentDetail['contentName']}}</td>
                        <td>{{$uploadedContentDetail['url']}}</td>
                        <td>{{$uploadedContentDetail['gradeName']}}</td>

                        <td class="text-right">
                            <a class="btn btn-primary" href="{{ route('upload.show', $uploadedContentDetail['contentId']) }}">View</a>
                            <a class="btn btn-warning" href="{{ route('upload.edit', $uploadedContentDetail['contentId']) }}">Edit</a>
                            <form action="{{ route('upload.destroy', $uploadedContentDetail['contentId']) }}" method="POST"
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

            <a class="btn btn-success" href="{{ route('uploadLink') }}">Upload New Link</a>
        </div>
    </div>


@endsection