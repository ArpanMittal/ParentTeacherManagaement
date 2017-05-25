@extends('layouts.calendarLayouts')

@section('content')
    <div class="page-header">
        <h1>Free Slot Details</h1>
    </div>

    <div class="row">
        <div class="col-md-12">

            <form action="#">
                {{--<div class="form-group">--}}
                    {{--<label for="nome">ID</label>--}}
                    {{--<p class="form-control-static">{{$calendar_event['id']}}</p>--}}
                {{--</div>--}}
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

                <a class="btn btn-default" href="{{ route('home') }}">Home</a>

            </form>
        </div>
    </div>


@endsection