@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Edit Slot Details</h1>
    </div>


    <div class="row">
        <div class="col-md-12">

            <form action="{{url('calendar_events',['teacherId'=>$calendar_event['teacherId'],'id'=>$calendar_event['id']])}}" method="post">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">


                <div class="form-group">
                    <label for="teacherName">TEACHER NAME</label>
                    <p class="form-control-static">{{$calendar_event['teacherName']}}</p>
                </div>
                <div class="form-group">
                    <label for="day">DAY</label>
                    <p class="form-control-static">{{$calendar_event['day']}}</p>
                </div>
                <div class="form-group {{$errors->has('start')?'has-error':''}}">
                    <label for="startTime">START TIME</label>
                    <input type="time" id="start" name="start" class="form-control" value="{{$calendar_event['startTime']}}" onClick="Clear1(this.id);"/>
                    @if ($errors->has('start'))
                        <span class="help-block">
                            <strong>{{ $errors->first('start') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group {{$errors->has('end')?'has-error':''}}">
                    <label for="endTime">END TIME</label>
                    <input type="time" id="end" name="end" class="form-control" value="{{$calendar_event['endTime']}}" onClick="Clear1(this.id);"/>
                    @if ($errors->has('end'))
                        <span class="help-block">
                            <strong>{{ $errors->first('end') }}</strong>
                        </span>
                    @endif
                </div>

                <a class="btn btn-default" href="{{ route('home') }}">Home</a>
                <button class="btn btn-primary" type="submit" >Save</button>
            </form>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"
            integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>

    <script type="text/javascript">
        var click = 0;
        function Clear1(id){
            click += 1;
            if ((click == 1 && id=="start") || (click==1 && id=="end")) {
                document.getElementById('start').value = "";
                document.getElementById('end').value = "";
            }
        }
    </script>

@endsection
