@extends('layouts.calendarLayouts')

@section('content')
    <div class="page-header">
        <h1>Edit School Event</h1>
        <img src="{{$school_event['imageUrl']}}" alt="HTML5 Icon" style="width:128px;height:128px;">
    </div>


    <div class="row">
        <div class="col-md-12">

            <form action="{{ route('school_events.update', $school_event['id']) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                    <label for="eventType">EVENT TYPE</label>
                    <p class="form-control-static">{{$school_event['eventType']}}</p>
                </div>
                <div class="form-group">
                    <label for="title">TITLE</label>
                    <p class="form-control-static">{{$school_event['title']}}</p>
                </div>
                <div class="form-group {{$errors->has('startDate')?'has-error':''}}">
                    <label for="startDate">DATE</label>
                    <input type="date" name="startDate" class="form-control" value="{{$school_event['startDate']}}"/>
                    @if ($errors->has('startDate'))
                        <span class="help-block">
                            <strong>{{ $errors->first('startDate') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group {{$errors->has('startTime')?'has-error':''}}">
                    <label for="startTime">START TIME</label>
                    <input type="time" id="startTime" name="startTime" class="form-control" value="{{$school_event['startTime']}}" onClick="Clear1(this.id);"/>
                    @if ($errors->has('startTime'))
                        <span class="help-block">
                            <strong>{{ $errors->first('startTime') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group {{$errors->has('endTime')?'has-error':''}}">
                    <label for="endTime">END TIME</label>
                    <input type="time" id="endTime" name="endTime" class="form-control" value="{{$school_event['endTime']}}" onClick="Clear1(this.id);"/>
                    @if ($errors->has('endTime'))
                        <span class="help-block">
                            <strong>{{ $errors->first('endTime') }}</strong>
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
            if ((click == 1 && id=="startTime") || (click==1 && id=="endTime")) {
                document.getElementById('startTime').value = "";
                document.getElementById('endTime').value = "";
            }
        }
    </script>

@endsection
