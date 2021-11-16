@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mt-3">
                <div class="card-header">

                    <h4 class="m-0 float-left">User Profile</h4>

                    <a href="{{ URL::previous() }}" class="float-right btn btn-outline-warning btn-sm">
                        <i class="zmdi zmdi-arrow-left zmdi-hc-lg"></i> Go Back
                    </a>

                </div>
                <div class="card-body torrent-list">
                    <form action="{{ route('profile.update', $user->id) }}" method="post" id="globalForm" class="form-horizontal pageLoad" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="form-group print-error-msg" style="display:none">
                            <a href="{{ route('profile.show', $user->username) }}" id="redirectPath"></a>
                            <div class="row ml-0 mr-0">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <img src="{{ asset('images/avatars/'.$user->avatar) }}" alt="" width="100%">
                                <br>
                                Change Avatar:
                                <br>
                                <input type="file" name="avatar" class="form-control">
                                <a href="{{ route('editPassword') }}" class="btn btn-outline-warning form-control mt-2">
                                    <i class="zmdi zmdi-lock"></i>
                                     Change Password</a>
                            </div>
                            <div class="col-lg-9">
                                <table class="table table-striped table-hover">
                                    <tbody>
                                    <tr>
                                        <td>Username:</td>
                                        <td>
                                            {{-- <input type="text" name="username" class="form-control" value="{{ $user->username }}"> --}}
                                            {{ $user->username }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Role:</td>
                                        <td>{{ $user->roles[0]->name }}</td>
                                    </tr>
                                    @if(Auth::user()->id == $user->id)
                                        <tr>
                                            <td>Email:</td>
                                            <td><input type="email" name="email" class="form-control" value="{{ $user->email }}"></td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td>Age:</td>
                                        <td><input type="date" name="dob" class="form-control" value="{{ $user->dob }}"></td>
                                    </tr>
                                    <tr>
                                        <td>Gender:</td>
                                        <td>
                                            <input type="radio" name="gender" value="male" id="male" {{ $user->gender = 'male' ? 'checked':'' }}>
                                            <label for="male">Male</label>
                                            <input type="radio" name="gender" value="female" id="female" {{ $user->gender = 'female' ? 'checked':'' }}>
                                            <label for="female">Female</label>
                                            <input type="radio" name="gender" value="unknown" id="unknown" {{ $user->gender = 'unknown' ? 'checked':'' }}>
                                            <label for="unknown">unknown</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Joined:</td>
                                        <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d M, Y g:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Last Online:</td>
                                        <td>{{ \Carbon\Carbon::parse($user->updated_at)->format('d M, Y g:i A') }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-12">
                                <button type="button" class="btn btn-success float-right updateData">Update Profile</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
