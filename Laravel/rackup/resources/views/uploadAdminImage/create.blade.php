@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Upload New Image</h1>
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
                        {{--@foreach($students as $student)--}}
                            {{--<option value = "{{$student['id']}}" >{{$student['name']}}_{{$student['id']}}</option>--}}
                        {{--@endforeach--}}
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
                <input id="fileEntries" type="file" name="fileEntries" required>
                <br>

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
                {{--<textarea id="fileEditor" name="fileEditor"></textarea>--}}

                <button id="uploadFile" class="btn btn-primary" type="submit">Save</button>
                <a class="btn btn-default" href="{{ route('uploadPdf.index') }}">Back</a>

            </form>
        </div>
    </div>


@endsection