@extends('layouts.calendarLayouts')
@section('content')
    <div class="page-header">
        <h1>Request Appointment</h1>
    </div>


    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('appointments.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                {{--<div class="form-group">--}}
                    {{--<label for="title">Title</label>--}}
                    {{--<input type="text" name="title" class="form-control" value=""/>--}}
                {{--</div>--}}

                <div class="form-group {{$errors->has('parentId')?'has-error':''}}">
                    <label for="parent">Parent</label>
                    <select  id="parentId" name="parentId" class="form-control"  required autofocus >
                        @foreach($parentData as $parent)
                            <option value = "{{$parent['id']}}" >{{$parent['name']}}_{{$parent['id']}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('parentId'))
                        <span class="help-block">
                            <strong>{{ $errors->first('parentId') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group {{$errors->has('startDate')?'has-error':''}}">
                    <label for="startDate">Start Date</label>
                    <input type="date" name="startDate" class="form-control" value=""  required autofocus/>
                    @if ($errors->has('startDate'))
                        <span class="help-block">
                            <strong>{{ $errors->first('startDate') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group {{$errors->has('startTime')?'has-error':''}}">
                    <label for="startTime">Start Time</label>
                    <input type="time" name="startTime" class="form-control" value=""  required autofocus/>
                    @if ($errors->has('startTime'))
                        <span class="help-block">
                            <strong>{{ $errors->first('startTime') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group {{$errors->has('endDate')?'has-error':''}}">
                    <label for="endDate">End Date</label>
                    <input type="date" name="endDate" class="form-control" value=""  required autofocus/>
                    @if ($errors->has('endDate'))
                        <span class="help-block">
                            <strong>{{ $errors->first('endDate') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group {{$errors->has('endTime')?'has-error':''}}">
                    <label for="endTime">End Time</label>
                    <input type="time" name="endTime" class="form-control" value=""  required autofocus/>
                    @if ($errors->has('endTime'))
                        <span class="help-block">
                            <strong>{{ $errors->first('endTime') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group {{$errors->has('appointmentReason')?'has-error':''}}">
                    <label for="appointmentReason">Reason for Appointment</label>
                    <input type="text" name="appointmentReason" class="form-control" value=""  required autofocus/>
                    @if ($errors->has('appointmentReason'))
                        <span class="help-block">
                            <strong>{{ $errors->first('appointmentReason') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group {{$errors->has('contactNo')?'has-error':''}}">
                    <label for="contact">Your Contact Number</label>
                    <input type="text" name="contactNo" class="form-control" value="{{$contactNo}}"  required autofocus/>
                    @if ($errors->has('contactNo'))
                        <span class="help-block">
                            <strong>{{ $errors->first('contactNo') }}</strong>
                        </span>
                    @endif
                </div>

                <a class="btn btn-default" href="{{ route('appointments.index') }}">Index</a>
                <button class="btn btn-primary" type="submit" >Send Request</button>
            </form>
        </div>
    </div>

@endsection
