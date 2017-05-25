@extends('layouts.calendarLayouts')

@section('content')
    <div class="page-header">
        <h1>View File Details</h1>
    </div>


    <div class="row">
        <div class="col-md-12">

                <div class="form-group">
                    <label for="title">TITLE</label>
                    <p class="form-control-static">{{$uploadedFiles['title']}}</p>
                </div>
                <div class="form-group">
                    <label for="start">URL</label>
                    <p class="form-control-static">{{$uploadedFiles['url']}}</p>
                </div>

            </form>

            <a class="btn btn-default" href="{{ route('uploadPdf.index') }}">Back</a>
            <form action="#/$uploadedFiles['fileId']" method="DELETE" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?'))
             {
             return true
             }
             else
             {
             return false
             };">
                <button class="btn btn-danger" type="submit">Delete</button></form>
        </div>
    </div>


@endsection