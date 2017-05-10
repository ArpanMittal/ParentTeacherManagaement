@extends('layouts.calendarLayouts')

@section('content')
    <div class="page-header">
        <h1>Edit Slot Details</h1>
    </div>


    <div class="row">
        <div class="col-md-12">

            <form action="{{url('calendar_events',['teacherId'=>$calendar_event['teacherId'],'id'=>$calendar_event['id']])}}" method="post">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                    <label for="id">ID</label>
                    <p class="form-control-static">{{$calendar_event['id']}}</p>
                </div>
                <div class="form-group">
                    <label for="teacherId">TEACHER ID</label>
                    <p class="form-control-static">{{$calendar_event['teacherId']}}</p>
                </div>
                <div class="form-group">
                    <label for="teacherName">TEACHER NAME</label>
                    <p class="form-control-static">{{$calendar_event['teacherName']}}</p>
                </div>
                <div class="form-group">
                    <label for="title">TITLE</label>
                    <p class="form-control-static">{{$calendar_event['title']}}</p>
                </div>
                <div class="form-group">
                    <label for="day">DAY</label>
                    <p class="form-control-static">{{$calendar_event['day']}}</p>
                </div>
                <div class="form-group {{$errors->has('start')?'has-error':''}}">
                    <label for="startTime">START TIME</label>
                    <input type="time" id="start" name="start" class="form-control" value="{{$calendar_event['startTime']}}"/>
                    @if ($errors->has('start'))
                        <span class="help-block">
                            <strong>{{ $errors->first('start') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group {{$errors->has('end')?'has-error':''}}">
                    <label for="endTime">END TIME</label>
                    <input type="time" id="end" name="end" class="form-control" value="{{$calendar_event['endTime']}}"/>
                    @if ($errors->has('end'))
                        <span class="help-block">
                            <strong>{{ $errors->first('end') }}</strong>
                        </span>
                    @endif
                </div>

                <a class="btn btn-default" href="{{ route('calendar_events.index') }}">Index</a>
                <button class="btn btn-primary" type="submit" >Save</button>
            </form>
        </div>
    </div>


@endsection
