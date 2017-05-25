@extends('layouts.calendarLayouts')

@section('content')
    <div class="page-header">
        <h1>School Events</h1>
    </div>

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

    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>EVENT TYPE</th>
                    <th>TITLE</th>
                    <th>START</th>
                    <th>END</th>
                    <th class="text-right">OPTIONS</th>
                </tr>
                </thead>

                <tbody>

                @foreach($school_events as $school_event)
                    <tr>
                        <td>{{$school_event->eventType}}</td>
                        <td>{{$school_event->title}}</td>
                        <td>{{$school_event->start}}</td>
                        <td>{{$school_event->end}}</td>
                        <td class="text-right">
                            <a class="btn btn-primary" href="{{ route('school_events.show', $school_event->id) }}">View</a>

                            <a class="btn btn-warning " href="{{ route('school_events.edit', $school_event->id) }}">Edit</a>

                            <form action="{{ route('school_events.destroy', $school_event->id) }}" method="POST" style="display: inline;"
                                  onsubmit="if(confirm('Delete? Are you sure?'))
                                  {
                                  return true
                                  }
                                  else
                                  {
                                  return false
                                  };">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>

                @endforeach

                </tbody>
            </table>

            <a class="btn btn-success" href="{{ route('school_events.create') }}">Add New Event</a>
        </div>
    </div>


@endsection