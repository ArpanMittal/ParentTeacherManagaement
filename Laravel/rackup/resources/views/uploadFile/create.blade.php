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

            <form id="uploadFileForm" action="{{ route('uploadPdf.store')}}"  enctype="multipart/form-data" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token()}}">

                <div class="form-group {{$errors->has('studentId')?'has-error':''}}">
                    <label for="studentId">Upload File for</label>
                    <select  id="studentId" name="studentId" class="form-group" onchange="if (this.value=='grade'){
                                this.form['gradeId'].style.visibility='visible'
                                }
                                else {
                                this.form['gradeId'].style.visibility='hidden'
                                };" required autofocus>
                        <option value="school">School</option>
                        <option value="grade">Grade</option>
                        @foreach($students as $student)
                            <option value = "{{$student['id']}}" >{{$student['name']}}_{{$student['id']}}</option>
                        @endforeach
                    </select>
                    <select  id="gradeId" name="gradeId" class="form-group" style="visibility: hidden" required autofocus>
                        @foreach($grades as $grade)
                            <option value = "{{$grade['gradeId']}}" >{{$grade['gradeName']}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('studentId'))
                        <span class="help-block">
                    <strong>{{ $errors->first('studentId') }}</strong>
                </span>
                    @endif
                </div>

                    {{--<div id="grade" name="grade" class="form-group {{$errors->has('studentId')?'has-error':''}}" style="visibility: hidden">--}}
                        {{--<label for="gradeId">Grade</label>--}}
                        {{----}}
                        {{--@if ($errors->has('gradeId'))--}}
                            {{--<span class="help-block">--}}
                            {{--<strong>{{ $errors->first('gradeId') }}</strong>--}}
                        {{--</span>--}}
                        {{--@endif--}}
                    {{--</div>--}}


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
                {{--<div class="form-group {{$errors->has('pdfCover')?'has-error':''}}">--}}
                    {{--<label for="pdfCover"  class="control-label">PDF cover</label>--}}
                    {{--<div>--}}
                        {{--<input id="pdfCover" type="file" name="pdfCover" required>--}}
                        {{--@if ($errors->has('pdfCover'))--}}
                            {{--<span class="help-block">--}}
                        {{--<strong>{{ $errors->first('pdfCover') }}</strong>--}}
                    {{--</span>--}}
                        {{--@endif--}}
                    {{--</div>--}}
                {{--</div>--}}
                <textarea id="fileEditor" name="fileEditor"></textarea>

                <button id="uploadFile" class="btn btn-primary" type="submit">Save</button>
                <a class="btn btn-default" href="{{ route('uploadPdf.index') }}">Back</a>

            </form>
        </div>
    </div>




    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js"></script>


    {{--<script type="text/javascript">--}}
        {{--var flag = 0;--}}
    {{--</script>--}}
    {{--<script type="text/javascript">--}}
        {{--function validate() {--}}
            {{--var filename=document.getElementById('fileEntries').value;--}}
            {{--var fileExtension=filename.substr(filename.lastIndexOf('.')+1).toLowerCase();--}}
            {{--var pdfCover = document.getElementById('pdfCover').value();--}}
            {{--var pdfCoverExtension=pdfCover.substr(pdfCover.lastIndexOf('.')+1).toLowerCase();--}}
            {{--//alert(extension);--}}
            {{--if(fileExtension=='pdf' && pdfCoverExtension=='jpg') {--}}
                {{--flag = 1;--}}
            {{--} else {--}}
                {{--flag =0;--}}
                {{--document.getElementById('invalidFormat').style.display='block';--}}
                {{--document.getElementById('status').style.display='none';--}}
            {{--}--}}

        {{--}--}}
    {{--</script>--}}

    <script src="{!! asset('/ckeditor_4.7.0_full/ckeditor/ckeditor.js') !!}">
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            CKEDITOR.replace("fileEditor",{
                enterMode : CKEDITOR.ENTER_BR,
                shiftEnterMode : CKEDITOR.ENTER_P
            });
        })
    </script>

    <script type="text/javascript">
        /* Upload file*/
        $("#uploadFile").click(function(e){
            e.preventDefault();
            var form_action = $("#uploadFileForm").attr("action");
            var studentId = $("#studentId").val();
            var gradeId = $('#gradeId').val();
            var  title = $("#title").val();
            var  description = $("#description").val();
            var fileEntry = CKEDITOR.instances.fileEditor.getData();
            if(!studentId){
                toastr.error('Student Id required', 'Failure Alert', {timeOut: 5000});
            }
            else if(!title.trim().length){
                toastr.error('Title is required', 'Failure Alert', {timeOut: 5000});

            }
            else if(!description.trim().length){
                toastr.error('Description is required', 'Failure Alert', {timeOut: 5000});
            }
            else if(!fileEntry.trim().length){
                toastr.error('File is empty', 'Failure Alert', {timeOut: 5000});
            }
           else{
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    url: form_action,
                    data:{studentId:studentId, gradeId:gradeId,title:title, description:description,fileEntry:fileEntry},
                    success: function( data ) {
//                        alert('success');
//                        alert(JSON.stringify(data));
                        toastr.success('File uploaded', 'Success Alert', {timeOut: 5000});
                        window.location.reload();
                    },
                    error: function(data) {
//                        alert('error');
//                        alert(JSON.stringify(data));
                        toastr.error('Cannot upload file','Failure Alert', {timeOut: 5000});
                        window.location.reload();
                    }
                });

            }
        });
    </script>
@endsection
