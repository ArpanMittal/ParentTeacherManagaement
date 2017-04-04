<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<div class="col-lg-offset-4 col-lg-4">
    <h1>Upload File</h1>
    <form action="/upload" enctype="multipart/form-data" method="post" >
        {{csrf_field()}}
        <input type="file" name="fileEntry[]" multiple>
        <br>
        <input type="submit" value="Upload">
    </form>
</div>
</body>
</html>
