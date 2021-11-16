@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mt-3">
                <div class="card-header">
                    <h4 class="m-0 float-left">Uploads</h4>
                    @can('manage-myupload-create')
                        <a href="{{ route('my-uploads.create') }}" class="float-right btn btn-outline-success btn-sm">
                            <i class="zmdi zmdi-plus-square zmdi-hc-lg"></i> New Torrent Upload
                        </a>
                    @endcan
                </div>

                <div class="row mt-6">
                    <div class="col-lg-4 text-center">
                        <a href="{{ route('my-uploads.index') }}" class="btn btn-info">All</a>
                        <a href="{{ route('my-pending-uploads') }}" class="btn btn-warning disabled">Pending</a>
                        <a href="{{ route('my-approved-uploads') }}" class="btn btn-success">Approved</a>
                        <a href="{{ route('my-disapproved-uploads') }}" class="btn btn-danger">Disapproved</a>
                    </div>

                    <div class="col-lg-6">
                        {!! $uploads->links() !!}
                    </div>
                </div>

                <div class="card-body torrent-list" id="globalTable">
                    <table id="datatable" class="table border table-striped table-hover display responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Category</th>
                            <th>Image</th>
                            <th>Name</th>
                            {{--                            <th>Torrent</th>--}}
                            {{--                            <th>IMDB Url</th>--}}
                            <th>IMDB Rating</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Updated By</th>
                            {{--                            <th>Deleted By</th>--}}
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
                                    if ($upload->imdbDetail)
                                    {
//                                        $IMDB = new IMDB($upload->imdbKey->key);
                                        if ($upload->imdbDetail) {
                                            echo $upload->imdbDetail->rating .'/10';
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
                                        <a href="{{ route('profile/'.$upload->created_by_user->username) }}">{!! $upload->created_by_user->username .'<br>'. \Carbon\Carbon::parse($upload->created_at)->format('d-M-y - g:i A') !!}</a>
                                    @endif
                                </td>
                                <td class="">
                                    {!! $upload->updated_by != '' && $upload->created_at != $upload->updated_at ? '<a href="'. route('profile.show', $upload->created_by_user->username ) .'">'.$upload->created_by_user->username.'</a>' .'<br>'. \Carbon\Carbon::parse($upload->updated_at)->format('d-M-y - g:i A') : 'None' !!}
                                </td>
                                <td class="text-center" width="150px">
                                    <a class="btn btn-info btn-just-icon" href="{!! asset('torrents/'.$upload->torrent) !!}" download>
                                        <i class="zmdi zmdi-download zmdi-hc-lg"></i>
                                    </a>
                                    @can('manage-myupload-show')
                                        <a href="JavaScript:void(0)"
                                           class="btn btn-info btn-just-icon showData"
                                           data-id="{{ $upload->id }}"
                                           data-route="{{ route('uploads.show', $upload->id) }}"
                                           data-formtype="newPage"
                                           data-formsize="large">
                                            <i class="zmdi zmdi-eye"></i>
                                        </a>
                                    @endcan
                                    @can('manage-myupload-update')
                                        <a href="JavaScript:void(0)"
                                           class="btn btn-warning btn-just-icon editData"
                                           data-id="{{ $upload->id }}"
                                           data-route="{{ route('uploads.edit', $upload->id) }}"
                                           data-formtype="newPage"
                                           data-formsize="large">
                                            <i class="zmdi zmdi-edit"></i>
                                        </a>
                                    @endcan
                                    @can('manage-myupload-delete')
                                        <a href="JavaScript:void(0)"
                                           class="btn btn-danger btn-just-icon deleteData"
                                           data-id="{{ $upload->id }}"
                                           data-route="{{ route('uploads.destroy', $upload->id) }}"
                                           data-delete="{{ $upload->id }}">
                                            <i class="zmdi zmdi-delete"></i>
                                        </a>
                                    @endcan

                                    <span class="pinSection">
                                        <span class="pins">
                                            @if(!$upload->pin)
                                                @can('manage-pin-create')
                                                    <a href="JavaScript:void(0)"
                                                       class="btn btn-success btn-just-icon btn-sm pinTorrent_{{ $upload->id }}"
                                                       data-id="{{ $upload->id }}"
                                                       data-delete="{{ $upload->id }}" title="Pin Torrent" onclick="pinTorrent()">
                                                        <span class="material-icons">
                                                            push_pin
                                                        </span>
                                                    </a>
                                                @endcan
                                                <script>
                                                        function pinTorrent() {
                                                            {{--alert($('.pinTorrent_{{ $upload->id }}').data('id'));--}}
                                                            $.ajax({
                                                                headers: {
                                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                },
                                                                url: "{{ route('pins.store') }}",
                                                                data: { torrent:$('.pinTorrent_{{ $upload->id }}').data('id') },
                                                                type:'post',

                                                                success: function (data) {
                                                                    // $('.pinSection').load( ' .pins' )
                                                                    location.reload();
                                                                },
                                                                error: function (data) {
                                                                    console.log('error:' + data);
                                                                    alert('Failed to Pin!')
                                                                }
                                                            });
                                                        }
                                                </script>
                                            @else
                                                @can('manage-pin-delete')
                                                    <a href="JavaScript:void(0)"
                                                       class="btn btn-danger btn-just-icon btn-sm unpinTorrent_{{ $upload->pin->id }}"
                                                       data-id="{{ $upload->pin->id }}"
                                                       title="unpin Torrent" onclick="unpinTorrent()">
                                                        <span class="material-icons">
                                                            push_pin
                                                        </span>
                                                    </a>
                                                @endcan
                                                <script>
                                                function unpinTorrent() {
                                                    $.ajax({
                                                        headers: {
                                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                        },
                                                        url: "{{ route('pins.destroy', $upload->pin->id) }}",
                                                        data: { id:$('.unpinTorrent_{{ $upload->pin->id }}').data('id') },
                                                        type: 'POST',
                                                        data: {
                                                            _method: 'DELETE'
                                                        },

                                                        success: function (data) {
                                                            // $('.pinSection').load( ' .pins' )
                                                            location.reload();
                                                        },
                                                        error: function (data) {
                                                            console.log('error:' + data);
                                                            alert('Failed to Pin!')
                                                        }
                                                    });
                                                }
                                            </script>
                                            @endif
                                        </span>
                                    </span>

                                    <span class="recommendSection">
                                        <span class="recommends">
                                            @if(!$upload->recommend)
                                                @can('manage-recommend-create')
                                                    <a href="JavaScript:void(0)"
                                                       class="btn btn-success btn-just-icon btn-sm recommendTorrent"
                                                       data-id="{{ $upload->id }}"
                                                       title="Recommend Torrent" onclick="recommendTorrent()">
                                                        <span class="material-icons">
                                                            campaign
                                                        </span>
                                                    </a>
                                                @endcan
                                                <script>
                                                        function recommendTorrent() {
                                                            $.ajax({
                                                                headers: {
                                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                },
                                                                url: "{{ route('recommends.store') }}",
                                                                data: { torrent:$('.recommendTorrent').data('id') },
                                                                type:'post',

                                                                success: function (data) {
                                                                    // $('.pinSection').load( ' .pins' )
                                                                    location.reload();
                                                                },
                                                                error: function (data) {
                                                                    console.log('error:' + data);
                                                                    alert('Failed to Pin!')
                                                                }
                                                            });
                                                        }
                                                </script>
                                            @else
                                                @can('manage-recommend-delete')
                                                    <a href="JavaScript:void(0)"
                                                       class="btn btn-danger btn-just-icon btn-sm unrecommendTorrent"
                                                       data-id="{{ $upload->recommend->id }}"
                                                       title="unpin Torrent" onclick="unrecommendTorrent()">
                                                        <span class="material-icons">
                                                            campaign
                                                        </span>
                                                    </a>
                                                @endcan
                                                <script>
                                                function unrecommendTorrent() {
                                                    $.ajax({
                                                        headers: {
                                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                        },
                                                        url: "{{ route('recommends.destroy', $upload->recommend->id) }}",
                                                        data: { id:$('.unrecommendTorrent').data('id') },
                                                        type: 'POST',
                                                        data: {
                                                            _method: 'DELETE'
                                                        },

                                                        success: function (data) {
                                                            // $('.pinSection').load( ' .pins' )
                                                            location.reload();
                                                        },
                                                        error: function (data) {
                                                            console.log('error:' + data);
                                                            alert('Failed to Pin!')
                                                        }
                                                    });
                                                }
                                            </script>
                                            @endif
                                        </span>
                                    </span>

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

@endpush
