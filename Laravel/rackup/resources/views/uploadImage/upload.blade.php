@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="container">
    <h1>Upload Image</h1>
    <div>
        @if (session('failure'))
            <div class="alert alert-danger">
                {{ session('failure') }}
            </div>
        @endif
    </div>
    <div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <div id="status" class="alert alert-success" style="display:none;">
        Upload successful. Upload next image or Click on Send Notification to notify parent.
    </div>
    <div id="invalidFormat" class="alert alert-danger" style="display:none;">
        Invalid File format.Upload only jpg images
    </div>

    <form id="uploadImage" action="{{ route('uploadImage.store')}}" enctype="multipart/form-data" method="post"
          onsubmit="return validate()" >
        {{csrf_field()}}

        <div class="form-group {{$errors->has('studentId')?'has-error':''}}">
            <label for="studentId">Student</label>
            <select  id="studentId" name="studentId" class="form-control" required autofocus>
                @foreach($students as $student)
                    <option value = "{{$student['id']}}" >{{$student['name']}}_{{$student['id']}}</option>
                @endforeach
            </select>
            @if ($errors->has('studentId'))
                <span class="help-block">
                    <strong>{{ $errors->first('studentId') }}</strong>
                </span>
            @endif
        </div>

        <input id="fileEntries" type="file" name="fileEntries" required>
        <br>
        <div class="form-group {{$errors->has('title')?'has-error':''}}">
            <label for="title"  class="control-label">Title</label>
            <div>
                <input type="text" name="title" id="title" value="{{ Input::old('title') }}" required autofocus>
                @if ($errors->has('title'))
                    <span class="help-block">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group {{$errors->has('message')?'has-error':''}}">
            <label for="message"  class="control-label">Description</label>
            <div>
                <input type="text" name="message" id="message" value="{{ Input::old('message') }}" required autofocus>
                @if ($errors->has('message'))
                    <span class="help-block">
                        <strong>{{ $errors->first('message') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <input type="submit" id="upload" value="Upload" >

        <button id="sendNotification" type="submit" class="btn btn-primary">
            Send Notification
        </button>

    </form>
</div>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.3.1/jquery.twbsPagination.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js"></script>


<script type="text/javascript">
    var url = "<?php echo route('uploadImage.store')?>";
    var flag = 0;
</script>
<script type="text/javascript">
    function validate() {
        var filename=document.getElementById('fileEntries').value;
        var extension=filename.substr(filename.lastIndexOf('.')+1).toLowerCase();
        //alert(extension);
        if(extension=='jpg') {
            flag = 1;
        } else {
            flag =0;
            document.getElementById('invalidFormat').style.display='block';
            document.getElementById('status').style.display='none';
        }
    }
</script>

<script type="text/javascript">
    $("form#uploadImage").submit(function(event) {
        //disable the default form submission
        event.preventDefault();
        //grab all form data
        var formData = new FormData($(this)[0]);
        var studentId = $('#studentId option:selected').val();
        formData.append('studentId',studentId);
    if(flag == 1){
        $.ajax({
            url: url,
            type: 'POST',
            data:formData,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            success: function (returndata) {
                document.getElementById('status').style.display='block';
                document.getElementById('invalidFormat').style.display='none';
//                alert("success");
//                alert(returndata+"return data");
                var studentId = returndata;
                $('#studentId option:selected').val()+"selected";
                toastr.success('Image uploaded', 'Success Alert', {timeOut: 5000});
//                window.location.reload();
            },
            error: function (data) {
//                alert('error');
//                alert(JSON.stringify(data));
                toastr.error('Cannot upload image','Failure Alert', {timeOut: 5000});
//              window.location.reload();
            }
        });
        document.getElementById("studentId").disabled = true;
    }
    });

    $("#sendNotification").click(function(e){
//        alert('on click');
        e.preventDefault();
        var studentId = $('#studentId option:selected').val();
//        alert(studentId);
        $.ajax({
            type:'POST',
            url: 'sendNotification'+studentId,
            success: function( data ) {
//                alert('success');
//                alert(JSON.stringify(data));
                toastr.success('Child Activity notification sent', 'Success Alert', {timeOut: 5000});
                window.location.reload();
            },
            error: function(data) {
//                alert('error');
//                alert(JSON.stringify(data));
                toastr.error('Failed to send notification','Failure Alert', {timeOut: 5000});
                window.location.reload();
            }

        });
    });

</script>
</body>
</html>
@endsection