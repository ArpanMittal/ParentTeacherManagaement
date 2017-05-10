@extends('layouts.calendarLayouts')

@section('content')
    <div class="page-header">
        <h1>Edit School Event</h1>
    </div>


    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('school_events.update', $school_event['id']) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                    <label for="nome">ID</label>
                    <p class="form-control-static">{{$school_event['id']}}</p>
                </div>
                <div class="form-group {{$errors->has('title')?'has-error':''}}">
                    <label for="title">TITLE</label>
                    <input type="text" name="title" class="form-control" value="{{$school_event['title']}}"/>
                    @if ($errors->has('title'))
                        <span class="help-block">
                            <strong>{{ $errors->first('title')}}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group {{$errors->has('startDate')?'has-error':''}}">
                    <label for="startDate">START DATE</label>
                    <input type="date" name="startDate" class="form-control" value="{{$school_event['startDate']}}"/>
                    @if ($errors->has('startDate'))
                        <span class="help-block">
                            <strong>{{ $errors->first('startDate') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group {{$errors->has('startTime')?'has-error':''}}">
                    <label for="startTime">START TIME</label>
                    <input type="time" name="startTime" class="form-control" value="{{$school_event['startTime']}}"/>
                    @if ($errors->has('startTime'))
                        <span class="help-block">
                            <strong>{{ $errors->first('startTime') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group {{$errors->has('endDate')?'has-error':''}}">
                    <label for="endDate">END DATE</label>
                    <input type="date" name="endDate" class="form-control" value="{{$school_event['endDate']}}"/>
                    @if ($errors->has('endDate'))
                        <span class="help-block">
                            <strong>{{ $errors->first('endDate') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group {{$errors->has('endTime')?'has-error':''}}">
                    <label for="endTime">END TIME</label>
                    <input type="time" name="endTime" class="form-control" value="{{$school_event['endTime']}}"/>
                    @if ($errors->has('endTime'))
                        <span class="help-block">
                            <strong>{{ $errors->first('endTime') }}</strong>
                        </span>
                    @endif
                </div>

                <a class="btn btn-default" href="{{ route('school_events.index') }}">Index</a>
                <button class="btn btn-primary" type="submit" >Save</button>
            </form>
        </div>
    </div>


@endsection
