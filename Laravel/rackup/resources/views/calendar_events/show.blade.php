@extends('layouts.calendarLayouts')

@section('content')
    <div class="page-header">
        <h1>Slot Details</h1>
    </div>

    <div class="row">
        <div class="col-md-12">

            <form action="#">
                <div class="form-group">
                    <label for="nome">ID</label>
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
                <div class="form-group">
                    <label for="start">START TIME</label>
                    <p class="form-control-static">{{$calendar_event['startTime']}}</p>
                </div>
                <div class="form-group">
                    <label for="end">END TIME</label>
                    <p class="form-control-static">{{$calendar_event['endTime']}}</p>
                </div>

            <a class="btn btn-default" href="{{ route('calendar_events.index') }}">Back</a>
            <a class="btn btn-warning" href="{{ route('calendar_events.edit', $calendar_event['id']) }}">Edit</a>
            <form action="#/$calendar_event['id']" method="DELETE" style="display: inline;"
                  onsubmit="if(confirm('Delete? Are you sure?'))
                   { return true }
                   else {return false };">
                <button class="btn btn-danger" type="submit">Delete</button>
            </form>
            </form>
        </div>
    </div>


@endsection