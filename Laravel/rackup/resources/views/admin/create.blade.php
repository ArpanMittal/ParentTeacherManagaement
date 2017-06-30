@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Add New Grade</h1>
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
                        <select id="gradeId" name="gradeId" class="form-group"
                                onchange="if (this.value=='other'){
                                this.form['other'].style.visibility='visible'
                                }
                                else {
                                this.form['other'].style.visibility='hidden'
                                };"
                                required autofocus>
                            <option  value="Playgroup"/>Playgroup</option>
                            <option value="Nursery"/>Nursery</option>
                            <option value="J.K.G."/>J.K.G.</option>
                            <option value="S.K.G."/>S.K.G.</option>
                            <option value="other"/>Other</option>
                        </select>
                        <input type="textbox" name="other" style="visibility:hidden;"/>
                        @if ($errors->has('gradeId'))
                            <span class="help-block">
                                <strong>{{ $errors->first('gradeId')}}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div></div>

                <div class="form-group {{$errors->has('room_number')?'has-error':''}}">
                    <label for="room_number">Room Number</label>
                    <div>
                        <input type="text" name="room_number" id="room_number" value="{{ Input::old('room_number') }}" required autofocus>
                        @if ($errors->has('room_number'))
                            <span class="help-block">
                                <strong>{{ $errors->first('room_number') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <button class="btn btn-primary" type="submit" >Create</button>
            </form>
        </div>
    </div>

@endsection
