@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Add new Grade</h1>
    </div>

    @if (session('failure'))
        <div class="alert alert-danger">
            {{ session('failure') }}
        </div>
    @endif


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

            <form action="{{ route('postAssignTeacher') }}" method="POST">
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

                <div class="form-group {{$errors->has('gradeId')?'has-error':''}}">
                    <label for="teacher">Grade</label>
                    <select  id="gradeId" name="gradeId" class="form-control" required autofocus>
                        @foreach($grades as $grade)
                            <option value = "{{$grade['gradeId']}}" >{{$grade['gradeName']}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('gradeId'))
                        <span class="help-block">
                            <strong>{{ $errors->first('gradeId') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group {{$errors->has('isClassTeacher')?'has-error':''}}">
                    <label for="isClassTeacher">Class Teacher</label>
                    <div>
                        <input type="radio" name="isClassTeacher"value="1"/>Yes
                        <input type="radio" name="isClassTeacher"value="0"/>No
                        @if ($errors->has('isClassTeacher'))
                            <span class="help-block">
                                <strong>{{ $errors->first('isClassTeacher') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>




                <button class="btn btn-primary" type="submit" >Assign</button>
            </form>
        </div>
    </div>


@endsection
