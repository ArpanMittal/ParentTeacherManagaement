<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.css">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">

            <div class="modal-body">
                <form data-toggle="validator" action="{{ route('insertAppointmentsSlots') }}" method="POST">
                    <div class="form-group">
                        <label class="control-label" for="teacherName">Teacher Name</label>
                        <select  id="teacherId" name="teacherId" class="form-control">
                            @foreach($teacherData as $teacher)
                                <option value = "{{$teacher['id']}}" >{{$teacher['name']}}_{{$teacher['id']}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="duration">Duration</label>
                       <select  id="duration" name="duration" class="form-control">
                            @foreach($durations as $duration)
                                <option value = "{{$duration}}" >{{$duration}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="day">Day</label>
                        <select  id="day" name="day" class="form-control">
                            @foreach($days as $day)
                                <option value = "{{$day}}" >{{$day}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="time">Time</label>
                        <input type="time" name="time" class="form-control" data-error="Please enter start time" required />
                        <div class="help-block with-errors"></div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn crud-submit btn-success">Book Slots</button>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                </form>
            </div>

        </div>
    </div>

</div>



</body>
</html>