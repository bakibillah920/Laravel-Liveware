@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mt-3">
                <div class="card-header">
                    <h4 class="m-0 float-left">Uploads - <span class="text-danger">History</span></h4>
                    @can('manage-upload-delete-history')

                        <a href="{{ route('uploads.history.delete-all') }}"
                           class="float-right btn btn-outline-danger btn-sm"
                           onclick="event.preventDefault();
                               document.getElementById('upload-history-all-remove-form').submit();">
                            <i class="zmdi zmdi-delete zmdi-hc-lg"></i> Clear History
                        </a>
                        <form id="upload-history-all-remove-form" action="{{ route('uploads.history.delete-all') }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>

                        {{--<a href="{{ route('uploads.history.delete-all') }}" class="float-right btn btn-outline-danger btn-sm">
                            <i class="zmdi zmdi-delete zmdi-hc-lg"></i> Clear History
                        </a>--}}
                    @endcan
                </div>

                <div class="ml-auto mr-auto mt-3">
                    {!! $uploads->links() !!}
                </div>
                <div class="card-body torrent-list" id="globalTable">
                    <table id="datatable" class="table border table-striped table-hover display responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Category</th>
{{--                            <th>Torrent</th>--}}
{{--                            <th>IMDB Url</th>--}}
                            <th>IMDB Rating</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Updated By</th>
                            <th>Deleted By</th>
                            <th>Delete Reason</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($uploads as $upload)
                            <?php
//                            $torrent = new \App\Helpers\TorrentRW( public_path('torrents/'.$upload->category->parentCategory->slug.'/'.$upload->category->slug.'/'.$upload->torrent) );
                            $torrent = new \App\Helpers\TorrentRW( public_path('torrents/'.$upload->torrent) );
                            ?>
                            <tr data-row="{{ $upload->id }}">
                                <td class="text-center" width="30px">
{{--                                    {{ $loop->iteration }}--}}
                                    {{ ($uploads ->currentpage()-1) * $uploads ->perpage() + $loop->index + 1 }}
                                </td>
                                <td class="">
                                    <a href="{{ route('landing.categories.show',[$upload->category->parentCategory->slug, $upload->category->slug, $upload->slug]) }}" class="hover-title" title="{{ $upload->name }}" >
                                        {{ $upload->name }}
                                    </a>
                                </td>
                                <td class=""><img src="{{ asset('images/loading/loading1.gif') }}" data-echo="{{ asset('torrents/'.$upload->image) }}" alt="" width="50px" height="50px"></td>
                                <td class="">{{ $upload->category->name }}</td>
                                <td class="">
                                    <?php
                                    if ($upload->imdbKey)
                                    {
                                        $IMDB = new IMDB($upload->imdbKey->key);
                                        if ($IMDB->isReady) {
                                            echo $IMDB->getRating() .'/10';
                                        } else {
                                            echo 'Movie not found. 😞';
                                        }
                                    }
                                    ?>
                                </td>
                                <td class="">
                                    {{ $upload->approval->is_approved }}
                                </td>
                                <td class="">
                                    @if($upload->is_anonymous == 'on')
                                        {!! $upload->created_by_user->username .' (Anonymous)<br>'. \Carbon\Carbon::parse($upload->created_at)->format('d-M-y - g:i A') !!}
                                    @else
                                        <a href="{{ route('profile.show', $upload->created_by_user->username) }}">
                                            {!! $upload->created_by_user->username .'<br>'. \Carbon\Carbon::parse($upload->created_at)->format('d-M-y - g:i A') !!}
                                        </a>
                                    @endif
                                </td>
                                <td class="">
                                    {!! $upload->updated_by != '' && $upload->created_at != $upload->updated_at ? '<a href="'. route('profile.show', $upload->created_by_user->username ) .'">'.$upload->created_by_user->username.'</a>' .'<br>'. \Carbon\Carbon::parse($upload->updated_at)->format('d-M-y - g:i A') : 'None' !!}
                                </td>
                                <td class="">
                                    <a href="{{ route('profile.show', $upload->created_by_user->username )  }}"> {{ $upload->deleted_by_user->username }}</a> <br>  {!! \Carbon\Carbon::parse($upload->updated_at)->format('d-M-y - g:i A') !!}
                                </td>
                                <td class="">
                                    {{ $upload->delete_reason }}
                                </td>
                                <td class="text-center" width="150px">

                                    @can('manage-upload-delete-history')
                                        {{--<a href="{{ route('uploads.history.restore-single', $upload->id) }}"
                                           class="btn btn-success btn-just-icon">
                                            <i class="zmdi zmdi-check"></i>
                                        </a>--}}

                                        <a href="{{ route('uploads.history.restore-single', $upload->id) }}"
                                           class="btn btn-success btn-just-icon"
                                           onclick="event.preventDefault();
                                               document.getElementById('upload-history-single-restore-form-{{$upload->id}}').submit();">
                                            <i class="zmdi zmdi-check"></i>
                                        </a>
                                        <form id="upload-history-single-restore-form-{{$upload->id}}" action="{{ route('uploads.history.restore-single', $upload->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('POST')
                                        </form>

                                    @endcan
                                    @can('manage-upload-delete-history')


                                        <a href="{{ route('uploads.history.delete-single', $upload->id) }}"
                                           class="btn btn-danger btn-just-icon"
                                           onclick="event.preventDefault();
                                                 document.getElementById('upload-history-single-remove-form-{{$upload->id}}').submit();">
                                            <i class="zmdi zmdi-delete"></i>
                                        </a>
                                        <form id="upload-history-single-remove-form-{{$upload->id}}" action="{{ route('uploads.history.delete-single', $upload->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                        {{--<a href="JavaScript:void(0)"
                                           class="btn btn-danger btn-just-icon deleteData"
                                           data-id="{{ $upload->id }}"
                                           data-route="{{ route('uploads.history.delete-single', $upload->id) }}"
                                            data-delete="{{ $upload->id }}">
                                            <i class="zmdi zmdi-delete"></i>
                                        </a>--}}
                                    @endcan

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="ml-auto mr-auto mb-3">
                    {!! $uploads->links() !!}
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')

    <script>
        // Initialize Datatable
        $(document).ready( function () {
            $('#datatable').DataTable({
                paging:false,
                // searching:false,
                list:false,
                info:false,
            });
        } );
    </script>
    <script>
        // Initialize Datatable
        /*$(document).ready(function(){
            $('#datatable').DataTable({
                'processing': true,
                'serverSide': true,
                // 'serverMethod': 'get',
                'serverMethod': 'post',

                'ajax': {

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    // 'url':'/uploads',
                    'url':'/uploads/paginated-data',
                    dataSrc:""
                },
                "columns": [
                    { data: 'id' },
                    { data: 'name' },
                    { data: 'image' },
                    { data: 'category_id' },
                    { data: 'id' },
                    { data: 'status' },
                    { data: 'created_by' },
                    { data: 'updated_by' },
                    { data: 'id' },
                ]
            });

        });*/
    </script>

@endpush
