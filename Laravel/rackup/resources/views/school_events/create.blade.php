@extends('layouts.calendarLayouts')

@section('content')
    <div class="page-header">
        <h1>New School Event</h1>
    </div>


    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('school_events.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

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
                <a class="btn btn-default" href="{{ route('school_events.index') }}">Index</a>
                <button class="btn btn-primary" type="submit" >Add New Event</button>
            </form>
        </div>
    </div>


@endsection
