@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Edit User Details</h1>
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

            <form action="{{ route('updateProfileDetails', $profile_details['id']) }}" enctype="multipart/form-data" method="POST">
                {{--<input type="hidden" name="_method" value="PUT">--}}
                {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}


                <div class="form-group">
                    <label for="id">User ID</label>
                    <p class="form-control-static">{{$profile_details['id']}}</p>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <p class="form-control-static">{{$profile_details['username']}}</p>
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <p class="form-control-static">{{$profile_details['name']}}</p>
                </div>
                <div class="form-group {{$errors->has('contact')?'has-error':''}}">
                    <label for="contact">Contact</label>
                    <input type="text" name="contact" class="form-control" value="{{$profile_details['contact']}}"/>
                    @if ($errors->has('contact'))
                        <span class="help-block">
                                <strong>{{ $errors->first('contact') }}</strong>
                            </span>
                    @endif
                </div>
                <div class="form-group $errors->has('address')?'has-error':''}}">
                    <label for="address">Address</label>
                    <input type="text" name="address" class="form-control" value="{{$profile_details['address']}}"/>
                    @if ($errors->has('address'))
                        <span class="help-block">
                                <strong>{{ $errors->first('address') }}</strong>
                            </span>
                    @endif
                </div>

                <input id="profilePhoto" type="file" name="profilePhoto">
                {{--<input type="submit" id="upload" value="Upload Profile Photo">--}}
                <a class="btn btn-default" href="{{ route('home') }}">Back</a>
                <button class="btn btn-primary" type="submit" >Save</button>
            </form>
        </div>
    </div>


@endsection
