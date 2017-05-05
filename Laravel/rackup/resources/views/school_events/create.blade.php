@extends('layouts.calendarLayouts')

@section('content')
    <div class="page-header">
        <h1>New School Event</h1>
    </div>


    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('school_events.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                    <label for="title">TITLE</label>
                    <input type="text" name="title" class="form-control" value=""/>
                </div>
                <div class="form-group">
                    <label for="startDate">START DATE</label>
                    <input type="date" name="startDate" class="form-control" value=""/>
                </div>
                <div class="form-group">
                    <label for="startTime">START TIME</label>
                    <input type="time" name="startTime" class="form-control" value=""/>
                </div>
                <div class="form-group">
                    <label for="endDate">END DATE</label>
                    <input type="date" name="endDate" class="form-control" value=""/>
                </div>
                <div class="form-group">
                    <label for="endTime">END TIME</label>
                    <input type="time" name="endTime" class="form-control" value=""/>
                </div>
                <a class="btn btn-default" href="{{ route('school_events.index') }}">Back</a>
                <button class="btn btn-primary" type="submit" >Add New Event</button>
            </form>
        </div>
    </div>


@endsection
