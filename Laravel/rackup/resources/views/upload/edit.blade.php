@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Edit Content Details</h1>
    </div>


    <div class="row">
        <div class="col-md-12">

            <div>
                @if (session('failure'))
                    <div class="alert alert-danger">
                        {{ session('failure') }}
                    </div>
                @endif
            </div>

            <form action="{{ route('upload.update', $uploadedContentDetails['contentId']) }}" method="post">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                {{--<div class="form-group">--}}
                    {{--<label for="id">ID</label>--}}
                    {{--<p class="form-control-static">{{$uploadedContentDetails['contentId']}}</p>--}}
                {{--</div>--}}
                <div class="form-group">
                    <label for="categoryName">Category</label>
                    <p class="form-control-static">{{$uploadedContentDetails['categoryName']}}</p>
                </div>
                <div class="form-group">
                    <label for="contentName">Video Name</label>
                    <input type="text" id="contentName" name="contentName" class="form-control" value="{{$uploadedContentDetails['contentName']}}"/>
                </div>
                <div class="form-group">
                    <label for="url">URL</label>
                    <input type="url" id="url" name="url" class="form-control" value="{{$uploadedContentDetails['url']}}"/>
                </div>
                <div class="form-group">
                    <label for="gradeName">Grade</label>
                    {{--<input type="time" id="end" name="end" class="form-control" value="{{$calendar_event['endTime']}}"/>--}}
                    <p class="form-control-static">{{$uploadedContentDetails['gradeName']}}</p>
                </div>

                <a class="btn btn-default" href="{{ route('upload.index') }}">Back</a>
                <button class="btn btn-primary" type="submit" >Save</button>
            </form>
        </div>
    </div>


@endsection
