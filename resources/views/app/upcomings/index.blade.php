@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mt-3">
                <div class="card-header">
                    <h4 class="m-0 float-left">Upcoming Torrents</h4>

                    @can('manage-upcoming-create')
                        <a href="{{ route('upcoming.create') }}" class="float-right btn btn-outline-success btn-sm">
                            <i class="zmdi zmdi-plus-square zmdi-hc-lg"></i> New Upcoming Torrent
                        </a>
                    @endcan
                </div>
                <div class="card-body torrent-list" id="globalTable">
                    <table id="datatable" class="table border table-striped table-hover table-responsive display responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Name</th>
                            <th>Category</th>
{{--                            <th>Image</th>--}}
                            {{--<th>IMDB Rating</th>--}}
                            <th>Upload Date</th>
                            <th>Created By</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody> {{--14 tr--}}
                        @foreach($upcomings as $upcoming)
                            <tr data-row="{{ $upcoming->id }}">
                                <td class="text-center" width="30px">{{ $loop->iteration }}</td>
                                <td class="">
                                    <a href="{{ route('upcoming.show', $upcoming->id) }}">
                                        {{ $upcoming->name }}
                                    </a>
                                </td>
                                <td class="">{{ $upcoming->category->name }}</td>
                                {{--<td class="">
                                    <a href="JavaScript:void(0)"
                                       class="showData"
                                       data-id="{{ $upcoming->id }}"
                                       data-route="{{ route('upcoming.show', $upcoming->id) }}"
                                       data-formtype="newPage"
                                       data-formsize="large">
                                        <img src="{{ asset('upcomings/'.$upcoming->image) }}" alt="" width="50px" height="50px">
                                    </a>
                                </td>--}}
                                {{--<td class="">
                                    <?php
                                    if ($upcoming->imdbLink)
                                    {
                                        $IMDB = new IMDB($upcoming->imdbLink);
                                        if ($IMDB->isReady) {
                                            echo $IMDB->getRating() .'/10';
                                        } else {
                                            echo 'Movie not found. 😞';
                                        }
                                    }
                                    else
                                        {
                                            echo " 😞 ";
                                        }
                                    ?>
                                </td>--}}
                                <td>{{ \Carbon\Carbon::parse($upcoming->upload_date)->format('d M, Y') }}</td>
                                <td class="">{!! $upcoming->created_by_user->username .'<br>'. \Carbon\Carbon::parse($upcoming->created_at)->format('d M, y - g:i A') !!}</td>
                                <td class="text-center" width="150px">
                                    @can('manage-upcoming-show')
                                        <a href="JavaScript:void(0)"
                                           class="btn btn-info btn-just-icon showData"
                                           data-id="{{ $upcoming->id }}"
                                           data-route="{{ route('upcoming.show', $upcoming->id) }}"
                                           data-formtype="newPage"
                                           data-formsize="large">
                                            <i class="zmdi zmdi-eye"></i>
                                        </a>
                                    @endcan
                                    @can('manage-upcoming-update')
                                        <a href="JavaScript:void(0)"
                                           class="btn btn-warning btn-just-icon editData"
                                           data-id="{{ $upcoming->id }}"
                                           data-route="{{ route('upcoming.edit', $upcoming->id) }}"
                                           data-formtype="newPage"
                                           data-formsize="large">
                                            <i class="zmdi zmdi-edit"></i>
                                        </a>
                                    @endcan
                                    @can('manage-upcoming-delete')
                                        <a href="JavaScript:void(0)"
                                           class="btn btn-danger btn-just-icon deleteData"
                                           data-id="{{ $upcoming->id }}"
                                           data-route="{{ route('upcoming.destroy', $upcoming->id) }}"
                                            {{--data-delete="{{ $upcoming->id }}"--}}>
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
