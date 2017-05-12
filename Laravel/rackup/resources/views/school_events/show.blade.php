@extends('layouts.calendarLayouts')

@section('content')
    <div class="page-header">
        <h1>View School Event</h1>
    </div>


    <div class="row">
        <div class="col-md-12">

            <form action="#">
                {{--<div class="form-group">--}}
                    {{--<label for="nome">ID</label>--}}
                    {{--<p class="form-control-static">{{$school_event->id}}</p>--}}
                {{--</div>--}}
                <div class="form-group">
                    <label for="title">TITLE</label>
                    <p class="form-control-static">{{$school_event->title}}</p>
                </div>
                <div class="form-group">
                    <label for="start">START</label>
                    <p class="form-control-static">{{$school_event->start}}</p>
                </div>
                <div class="form-group">
                    <label for="end">END</label>
                    <p class="form-control-static">{{$school_event->end}}</p>
                </div>
            </form>


            <a class="btn btn-default" href="{{ route('school_events.index') }}">Index</a>
            <a class="btn btn-warning" href="{{ route('school_events.edit', $school_event->id) }}">Edit</a>
            <form action="#/$school_event->id" method="DELETE" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };"><button class="btn btn-danger" type="submit">Delete</button></form>
        </div>
    </div>


@endsection