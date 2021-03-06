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
                    <th>TIMESTAMP</th>
                    <th>DELETE</th>
                </tr>
                </thead>

                <tbody>

                @foreach($uploadedFiles as $uploadedFile)
                    <tr>

                        <td>{{$uploadedFile['title']}}</td>

                        @if($uploadedFile['type']=="Image")
                        <td><a href="{{ url('http://web.rackupcambridge.com'. $uploadedFile['url_main']) }}" target="_blank">Show {{$uploadedFile['title']}}</a></td>
                        @endif
                        @if($uploadedFile['type']=="Video")
                            <td><a href="{{ url($uploadedFile['url_main']) }}" target="_blank">Show {{$uploadedFile['title']}}</a></td>
                        @endif

                        <td>{{$uploadedFile['description']}}</td>
                        <td>{{$uploadedFile['uploadedBy']}}</td>
                        <td>{{$uploadedFile['type']}}</td>
                        <td>{{$uploadedFile['time_stamp']}}</td>
                        <td><a href="{{ url('/showAll/'.$uploadedFile['id']) }}">Delete</a></td>
                        {{--<td><a href="{{ route('showAll') }}">Delete</a></td>--}}
                        {{--<td><a href="{{ URL::to("showAll/".$uploadedFile['id'].")) }}"> Delete </a> </td>--}}
                        {{--<td><form action="#/{{$uploadedFile['id']}}" method="DELETE" style="display: inline;"--}}
                                  {{--onsubmit="if(confirm('Delete? Are you sure?'))--}}
                   {{--{ return true }--}}
                   {{--else {return false };">--}}
                                {{--<button class="btn btn-danger" type="submit">Delete</button>--}}
                            {{--</form></td>--}}
                    </tr>

                @endforeach

                </tbody>
            </table>

            <label>Deleted Items</label>

            <table class="table table-striped">
                <thead>
                <tr>
                    <th>TITLE</th>
                    <th>URL</th>
                    <th>DESCRIPTION</th>
                    <th>DELETED BY</th>

                    <th> TIME</th>
                </tr>
                </thead>

                <tbody>

                @foreach($deleteContents as $deletedimage)
                    <tr>

                        <td>{{$deletedimage['name']}}</td>
                        <td><a href="{{ url('http://web.rackupcambridge.com'. $deletedimage['url']) }}" target="_blank">Show {{$deletedimage['name']}}</a></td>
                        <td>{{$deletedimage['description']}}</td>
                        <td>{{$deletedimage['deletedBy']}}</td>
                        <td>{{$deletedimage['time']}}</td>
                        {{--<td><a href="{{ route('showAll') }}">Delete</a></td>--}}
                        {{--<td><a href="{{ URL::to("showAll/".$uploadedFile['id'].")) }}"> Delete </a> </td>--}}
                        {{--<td><form action="#/{{$uploadedFile['id']}}" method="DELETE" style="display: inline;"--}}
                        {{--onsubmit="if(confirm('Delete? Are you sure?'))--}}
                        {{--{ return true }--}}
                        {{--else {return false };">--}}
                        {{--<button class="btn btn-danger" type="submit">Delete</button>--}}
                        {{--</form></td>--}}
                    </tr>

                @endforeach

                </tbody>
            </table>

        </div>
    </div>


@endsection