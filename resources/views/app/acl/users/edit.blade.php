@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mt-3">
                <div class="card-header">
                    <h4 class="m-0 float-left">users</h4>

                    <a href="{{ URL::previous() }}" class="float-right btn btn-outline-warning btn-sm">
                        <i class="zmdi zmdi-arrow-left zmdi-hc-lg"></i> Go Back
                    </a>
                </div>
                <div class="card-body torrent-list" id="globalModal">
                    <span class="load-overlay"></span>

                    <form action="{{ route('users.update', $user->id) }}" method="post" id="globalForm" class="form-horizontal pageLoad" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="form-group print-error-msg" style="display:none">
                            <a href="{{ route('users.index') }}" id="redirectPath"></a>
                            <div class="row ml-0 mr-0">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="bmd-label-floating"> Role : </label>
                                    <select name="role" class="form-control">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ $role->id == $user->roles[0]->id ? 'selected':'' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="bmd-label-floating" for="username"> Username : </label>
                                    <input type="text" name="username" onkeyup="forceSlug()" id="username" class="form-control" value="{{ $user->username }}" required="true" onkeyup="if (/[^\\A-Za-z0-9-\\.]/g.test(this.value)) this.value = this.value.replace(/[^\\A-Za-z0-9-\\.]/g,'')">
                                    <small><span class="text-danger">Note: </span>Allowed characters are "A-Z" "a-z" "0-9" "-"</small>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="bmd-label-floating" for="avatar"> Avatar : </label>
                                    <input type="file" name="avatar" id="avatar" class="form-control" required="true">
                                    <small class="text-danger">Note: Leave blank for old avatar!</small>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="bmd-label-floating" for="email"> Email : </label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required="true">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="bmd-label-floating" for="status"> Status : </label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="" {{ $user->status == '' ? 'selected':'' }}>Okay</option>
                                        <option value="Warn" {{ $user->status == 'Warn' ? 'selected':'' }}>Warn</option>
                                        <option value="Suspend" {{ $user->status == 'Suspend' ? 'selected':'' }}>Suspend</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12" id="status_reason">
                                <div class="form-group">
                                    <label class="bmd-label-floating"> Status Reason : </label>
                                    <textarea name="status_reason" class="form-control">{{ $user->status_reason }}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating" for="password"> Password : </label>
                                    <input type="password" name="password" id="password" class="form-control" required="true">
                                    <small class="text-danger">Note: Leave blank for old password!</small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating" for="password-confirm"> Confirm Password : </label>
                                    <input type="password" name="password_confirmation" id="password-confirm" class="form-control" required autocomplete="new-password">
                                    <small class="text-danger">Note: Leave blank for old password!</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 ml-auto">
                                <a href="{{ URL::previous() }}" id="closeBtn" class="form-control btn btn-danger float-left pt-2 pb-2 mr-1"><i class="zmdi zmdi-arrow-left zmdi-hc-lg"></i> Go Back</a>
                            </div>
                            <div class="col-lg-2">
                                <button type="button" id="submitBtn" class="storeData form-control btn btn-success pt-2 pb-2 ml-1"><i class="zmdi zmdi-check zmdi-hc-lg"></i> Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        if ($('#status').val() == '')
        {
            $('#status_reason').hide();
        }

        $('#status').on('change', function () {
            if ($('#status').val() == 'Warn' || $('#status').val() == 'Suspend')
            {
                $('#status_reason').show();
            }
            else
            {
                $('#status_reason').hide();
            }
        });
    </script>
@endpush
