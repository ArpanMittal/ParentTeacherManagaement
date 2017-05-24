@extends('layouts.calendarLayouts')

@section('content')
    <div class="page-header">
        <h1>New School Event</h1>
    </div>


    <div class="row">
        <div class="col-md-12">

            <div id="invalidFormat" class="alert alert-danger" style="display:none;">
                Invalid File format.Upload only jpg images
            </div>

            <form action="{{ route('school_events.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validate()">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group {{$errors->has('end')?'has-error':''}}">
                    <label for="eventType">EVENT TYPE</label>
                    <select  id="eventType" name="eventType" class="form-control" required autofocus>
                        @foreach($eventTypes as $eventType)
                            <option value = "{{$eventType}}">{{$eventType}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('eventType'))
                        <span class="help-block">
                            <strong>{{ $errors->first('eventType') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group {{$errors->has('end')?'has-error':''}}">
                    <label for="title">TITLE</label>
                    <input type="text" name="title" class="form-control" value="{{ Input::old('title') }}" required autofocus/>
                    @if ($errors->has('title'))
                        <span class="help-block">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group {{$errors->has('end')?'has-error':''}}">
                    <label for="startDate">DATE</label>
                    <input type="date" name="startDate" class="form-control" value="{{ Input::old('startDate') }}" required autofocus/>
                    @if ($errors->has('startDate'))
                        <span class="help-block">
                            <strong>{{ $errors->first('startDate') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group {{$errors->has('end')?'has-error':''}}">
                    <label for="startTime">START TIME</label>
                    <input type="time" name="startTime" class="form-control" value="{{ Input::old('startTime') }}" required autofocus/>
                    @if ($errors->has('startTime'))
                        <span class="help-block">
                            <strong>{{ $errors->first('startTime') }}</strong>
                        </span>
                    @endif
                </div>

                {{--<div class="form-group {{$errors->has('end')?'has-error':''}}">--}}
                    {{--<label for="endDate">END DATE</label>--}}
                    {{--<input type="date" name="endDate" class="form-control" value="{{ Input::old('endDate') }}" required autofocus/>--}}
                    {{--@if ($errors->has('endDate'))--}}
                        {{--<span class="help-block">--}}
                                {{--<strong>{{ $errors->first('endDate') }}</strong>--}}
                        {{--</span>--}}
                    {{--@endif--}}
                {{--</div>--}}
                <div class="form-group {{$errors->has('endTime')?'has-error':''}}">
                    <label for="endTime">END TIME</label>
                    <input type="time" name="endTime" class="form-control" value="{{ Input::old('endTime') }}" required autofocus/>
                    @if ($errors->has('endTime'))
                        <span class="help-block">
                            <strong>{{ $errors->first('endTime') }}</strong>
                        </span>
                    @endif
                </div>
                <input id="fileEntries" type="file" name="fileEntries" required>
                <br>


                <a class="btn btn-default" href="{{ route('school_events.index') }}">Home</a>
                <button class="btn btn-primary" type="submit" >Add New Event</button>
            </form>
        </div>
    </div>


    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js"></script>

    <script>
        var flag = 0;
    </script>
    <script type="text/javascript">
            function validate() {
                var filename=document.getElementById('fileEntries').value;
                var extension=filename.substr(filename.lastIndexOf('.')+1).toLowerCase();
                //alert(extension);
                if(extension=='jpg') {
                    flag = 1;
                } else {
                    flag =0;
                    document.getElementById('invalidFormat').style.display='block';
                }
            }
    </script>

@endsection
