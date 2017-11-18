@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Image Files</h1>
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
                    <th class="text-right">OPTIONS</th>
                </tr>
                </thead>

                <tbody>

                @foreach($uploadedFiles as $uploadedFile)
                    <tr>
                        <td>{{$uploadedFile['title']}}</td>
                        <td>

                            <a href="{{ url('http://web.rackupcambridge.com'. $uploadedFile['filePath']) }}" target="_blank">Show {{$uploadedFile['title']}}</a>
                        </td>
                        <td>{{$uploadedFile['description']}}</td>
                        <td class="text-right">
                            <form action="{{ route('uploadPdf.destroy', $uploadedFile['fileId']) }}" method="POST" style="display: inline;"
                                  onsubmit="if(confirm('Delete? Are you sure?'))
                                  {
                                  return true
                                  }
                                  else
                                  {
                                  return false
                                  };">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>

                @endforeach

                </tbody>
            </table>

            <a class="btn btn-success" href="{{ route('uploadPdf.create') }}">Add New Image</a>
        </div>
    </div>


@endsection