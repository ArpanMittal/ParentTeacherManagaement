@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Show All</h1>
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
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>TITLE</th>
                    <th>URL</th>
                    <th>DESCRIPTION</th>
                    <th>UPLOADED BY</th>
                    <th>TYPE</th>
                </tr>
                </thead>

                <tbody>

                @foreach($uploadedFiles as $uploadedFile)
                    <tr>
                        <td>{{$uploadedFile['title']}}</td>
                        <td><a href="{{ route('getFile', $uploadedFile['url']) }}" target="_blank">Show {{$uploadedFile['title']}}</a></td>
                        <td>{{$uploadedFile['description']}}</td>
                        <td>{{$uploadedFile['uploadedBy']}}</td>
                        <td>{{$uploadedFile['type']}}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>

        </div>
    </div>


@endsection