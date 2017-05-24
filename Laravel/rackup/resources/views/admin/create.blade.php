@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Add new Grade</h1>
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

            <form action="{{ route('admin.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">


                <div class="form-group {{$errors->has('gradeId')?'has-error':''}}">
                    <label for="gradeId">Grade</label>
                    <div>
                        <input type="radio" name="gradeId"value="Playgroup"/>Playgroup
                        <input type="radio" name="gradeId"value="Nursery"/>Nursery
                        <input type="radio" name="gradeId"value="J.K.G."/>J.K.G.
                        <input type="radio" name="gradeId"value="S.K.G."/>S.K.G.
                        @if ($errors->has('gradeId'))
                            <span class="help-block">
                                <strong>{{ $errors->first('gradeId')}}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div></div>

                <div class="form-group {{$errors->has('roomNo')?'has-error':''}}">
                    <label for="roomNo">Room Number</label>
                    <div>
                        <input type="text" name="roomNo" id="roomNo" value="{{ Input::old('roomNo') }}" required autofocus>
                        @if ($errors->has('roomNo'))
                            <span class="help-block">
                                <strong>{{ $errors->first('roomNo') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <button class="btn btn-primary" type="submit" >Create</button>
            </form>
        </div>
    </div>


@endsection
