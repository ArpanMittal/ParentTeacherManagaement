@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
          crossorigin="anonymous">
</head>

<body>

<!--<div class="container">
    <ul class="pager">
        <li class="previous"><a href="#">Previous</a></li>
    </ul>
</div>-->

<div class="container">
    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#newContent">Create New Category</button>
</div>
<!-- Modal -->
<div id="newContent" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">New Category</h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div>
                            <form id="uploadLink" method="post" role="form" action="createCategory" >
                                {{csrf_field()}}
                                <div class="form-group {{$errors->has('gradeId')?'has-error':''}}">
                                    <label for="gradeId"  class="control-label ">Grade</label>
                                    <div >
                                        <input type="radio" id="grade" name="grade" value="1"/>Playgroup
                                        <input type="radio" id="grade" name="grade" value="2"/>Nursery
                                        <input type="radio" id="grade" name="grade" value="3"/>J.K.G.
                                        <input type="radio" id="grade" name="grade" value="4"/>S.K.G.
                                        @if ($errors->has('gradeId'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('gradeId') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group {{$errors->has('contentName')?'has-error':''}}">
                                    <label for="contentName"  class="control-label">New Category</label>
                                    <div>
                                        <input type="text" name="contentName" id="contentName" value="{{ Input::old('contentName') }}" required autofocus>
                                        @if ($errors->has('contentName'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('contentName') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" id="create-category" class="btn crud-submit btn-success">Submit</button>
                                </div>

                                <div class="col-md-6 col-md-offset-4">
                                    @if (session('status'))
                                        <div class="alert alert-warning">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<div class="container">
    <div class="row">
        <div class="col-md-12 ">
            <div class="panel panel-default">
                <div class="panel-heading"><h1>Upload Video Link</h1></div>
                <div class="panel-body">
                    <div>
                        @if (session('failure'))
                            <div class="alert alert-danger">
                                {{ session('failure') }}
                            </div>
                        @endif
                    </div>
                    <div id="status" class="alert alert-danger" style="display:none;">
                        No category for this grade. Click on Create New Category to add category.
                    </div>
                    <form id="uploadLink" method="post" role="form" action="/uploadLink" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="form-group {{$errors->has('gradeId')?'has-error':''}}">
                            <label for="gradeId"  class="col-md-4 control-label ">Grade</label>
                            <div class="col-md-6">
                                <select  id="gradeId"name="gradeId" class="form-control">
                                    <option value=0>Select grade</option>
                                    @foreach($grades as $grade)
                                        <option value = "{{$grade['id']}}">{{$grade['name']}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('gradeId'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gradeId') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12"></div>

                        <div id="contents" class="form-group {{$errors->has('contentName')?'has-error':''}}" style="display:none;">
                            <label for="contentName"  class="col-md-4 control-label">Category</label>
                            <div class="col-md-6">
                                <select  id="contentName"name="contentName" class="form-control">

                                </select>

                                @if ($errors->has('contentName'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('contentName') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12"></div>

                        <div class="form-group {{$errors->has('categoryName')?'has-error':''}}">
                            <label for="categoryName"  class="col-md-4 control-label">Video Name</label>
                            <div class="col-md-6">
                                <input type="text" name="categoryName" id="categoryName" value="{{ Input::old('categoryName') }}" required autofocus>
                                @if ($errors->has('categoryName'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('categoryName') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="col-md-12"></div>

                        <div class="form-group {{$errors->has('categoryUrl')?'has-error':''}}">
                            <label for="categoryUrl"  class="col-md-4 control-label">URL</label>
                            <div class="col-md-6">
                                <input type="text" name="categoryUrl" id="categoryUrl" value="{{ Input::old('categoryUrl') }}" required autofocus>
                                @if ($errors->has('categoryUrl'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('categoryUrl') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12"></div>

                        <div class="form-group {{$errors->has('description')?'has-error':''}}">
                            <label for="description"  class="col-md-4 control-label">Description</label>
                            <div class="col-md-6">
                                <input type="text" name="description" id="description" value="{{ Input::old('description    ') }}" required autofocus>
                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12"></div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <a class="btn btn-default" href="{{ route('upload.index') }}">Back</a>
                            </div>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.3.1/jquery.twbsPagination.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js"></script>

<script type="text/javascript">
    var url = "<?php echo route('createCategory')?>";
</script>
<script src="/js/uploadCategory-ajax.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('select[name="gradeId"]').on('change', function() {
            var gradeID = $(this).val();
//            if(gradeID != 0){
                if(gradeID) {
                    $.ajax({
                        url: 'category'+gradeID,
                        type: "GET",
                        dataType: "json",
                        success:function(data) {
                            document.getElementById('contents').style.display='block';
                            $('select[name="contentName"]').empty();
                            var contents = $.makeArray(data);
                            if (jQuery.isEmptyObject(contents)){
                                if(gradeID!=0){
                                    document.getElementById('contents').style.display='none';
                                    document.getElementById('status').style.display='block';
                                }
                                else {
                                    document.getElementById('contents').style.display='none';
                                    document.getElementById('status').style.display='hide';

                                }
                            }
                            else{
                                $.each(contents, function(index,content) {
                                    $('select[name="contentName"]').append('<option value="'+content.name +'">'+content.name +'</option>');
                                });
                                document.getElementById('status').style.display='none';
                            }
                        }
                    });
                }else{
                    $('select[name="contentName"]').empty();
                }
//            }
//            else{
//                document.getElementById('contents').style.display='none';
//            }
        });
    });
</script>
</body>
</html>
@endsection