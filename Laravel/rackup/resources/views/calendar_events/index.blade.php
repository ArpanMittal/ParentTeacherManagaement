@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Free Slots</h1>
    </div>


    <div class="row">
        <div class="col-md-12">

            <div>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
            </div>

           <div>
            @if (session('failure'))
                <div class="alert alert-danger">
                    {{ session('failure') }}
                </div>
            @endif
        </div>

            <table class="table table-striped">
                <thead>
                <tr>
                    {{--<th>ID</th>--}}
                    {{--<th>TEACHER ID</th>--}}
                    <th>TEACHER NAME</th>
                    <th>DAY</th>
                    <th>START TIME</th>
                    <th>END TIME</th>
                    <th class="text-right">OPTIONS</th>
                </tr>
                </thead>

                <tbody>

                @foreach($calendar_events as $calendar_event)
                    <tr>
                        {{--<td>{{$calendar_event['id']}}</td>--}}
                        {{--<td>{{$calendar_event['teacherId']}}</td>--}}
                        <td>{{$calendar_event['teacherName']}}</td>
                        <td>{{$calendar_event['day']}}</td>
                        <td>{{$calendar_event['startTime']}}</td>
                        <td>{{$calendar_event['endTime']}}</td>

                        <td class="text-right">
                            <a class="btn btn-primary" href="{{ route('calendar_events.show', $calendar_event['id']) }}">View</a>
                            <a class="btn btn-warning" href="{{ route('calendar_events.edit', $calendar_event['id']) }}">Edit</a>
                            <form action="{{ route('calendar_events.destroy', $calendar_event['id']) }}" method="POST"
                                  style="display: inline;"
                                  onsubmit="if(confirm('Delete? Are you sure?'))
                                   { return true }
                                   else {return false };">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>

                @endforeach

                </tbody>
            </table>

            <a class="btn btn-success" href="{{ route('calendar_events.create') }}">Add Slots</a>
        </div>
    </div>


@endsection