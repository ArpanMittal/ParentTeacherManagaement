@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Upload New File</h1>
    </div>

    <div id="invalidFormat" class="alert alert-danger" style="display:none;">
        Invalid File format.Upload only pdf files.
    </div>

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('uploadPdf.store') }}" enctype="multipart/form-data" method="POST" onsubmit="return validate()">
                <input type="hidden" name="_token" value="{{ csrf_token()}}">

                <div class="form-group {{$errors->has('title')?'has-error':''}}">
                    <label for="title"  class="control-label">Title</label>
                    <div>
                        <input type="text" name="title" id="title" value="{{ Input::old('title') }}" required autofocus>
                        @if ($errors->has('title'))
                            <span class="help-block">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                        @endif
                    </div>
                </div>

                <input id="fileEntries" type="file" name="fileEntries">

                <input type="submit" id="upload" value="Upload" >

                <a class="btn btn-default" href="{{ route('uploadPdf.index') }}">Home</a>

            </form>
        </div>
    </div>


    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.js"></script>
    
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js"></script>


    <script type="text/javascript">
        var flag = 0;
    </script>
    <script type="text/javascript">
        function validate() {
            var filename=document.getElementById('fileEntries').value;
            var extension=filename.substr(filename.lastIndexOf('.')+1).toLowerCase();
            //alert(extension);
            if(extension=='pdf') {
                flag = 1;
            } else {
                flag =0;
                document.getElementById('invalidFormat').style.display='block';
                document.getElementById('status').style.display='none';
            }
        }
    </script>

@endsection
