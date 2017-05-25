@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Add Free Slots</h1>
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

            <form action="{{ route('calendar_events.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group {{$errors->has('teacherId')?'has-error':''}}">
                    <label for="teacher">TEACHER</label>
                    <select  id="teacherId" name="teacherId" class="form-control" required autofocus>
                        @foreach($teacherData as $teacher)
                            <option value = "{{$teacher['id']}}" >{{$teacher['name']}}_{{$teacher['id']}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('teacherId'))
                        <span class="help-block">
                            <strong>{{ $errors->first('teacherId') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group {{$errors->has('day')?'has-error':''}}" >
                    <label for="day">DAY</label>
                    <select  id="day" name="day" class="form-control" required autofocus>
                        @foreach($days as $day)
                            <option value = "{{$day}}" >{{$day}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('day'))
                        <span class="help-block">
                            <strong>{{ $errors->first('day') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group {{$errors->has('start')?'has-error':''}}">
                    <label for="start">START</label>
                    <input type="time" id="start" name="start" class="form-control"  value="{{ Input::old('start') }}" required autofocus />
                    @if ($errors->has('start'))
                        <span class="help-block">
                            <strong>{{ $errors->first('start') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group {{$errors->has('end')?'has-error':''}}" >
                    <label for="end">END</label>
                    <input type="time" id="end" name="end" class="form-control"  value="{{ Input::old('end') }}" required autofocus />
                    @if ($errors->has('end'))
                        <span class="help-block">
                            <strong>{{ $errors->first('end') }}</strong>
                        </span>
                    @endif
                </div>


                <a class="btn btn-default" href="{{ route('calendar_events.index') }}">Cancel</a>
                <button class="btn btn-primary" type="submit" >Add Slots</button>
            </form>
        </div>
    </div>

@endsection
