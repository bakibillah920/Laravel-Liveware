@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mt-3">
                <div class="card-header">
                    <h4 class="m-0 float-left">users</h4>
                    @can('manage-user-create')
                        <a href="JavaScript:void(0)" class="float-right btn btn-outline-success btn-sm createData"
                           data-route="{{ route('users.create') }}"
                           data-formtype="newPage"
                           data-formsize="small">
                            <i class="zmdi zmdi-plus-square zmdi-hc-lg"></i> New User
                        </a>
                    @endcan
                </div>
                <div class="card-body torrent-list" id="globalTable">
                    <table id="datatable" class="table border table-striped table-hover display responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Avatar</th>
                            <th>Username</th>
                            {{-- <th>Email</th> --}}
                            <th>Role</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody> {{--14 tr--}}
                        @foreach($users as $user)
                            <tr data-row="{{ $user->id }}">
                                <td class="text-center" width="30px">{{ $loop->iteration }}</td>
                                <td class=""><img src="{{ asset('images/avatars/'. $user->avatar ) }}" alt="{{ $user->username }}" width="50px" height="50px"></td>
                                <td class="">
                                    <a href="{{ route('profile.show', $user->username) }}">{{ $user->username }}</a>
                                </td>
                                {{-- <td class="">{{ $user->email }}</td> --}}
                                <td style="color: {{ $user->roles[0]->color }}"><strong>{{ $user->roles[0]->name }}</strong></td>
                                <td class="text-center" width="150px">
                                    @can('manage-user-show')
                                        <a href="JavaScript:void(0)"
                                           class="btn btn-info btn-just-icon showData"
                                           data-id="{{ $user->id }}"
                                           data-route="{{ route('users.show', $user->id) }}"
                                           data-formtype="newPage"
                                           data-formsize="large">
                                            <i class="zmdi zmdi-eye"></i>
                                        </a>
                                    @endcan
                                    @can('manage-user-update')
                                        <a href="JavaScript:void(0)"
                                           class="btn btn-warning btn-just-icon editData"
                                           data-id="{{ $user->id }}"
                                           data-route="{{ route('users.edit', $user->id) }}"
                                           data-formtype="newPage"
                                           data-formsize="large">
                                            <i class="zmdi zmdi-edit"></i>
                                        </a>
                                    @endcan
                                    @can('manage-user-delete')
                                        <a href="JavaScript:void(0)"
                                           class="btn btn-danger btn-just-icon deleteData"
                                           data-id="{{ $user->id }}"
                                           data-route="{{ route('users.destroy', $user->id) }}"
                                            {{--data-delete="{{ $user->id }}"--}}>
                                            <i class="zmdi zmdi-delete"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('#datatable').DataTable();
    </script>
@endpush
