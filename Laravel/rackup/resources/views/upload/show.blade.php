@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Slot Details</h1>
    </div>

    <div class="row">
        <div class="col-md-12">

            <form action="#">
                <div class="form-group">
                    <label for="id">ID</label>
                    <p class="form-control-static">{{$uploadedContentDetails['contentId']}}</p>
                </div>
                <div class="form-group">
                    <label for="categoryName">Category</label>
                    <p class="form-control-static">{{$uploadedContentDetails['categoryName']}}</p>
                </div>
                <div class="form-group">
                    <label for="contentName">Video Name</label>
                    <p class="form-control-static">{{$uploadedContentDetails['contentName']}}</p>
                </div>
                <div class="form-group">
                    <label for="url">URL</label>
                    <p class="form-control-static">{{$uploadedContentDetails['url']}}</p>
                </div>
                <div class="form-group">
                    <label for="gradeName">Grade</label>
                    <p class="form-control-static">{{$uploadedContentDetails['gradeName']}}</p>
                </div>

            <a class="btn btn-default" href="{{ route('upload.index') }}">Back</a>
            <a class="btn btn-warning" href="{{ route('upload.edit', $uploadedContentDetails['contentId']) }}">Edit</a>
            <form action="#/$uploadedContentDetails['contentId']" method="DELETE" style="display: inline;"
                  onsubmit="if(confirm('Delete? Are you sure?'))
                   { return true }
                   else {return false };">
                <button class="btn btn-danger" type="submit">Delete</button>
            </form>
            </form>
        </div>
    </div>


@endsection