@extends('layouts.calendarLayouts')
@section('content')
    <div class="page-header">
        <h1>Add Slots</h1>
    </div>


    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('appointments.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                    <label for="title">TITLE</label>
                    <input type="text" name="title" class="form-control" value=""/>
                </div>

                <div class="form-group">
                    <label for="title">TEACHER</label>
                    <select  id="teacherId" name="teacherId" class="form-control">
                        @foreach($teacherData as $teacher)
                            <option value = "{{$teacher['id']}}" >{{$teacher['name']}}_{{$teacher['id']}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="start">START</label>
                    <input type="DATETIME" name="start" class="form-control" value=""/>
                </div>
                <div class="form-group">
                    <label for="end">END</label>
                    <input type="DATETIME" name="end" class="form-control" value=""/>
                </div>


                <a class="btn btn-default" href="{{ route('appointments.index') }}">Back</a>
                <button class="btn btn-primary" type="submit" >Add Slots</button>
            </form>
        </div>
    </div>


@endsection
