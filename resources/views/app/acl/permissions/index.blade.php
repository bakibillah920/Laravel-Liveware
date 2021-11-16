@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mt-3">
                <div class="card-header">
                    <h4 class="m-0 float-left">Permissions</h4>
                    @can('manage-permission-create')
                        <a href="JavaScript:void(0)" class="float-right btn btn-outline-success btn-sm createData"
                           data-route="{{ route('permissions.create') }}"
                           data-formtype="newPage"
                           data-formsize="small">
                            <i class="zmdi zmdi-plus-square zmdi-hc-lg"></i> New permission
                        </a>
                    @endcan
                </div>
                <div class="card-body torrent-list" id="globalTable">
                    <table id="datatable" class="table border table-striped table-hover display responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Name</th>
                            <th>Parent</th>
                            <th>Description</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody> {{--14 tr--}}
                        @foreach($permissions as $permission)
                            <tr data-row="{{ $permission->id }}">
                                <td class="text-center" width="30px">{{ $loop->iteration }}</td>
                                <td class="">{{ $permission->name }}</td>
                                <td class="">{{ $permission->parent ? $permission->parent->name : '' }}</td>
                                <td>{!! Str::limit($permission->getDescription(), 100) !!}</td>
                                <td class="text-center" width="150px">
                                    @can('manage-permission-show')
                                        <a href="JavaScript:void(0)"
                                           class="btn btn-info btn-just-icon showData"
                                           data-id="{{ $permission->id }}"
                                           data-route="{{ route('permissions.show', $permission->id) }}"
                                           data-formtype="newPage"
                                           data-formsize="large">
                                            <i class="zmdi zmdi-eye"></i>
                                        </a>
                                    @endcan
                                    @can('manage-permission-update')
                                        <a href="JavaScript:void(0)"
                                           class="btn btn-warning btn-just-icon editData"
                                           data-id="{{ $permission->id }}"
                                           data-route="{{ route('permissions.edit', $permission->id) }}"
                                           data-formtype="newPage"
                                           data-formsize="large">
                                            <i class="zmdi zmdi-edit"></i>
                                        </a>
                                    @endcan
                                    @can('manage-permission-delete')
                                        <a href="JavaScript:void(0)"
                                           class="btn btn-danger btn-just-icon deleteData"
                                           data-id="{{ $permission->id }}"
                                           data-route="{{ route('permissions.destroy', $permission->id) }}"
                                            {{--data-delete="{{ $permission->id }}"--}}>
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
