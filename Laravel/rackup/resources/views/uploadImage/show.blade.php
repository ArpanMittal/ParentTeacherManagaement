@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Image Details</h1>
    </div>

    <div class="row">
        <div class="col-md-12">

            <form action="#">
                <div class="form-group">
                    <label for="studentName">Student Name</label>
                    <p class="form-control-static">{{$uploadedImages['studentName']}}</p>
                </div>
                <div class="form-group">
                    <label for="title">Title</label>
                    <p class="form-control-static">{{$uploadedImages['title']}}</p>
                </div>
                <div class="form-group">
                    <label for="filePath">Image URL</label>
                    <p class="form-control-static"><a href="{{$uploadedImages['filePath']}}">{{$uploadedImage['filePath']}}</a>  </p>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <p class="form-control-static">{{$uploadedImages['description']}}</p>
                </div>

            <a class="btn btn-default" href="{{ route('uploadImage.index') }}">Back</a>
            <form action="#/$uploadedImage['imageId']" method="DELETE" style="display: inline;"
                  onsubmit="if(confirm('Delete? Are you sure?'))
                   { return true }
                   else {return false };">
                <button class="btn btn-danger" type="submit">Delete</button>
            </form>
            </form>
        </div>
    </div>


@endsection