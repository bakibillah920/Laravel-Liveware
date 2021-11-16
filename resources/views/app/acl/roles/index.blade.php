@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mt-3">
                <div class="card-header">
                    <h4 class="m-0 float-left">roles</h4>
                    @can('manage-role-create')
                        <a href="JavaScript:void(0)" class="float-right btn btn-outline-success btn-sm createData"
                           data-route="{{ route('roles.create') }}"
                           data-formtype="newPage"
                           data-formsize="small">
                            <i class="zmdi zmdi-plus-square zmdi-hc-lg"></i> New role
                        </a>
                    @endcan
                </div>
                <div class="card-body torrent-list" id="globalTable">
                    <table id="datatable" class="table border table-striped table-hover display responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Role</th>
                            <th>Color</th>
                            <th>Description</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody> {{--14 tr--}}
                        @foreach($roles as $role)
                            <tr data-row="{{ $role->id }}">
                                <td class="text-center" width="30px">{{ $loop->iteration }}</td>
                                <td class="">{{ $role->name }}</td>
                                <td class="">{{ $role->color }}</td>
                                <td>{!! Str::limit($role->getDescription(), 100) !!}</td>
                                <td class="text-center" width="150px">
                                    @can('manage-role-show')
                                        <a href="JavaScript:void(0)"
                                           class="btn btn-info btn-just-icon showData"
                                           data-id="{{ $role->id }}"
                                           data-route="{{ route('roles.show', $role->id) }}"
                                           data-formtype="newPage"
                                           data-formsize="large">
                                            <i class="zmdi zmdi-eye"></i>
                                        </a>
                                    @endcan
                                    @can('manage-role-update')
                                        <a href="JavaScript:void(0)"
                                           class="btn btn-warning btn-just-icon editData"
                                           data-id="{{ $role->id }}"
                                           data-route="{{ route('roles.edit', $role->id) }}"
                                           data-formtype="newPage"
                                           data-formsize="large">
                                            <i class="zmdi zmdi-edit"></i>
                                        </a>
                                    @endcan
                                    @can('manage-role-delete')
                                        <a href="JavaScript:void(0)"
                                           class="btn btn-danger btn-just-icon deleteData"
                                           data-id="{{ $role->id }}"
                                           data-route="{{ route('roles.destroy', $role->id) }}"
                                            {{--data-delete="{{ $role->id }}"--}}>
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
