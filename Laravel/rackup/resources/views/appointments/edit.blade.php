@extends('layouts.calendarLayouts')

@section('content')
    <div class="page-header">
        <h1>Edit Slot Details</h1>
    </div>


    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('calendar_events.update', $calendar_event['id']) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                {{--<div class="form-group">--}}
                    {{--<label for="nome">ID</label>--}}
                    {{--<p class="form-control-static">{{$calendar_event['id']}}</p>--}}
                {{--</div>--}}
                {{--<div class="form-group">--}}
                    {{--<label for="teacherId">TEACHER ID</label>--}}
                    {{--<p class="form-control-static">{{$calendar_event['teacherId']}}</p>--}}
                {{--</div>--}}
                <div class="form-group">
                    <label for="teacherName">TEACHER NAME</label>
                    <p class="form-control-static">{{$calendar_event['teacherName']}}</p>
                </div>
                {{--<div class="form-group">--}}
                    {{--<label for="title">TITLE</label>--}}
                    {{--<input type="text" name="title" class="form-control" value="{{$calendar_event['title']}}"/>--}}
                {{--</div>--}}
                <div class="form-group">
                    <label for="start">START</label>
                    <input type="DATETIME" name="start" class="form-control" value="{{$calendar_event['start']}}"/>
                </div>
                <div class="form-group">
                    <label for="end">END</label>
                    <input type="DATETIME" name="end" class="form-control" value="{{$calendar_event['end']}}"/>
                </div>

                <a class="btn btn-default" href="{{ route('calendar_events.index') }}">Back</a>
                <button class="btn btn-primary" type="submit" >Save</button>
            </form>
        </div>
    </div>


@endsection
