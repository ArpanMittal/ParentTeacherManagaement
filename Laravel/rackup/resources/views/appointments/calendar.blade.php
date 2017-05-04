@extends('layouts.calendarLayouts')

@section('content')
    {!! $calendar->calendar() !!}
@stop

@section('scripts')
    {!! $calendar->script() !!}
{@stop