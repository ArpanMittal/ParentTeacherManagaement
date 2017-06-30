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
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Name</th>
            <th>Gender</th>
            <th>Address</th>
            <th>Contact</th>
        </tr>
        @foreach($teacherDetails as $teacherDetail)
            <tr>
                <td>{{$teacherDetail['name']}}</td>
                <td>{{$teacherDetail['gender']}}</td>
                <td>{{$teacherDetail['address']}}</td>
                <td>{{$teacherDetail['contact']}}</td>
            </tr>
        @endforeach
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
</body>
</html>
@endsection