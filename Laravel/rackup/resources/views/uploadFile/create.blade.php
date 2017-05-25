@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Upload New File</h1>
    </div>

    <div id="invalidFormat" class="alert alert-danger" style="display:none;">
        Invalid File format.Upload only pdf files and jpg pdf cover photos.
    </div>

    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('uploadPdf.store') }}" enctype="multipart/form-data" method="POST" onsubmit="return validate()">
                <input type="hidden" name="_token" value="{{ csrf_token()}}">

                <div class="form-group {{$errors->has('studentId')?'has-error':''}}">
                    <label for="studentId">Student</label>
                    <select  id="studentId" name="studentId" class="form-control" required autofocus>
                        @foreach($students as $student)
                            <option value = "{{$student['id']}}" >{{$student['name']}}_{{$student['id']}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('studentId'))
                        <span class="help-block">
                    <strong>{{ $errors->first('studentId') }}</strong>
                </span>
                    @endif
                </div>

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

                <div class="form-group {{$errors->has('description')?'has-error':''}}">
                    <label for="description"  class="control-label">Description</label>
                    <div>
                        <input type="text" name="description" id="description" value="{{ Input::old('description') }}" required autofocus>
                        @if ($errors->has('description'))
                            <span class="help-block">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                        @endif
                    </div>
                </div>
                <div class="form-group {{$errors->has('pdfCover')?'has-error':''}}">
                    <label for="pdfCover"  class="control-label">PDF cover</label>
                    <div>
                        <input id="pdfCover" type="file" name="pdfCover" required>
                        @if ($errors->has('pdfCover'))
                            <span class="help-block">
                        <strong>{{ $errors->first('pdfCover') }}</strong>
                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group {{$errors->has('fileEntries')?'has-error':''}}">
                    <label for="fileEntries"  class="control-label">PDF file</label>
                    <div>
                        <input id="fileEntries" type="file" name="fileEntries" required>
                        @if ($errors->has('fileEntries'))
                            <span class="help-block">
                        <strong>{{ $errors->first('fileEntries') }}</strong>
                    </span>
                        @endif
                    </div>
                </div>



                <input type="submit" id="upload" value="Upload" >

                <a class="btn btn-default" href="{{ route('uploadPdf.index') }}">Back</a>

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
            var fileExtension=filename.substr(filename.lastIndexOf('.')+1).toLowerCase();
            var pdfCover = document.getElementById('pdfCover').value();
            var pdfCoverExtension=pdfCover.substr(pdfCover.lastIndexOf('.')+1).toLowerCase();
            //alert(extension);
            if(fileExtension=='pdf' && pdfCoverExtension=='jpg') {
                flag = 1;
            } else {
                flag =0;
                document.getElementById('invalidFormat').style.display='block';
                document.getElementById('status').style.display='none';
            }

        }
    </script>

@endsection
