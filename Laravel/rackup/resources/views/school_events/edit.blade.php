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
                <div class="form-group">
                    <label for="title">TITLE</label>
                    <input type="text" name="title" class="form-control" value="{{$school_event['title']}}"/>
                </div>
                <div class="form-group">
                    <label for="startDate">START DATE</label>
                    <input type="date" name="startDate" class="form-control" value="{{$school_event['startDate']}}"/>
                </div>
                <div class="form-group">
                    <label for="startTime">START TIME</label>
                    <input type="time" name="startTime" class="form-control" value="{{$school_event['startTime']}}"/>
                </div>
                <div class="form-group">
                    <label for="endDate">END DATE</label>
                    <input type="date" name="endDate" class="form-control" value="{{$school_event['endDate']}}"/>
                </div>
                <div class="form-group">
                    <label for="endTime">END TIME</label>
                    <input type="time" name="endTime" class="form-control" value="{{$school_event['endTime']}}"/>
                </div>

                <a class="btn btn-default" href="{{ route('school_events.index') }}">Back</a>
                <button class="btn btn-primary" type="submit" >Save</button>
            </form>
        </div>
    </div>


@endsection
