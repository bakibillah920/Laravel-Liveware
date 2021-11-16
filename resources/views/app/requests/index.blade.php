@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mt-3">
                <div class="card-header">
                    <h4 class="m-0 float-left">Request List</h4>
                    <a href="{{ route('requests.create') }}" class="float-right btn btn-outline-success btn-sm">
                        <i class="zmdi zmdi-plus-square zmdi-hc-lg"></i> New Request
                    </a>
                </div>
                <div class="card-body torrent-list" id="globalTable">
                    <table id="datatable" class="table border table-striped table-hover display responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Date Added</th>
                            <th>Requested By</th>
                            <th>Filled</th>
                            <th>Filled By</th>
                        </tr>
                        </thead>
                        <tbody> {{--14 tr--}}
                        @foreach($requests as $request)
                            <tr data-row="{{ $request->id }}">
                                <td class="text-center" width="30px">{{ $loop->iteration }}</td>
                                <td class="">
                                    @if($request->requestFiller)
                                        @if($request->request_by == Auth::user()->id && $request->is_filled == 0 || Auth::user()->can('manage-request-delete'))
                                            <a href="JavaScript:void(0)"
                                               class="btn btn-sm btn-danger btn-just-icon deleteData mr-2"
                                               data-id="{{ $request->id }}"
                                               data-route="{{ route('requests.destroy', $request->id) }}"
                                                {{--data-delete="{{ $upcoming->id }}"--}}>
                                                <i class="zmdi zmdi-delete"></i>
                                            </a>
                                        @endif
                                        <a href="{{ $request->filled_url }}"
                                           class="text-success"
                                           data-formtype="newPage">
                                            <strong> {{ $request->name }}</strong>
                                        </a>
                                    @else
                                        @if($request->request_by == Auth::user()->id && $request->is_filled == 0 || Auth::user()->can('manage-request-delete'))
                                            <a href="JavaScript:void(0)"
                                               class="btn btn-sm btn-danger btn-just-icon deleteData mr-2"
                                               data-id="{{ $request->id }}"
                                               data-route="{{ route('requests.destroy', $request->id) }}"
                                                {{--data-delete="{{ $upcoming->id }}"--}}>
                                                <i class="zmdi zmdi-delete"></i>
                                            </a>
                                        @endif
                                        <a href="JavaScript:void(0)"
                                           class="text-dark editData"
                                           data-id="{{ $request->id }}"
                                           data-route="{{ route('requests.edit', $request->id) }}"
                                           data-formtype="newPage">
                                            <strong> {{ $request->name }}</strong>
                                        </a>
                                    @endif
                                </td>
                                <td class="nav-svg">
                                    @if($request->category->parentCategory->icon)
                                        @if(pathinfo(public_path('images/categories/'.$request->category->parentCategory->icon), PATHINFO_EXTENSION) === 'svg')
                                            {!! file_get_contents(public_path('images/categories/'.$request->category->parentCategory->icon)) !!}
                                        @else
                                            <img src="{{ asset('images/categories/'.$request->category->parentCategory->icon) }}" alt="{{ $request->category->parentCategory->name }}">
                                        @endif
                                    @endif
                                    {{ $request->category->parentCategory->name.' / '.$request->category->name }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($request->created_at)->format('d M, y - g:i A') }}</td>
                                <td class="">
                                    <a href="{{ route('profile.show', $request->requester->username ) }}">
                                        {!! $request->requester->username !!}
                                    </a>
                                </td>
                                <td class="text-{!! $request->is_filled == 1 ? 'success':'danger' !!}">
                                    <strong>{!! $request->is_filled == 1 ? 'Yes':'No' !!}</strong>
                                </td>
                                <td class="">
                                    @if($request->requestFiller)
                                        <a href="{{ route('profile.show', $request->requestFiller->username ) }}">
                                            {{ $request->requestFiller->username }}
                                        </a>
                                        
                                        <br>
                                        <a href="{{ $request->filled_url }}"
                                           class="btn btn-sm btn-success editData"
                                           data-formtype="newPage">
                                            View File
                                        </a>
                                        
                                        @can('manage-request-reset')
                                            <a href="{{ route('requests.reset', $request->id) }}"
                                           class="btn btn-sm btn-warning"
                                           onclick="event.preventDefault();
                                                 document.getElementById('reset-request-form-{{$request->id}}').submit();">
                                            Reset
                                            </a>
                                            <form id="reset-request-form-{{$request->id}}" action="{{ route('requests.reset', $request->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('PATCH')
                                            </form>
                                        @endcan
                                            
                                        @if(Auth::user()->id == $request->filled_by)
                                            <a href="{{ route('profile.show', $request->requestFiller->username ) }}"
                                               class="btn btn-sm btn-info editData"
                                               data-id="{{ $request->id }}"
                                               data-route="{{ route('requests.edit', $request->id) }}"
                                               data-formtype="newPage">
                                                <i class="zmdi zmdi-edit"></i>
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('requests.edit', $request->id) }}"
                                            class="btn btn-sm btn-info"
                                        >
                                            Fill This Request
                                        </a>
                                    @endif
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
