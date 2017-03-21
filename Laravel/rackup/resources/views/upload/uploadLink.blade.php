<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-md-12 ">
            <div class="panel panel-default">
                <div class="panel-heading"><h1>Upload Video Link</h1></div>
                <div class="panel-body">
                    <form id="uploadLink" method="post" role="form" action="/uploadLink" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="form-group {{$errors->has('gradeId')?'has-error':''}}">
                            <label for="gradeId"  class="col-md-4 control-label">Grade</label>
                            <div class="col-md-6">
                                <input type="radio" name="gradeId"value="1"/>Playgroup
                                <input type="radio" name="gradeId"value="2"/>Nursery
                                <input type="radio" name="gradeId"value="3"/>J.K.G.
                                <input type="radio" name="gradeId"value="4"/>S.K.G.
                                @if ($errors->has('gradeId'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gradeId') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12"></div>

                        <div class="form-group {{$errors->has('contentName')?'has-error':''}}">
                            <label for="contentName"  class="col-md-4 control-label">Content</label>
                            <div class="col-md-6">
                                <input type="text" name="contentName" id="contentName" value="{{ Input::old('contentName') }}" required autofocus>
                                @if ($errors->has('contentName'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('contentName') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12"></div>

                        <div class="form-group {{$errors->has('categoryName')?'has-error':''}}">
                            <label for="categoryName"  class="col-md-4 control-label">Category</label>
                            <div class="col-md-6">
                                <select name="categoryName" onchange='enterCategory(this.value);'>
                                    <option value="yoga">Yoga</option>
                                    <option value="moralStories">Moral Stories</option>
                                    <option value="rhymes">Rhymes</option>
                                    <option value="other">Other</option>
                                </select>
                                <input type="text" name="categoryName" id="categoryName" style='display:none;' placeholder="Enter category"/>
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

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function enterCategory(val){
        var element=document.getElementById('categoryName');
        if(val=='other')
            element.style.display='block';
        else
            element.style.display='none';
    }
</script>

</body>
</html>