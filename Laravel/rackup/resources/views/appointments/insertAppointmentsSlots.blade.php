@extends('layouts.app')
@section('content')
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
<!-- <div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h1>
                Free Slots
            </h1>
        </div>
    </div>
   <div class="row">
        <div class="col-sm-12">
            <table class="table table-hover" id="table">
                <thead>
                <tr data-tabullet-map="id">
                    <th width="50" data-tabullet-map="_index" data-tabullet-readonly="true">
                        No
                    </th>
                    <th data-tabullet-map="name">Name</th>
                    <th data-tabullet-map="day">Day</th>
                    <th data-tabullet-map="duration">Duration</th>
                    <th data-tabullet-map="time">Time</th>
                    <th width="50" data-tabullet-type="edit"></th>
                    <th width="50" data-tabullet-type="delete"></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>-->
</div>
<!--<script type="text/javascript">
</script>

<script>
    $("#table").tabullet({
        rowClass: '',
        columnClass: '',
        tableClass: 'table',
        textClass: 'form-control',
        editClass: 'btn btn-default',
        deleteClass: 'btn btn-danger',
        saveClass: 'btn btn-success',
        deleteContent: 'Delete',
        editContent: 'Edit',
        saveContent: 'Save',
        action: function () {
        }
    });

</script>
<script>
    $(function () {
        function resetTabullet() {
            $("#table").tabullet({
                data: source,
                action: function (mode, data) {
                    console.dir(mode);
                    if (mode === 'save') {
                        source.push(data);
                    }
                    if (mode === 'edit') {
                        for (var i = 0; i < source.length; i++) {
                            if (source[i].id == data.id) {
                                source[i] = data;
                            }
                        }
                    }
                    if(mode == 'delete'){
                        for (var i = 0; i < source.length; i++) {
                            if (source[i].id == data) {
                                source.splice(i,1);
                                break;
                            }
                        }
                    }
                    resetTabullet();
                }
            });
        }

        resetTabullet();
    });
</script>
<script src="http://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="js/Tabullet.js"></script>
<script>
    var name = $("#teacherName").val();
    var day = $("#day").val();
    var duration = $("#duration").val();
    var time = $("time").val();
    $("#table").tabullet({
        data:{name,day,duration,time},
    });
</script>-->
</body>
</html>
@endsection