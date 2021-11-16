@extends('layouts.app')

@push('page-specific-css')

    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

@endpush

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mt-3">
                <div class="card-header">
                    <h4 class="m-0 float-left">Categories</h4>
                    @can('manage-category-create')
                        <a href="JavaScript:void(0)" class="float-right btn btn-outline-success btn-sm createData"
                           data-route="{{ route('categories.create') }}"
                           data-formtype="modal"
                           data-formsize="small">
                            <i class="zmdi zmdi-plus-square zmdi-hc-lg"></i> New Category
                        </a>
                    @endcan
                </div>
                <div class="card-body torrent-list" id="globalTable">
                    <table id="datatable" class="table border table-striped table-hover {{--table-responsive--}} display responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Icon</th>
                            <th>Category</th>
                            <th>Parent</th>
                            <th class="">Serial</th>
                            <th class="">Status</th>
                            <th class="">Created</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody> {{--14 tr--}}
                        @foreach($categories as $category)
                            <tr data-row="{{ $category->id }}">
                                <td class="text-center" width="30px">{{ $loop->iteration }}</td>
                                <td class="text-center" width="30px">
                                    @if($category->icon == '')
                                        n/a
                                    @else
                                        <span class="list-svg">
                                            @if($category->icon)
                                                @if(pathinfo(public_path('images/categories/'.$category->icon), PATHINFO_EXTENSION) === 'svg')
                                                    {!! file_get_contents(public_path('images/categories/'.$category->icon)) !!}
                                                @else
                                                    <img src="{{ asset('images/categories/'.$category->icon) }}" alt="{{ $category->name }}">
                                                @endif
                                            @else
                                                n/a
                                            @endif
                                        </span>
                                    @endif
                                </td>
                                <td class="">{{ $category->name }}</td>
                                <td class="">{{ $category->parent_id != '' ? $category->parentCategory->name:'n/a' }}</td>
                                <td class="">{{ $category->serial }}</td>
                                <td class="">{{ $category->status }}</td>
                                <td class="">{{ \Carbon\Carbon::parse($category->created_at)->format('M d, Y - g:i A') }}</td>
                                <td class="text-center" width="150px">
                                    @can('manage-category-show')
                                        <a href="JavaScript:void(0)"
                                           class="btn btn-info btn-just-icon showData"
                                           data-id="{{ $category->id }}"
                                           data-route="{{ route('categories.show', $category->id) }}"
                                           data-formtype="modal"
                                           data-formsize="large">
                                            <i class="zmdi zmdi-eye"></i>
                                        </a>
                                    @endcan
                                    @can('manage-category-update')
                                        <a href="JavaScript:void(0)"
                                           class="btn btn-warning btn-just-icon editData"
                                           data-id="{{ $category->id }}"
                                           data-route="{{ route('categories.edit', $category->id) }}"
                                           data-formtype="modal"
                                           data-formsize="large">
                                            <i class="zmdi zmdi-edit"></i>
                                        </a>
                                    @endcan
                                    @can('manage-category-delete')
                                        <a href="JavaScript:void(0)"
                                           class="btn btn-danger btn-just-icon deleteData"
                                           data-route="{{ route('categories.destroy', $category->id) }}"
                                            {{--data-delete="{{ $category->id }}"--}}>
                                            <i class="zmdi zmdi-delete"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{--<div class="shop_toolbar t_bottom mt-4">
                        <div class="pagination">
                            <ul>
                                <li class="current">1</li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li class="next"><a href="#">next</a></li>
                                <li><a href="#">>></a></li>
                            </ul>
                        </div>
                    </div>--}}
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')

    <script>
        // Initialize Datatable
        $(document).ready( function () {
            $('#datatable').DataTable();
        } );
    </script>

@endpush
