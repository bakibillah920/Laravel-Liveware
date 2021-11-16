@extends('layouts.app')

@section('content')

    @if ($upload->torrent != '')

        <?php
        $torrent = new \App\Helpers\TorrentRW( public_path('torrents/'.$upload->torrent) );
        ?>
    
        @if (count($errors) > 0)
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="alert alert-danger p-3 text-center">{!! $error !!}</li>
                @endforeach
            </ul>
        @endif
    
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow mt-3">
                            <div class="card-header">
                                <h4 class="m-0 float-left" style="width: 82% !important;">Torrent: {!! $upload->name !!}</h4>
    
                                @if (Auth::user())
                                    @if (Auth::user()->id == $upload->user_id)
    
                                        @can('manage-myupload-delete')
                                            <form action="{{ route('uploads.destroy' , $upload->id ) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-just-icon float-right ml-2" value=""><i class="zmdi zmdi-delete"></i></button>
                                            </form>
                                        @endcan
    
                                        @can('manage-myupload-update')
                                            <a href="JavaScript:void(0)"
                                               class="btn btn-warning btn-just-icon editData float-right"
                                               data-id="{{ $upload->id }}"
                                               data-route="{{ route('uploads.edit', $upload->id) }}"
                                               data-formtype="newPage"
                                               data-formsize="large">
                                                <i class="zmdi zmdi-edit"></i>
                                            </a>
                                        @endcan
                                    @else
                                        @can('manage-upload-delete')
                                            <a href="JavaScript:void(0)" class="btn btn-danger float-right ml-2" data-toggle="modal" data-target=".deleteReasonModal"><i class="zmdi zmdi-delete"></i></a>
                                            @include('globals.modal.delete_reason')
                                        @endcan
    
                                        @can('manage-upload-update')
                                            <a href="JavaScript:void(0)"
                                               class="btn btn-warning btn-just-icon editData float-right"
                                               data-id="{{ $upload->id }}"
                                               data-route="{{ route('uploads.edit', $upload->id) }}"
                                               data-formtype="newPage"
                                               data-formsize="large">
                                                <i class="zmdi zmdi-edit"></i>
                                            </a>
                                        @endcan
                                    @endif
    
                                        <span class="pinSection float-left mr-2">
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
    
                                        <span class="recommendSection float-left mr-2">
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
                                                                        alert('Failed to Recommend!')
                                                                    }
                                                                });
                                                            }
                                                    </script>
                                                @else
                                                    @can('manage-recommend-delete')
                                                        <a href="JavaScript:void(0)"
                                                           class="btn btn-danger btn-just-icon btn-sm unrecommendTorrent"
                                                           data-id="{{ $upload->recommend->id }}"
                                                           title="UnRecommend Torrent" onclick="unrecommendTorrent()">
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
                                                                alert('Failed to Recommend!')
                                                            }
                                                        });
                                                    }
                                                </script>
                                                @endif
                                            </span>
                                        </span>
    
                                @endif
    
                            </div>
                            <div class="card-body torrent-detail">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-4">
                                        <div class="input-group">
                                            <img src="{{ asset('images/loading/loading1.gif') }}" data-echo="{{ asset('torrents/'.$upload->image) }}" alt="" style="width: 100% !important; height: 250px !important;">
    
                                            @auth()
                                                <a href="{!! asset('torrents/'.$upload->torrent) !!}" class="btn btn-info form-control" download>
                                                    <i class="zmdi zmdi-download zmdi-hc-lg"></i>
                                                </a>
                                                <a href="{!! $torrent->magnet() !!}" class="btn btn-danger form-control">
                                                    <i class="fa fa-magnet" aria-hidden="true"></i>
                                                </a>
                                            @else
                                                <a href="JavaScript:void(0)" data-toggle="modal" data-target="#loginNoticeModal"
                                                   class="btn btn-info form-control"
                                                >
                                                    <i class="zmdi zmdi-download zmdi-hc-2x"></i>
                                                </a>
                                                <a href="JavaScript:void(0)" data-toggle="modal" data-target="#loginNoticeModal"
                                                   class="btn btn-danger form-control"
                                                >
                                                    <i class="fa fa-magnet" aria-hidden="true"></i>
                                                </a>
                                                @include('globals.modal.login_notice')
                                            @endauth
    
                                        </div>
                                    </div>
                                    <div class="col-lg-9 col-sm-8">
                                        <div class="torrent-quick-info">
                                            @if($upload->imdbDetail)
                                                <div class="torrent-detail">
                                                    <div class="torrent-detail-label float-left">IMDB Rating</div>
                                                    <div class="torrent-detail-value float-right">
                                                        :
                                                            @if($upload->imdbDetail)
                                                                {{ $upload->imdbDetail->rating .'/10' }}
                                                            @else
                                                                {{ 'n/A 😞' }}
                                                            @endif
                                                    </div>
                                                </div>
                                            @endif
    
    
                                                @if($upload->imdbDetail)
                                                    <div class="torrent-detail">
                                                        <div class="torrent-detail-label float-left">Release</div>
                                                        <div class="torrent-detail-value float-right">
                                                            :
                                                            @if($upload->imdbDetail)
                                                                @if($upload->imdbDetail)
                                                                    {{ $upload->imdbDetail->release_date }}
                                                                @else
                                                                    {{ 'n/A 😞' }}
                                                                @endif
                                                            @else
                                                                {{ 'n/A 😞' }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($upload->imdbDetail)
                                                    <div class="torrent-detail">
                                                        <div class="torrent-detail-label float-left">Genre</div>
                                                        <div class="torrent-detail-value float-right">
                                                            :
                                                            @if($upload->imdbDetail)
                                                                {{ $upload->imdbDetail->genre }}
                                                            @else
                                                                {{ 'n/A 😞' }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($upload->imdbDetail)
                                                    <div class="torrent-detail">
                                                        <div class="torrent-detail-label float-left">Director</div>
                                                        <div class="torrent-detail-value float-right">
                                                            :
                                                            @if($upload->imdbDetail)
                                                                @if($upload->imdbDetail)
                                                                    {{ $upload->imdbDetail->director }}
                                                                @else
                                                                    {{ 'n/A 😞' }}
                                                                @endif
                                                            @else
                                                                {{ 'n/A 😞' }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($upload->imdbDetail)
                                                    <div class="torrent-detail">
                                                        <div class="torrent-detail-label float-left">Awards</div>
                                                        <div class="torrent-detail-value float-right">
                                                            :
                                                            @if($upload->imdbDetail)
                                                                @if($upload->imdbDetail)
                                                                    {{ $upload->imdbDetail->awards }}
                                                                @else
                                                                    {{ 'n/A 😞' }}
                                                                @endif
                                                            @else
                                                                {{ 'n/A 😞' }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($upload->resolution != '')
                                                    <div class="torrent-detail">
                                                        <div class="torrent-detail-label float-left">Quality</div>
                                                        <div class="torrent-detail-value float-right">:
                                                            @if($upload->resolution != '')
                                                                {{ $upload->resolution }}
                                                            @else
                                                                {{ 'n/A 😞' }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
    
                                            <div class="torrent-detail">
                                                <div class="torrent-detail-label float-left">File size</div>
                                                <div class="torrent-detail-value float-right">: {!! $torrent->size( 2 ) !!}</div>
                                            </div>
                                            <div class="torrent-detail">
                                                <div class="torrent-detail-label float-left">
                                                    {{--<span class="badge badge-info p-2">Uploader</span>--}}
                                                    <span class="text-info">Category</span>
                                                </div>
                                                <div class="torrent-detail-value float-right">:
                                                    <span class="text-info">
                                                        <a href="{{ route('landing.categories.index',[$upload->category->parentCategory->slug, $upload->category->slug]) }}">
                                                        {{ $upload->category->parentCategory->name.'/'.$upload->category->name }}
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="torrent-detail">
                                                <div class="torrent-detail-label float-left">
                                                    {{--<span class="badge badge-info p-2">Uploader</span>--}}
                                                    <span class="text-info">Upload Date</span>
                                                </div>
                                                <div class="torrent-detail-value float-right">: <span class="text-info"> {{ \Carbon\Carbon::parse($upload->created_at)->format('M d, Y') }}</span></div>
                                            </div>
                                            <div class="torrent-detail">
                                                <div class="torrent-detail-label float-left">
                                                    {{--<span class="badge badge-info p-2">Uploader</span>--}}
                                                    <span class="text-info">Uploaded By</span>
                                                </div>
                                                <div class="torrent-detail-value float-right">: <span class="text-info">{!! $upload->is_anonymous == 'on' ? 'Anonymous':'<a href="'.route('profile.show', $upload->created_by_user->username ).'">' .$upload->created_by_user->username .'</a>' !!}</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card shadow mt-3">
                            <div class="card-header">
                                <h4 class="m-0 float-left">Description</h4>
                            </div>
                            <div class="card-body torrent-detail">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="torrent-description">
                                            <?php
    
                                            ?>
                                            {!! $upload->getDescription() !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--<div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow mt-3">
                            <div class="small_product_area">
                                <div class="card-header">
                                    <h4 class="m-0">Recommended Torrents</h4>
                                </div>
                            </div>
                            <div class="card-body sidebar m-2 p-0 bg-white">
    
                                <div class="small_sidebar_wrapper">
                                    @foreach($recommended as $recommend)
                                        
                                        <div class="small_sidebar_items p-2">
                                            <div class="product_thumb">
                                                <a href="{{ route('landing.categories.show', [$recommend->category->parentCategory->slug, $recommend->category->slug, $recommend->slug]) }}">
                                                    <img class="img-w-70 img-h-80" src="{{ asset('torrents/'.$recommend->category->parentCategory->slug.'/'.$recommend->category->slug.'/'.$recommend->image) }}" alt="{{ $recommend->name }}">
                                                </a>
                                            </div>
                                            <div class="product_content">
                                                <div class="product_name">
                                                    <a href="{{ route('landing.categories.show', [$recommend->category->parentCategory->slug, $recommend->category->slug, $recommend->slug]) }}">
                                                        <h3 class="m-0">{{ Str::limit($recommend->name, 40) }}</h3>
                                                    </a>
                                                </div>
                                                <div class="product_rating m-0">
                                                    <ul>
                                                        @if($recommend->imdbDetail)
                                                            @if($recommend->imdbDetail)
                                                                <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $recommend->imdbDetail->rating > 0 ? 'star':'star-outline' }}"></i></a></li>
                                                                <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $recommend->imdbDetail->rating > 1 ? 'star':'star-outline' }}"></i></a></li>
                                                                <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $recommend->imdbDetail->rating > 2 ? 'star':'star-outline' }}"></i></a></li>
                                                                <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $recommend->imdbDetail->rating > 3 ? 'star':'star-outline' }}"></i></a></li>
                                                                <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $recommend->imdbDetail->rating > 4 ? 'star':'star-outline' }}"></i></a></li>
                                                                <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $recommend->imdbDetail->rating > 5 ? 'star':'star-outline' }}"></i></a></li>
                                                                <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $recommend->imdbDetail->rating > 6 ? 'star':'star-outline' }}"></i></a></li>
                                                                <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $recommend->imdbDetail->rating > 7 ? 'star':'star-outline' }}"></i></a></li>
                                                                <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $recommend->imdbDetail->rating > 8 ? 'star':'star-outline' }}"></i></a></li>
                                                                <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $recommend->imdbDetail->rating > 9 ? 'star':'star-outline' }}"></i></a></li>
                                                            @else
                                                                {{ 'No rating available 😞' }}
                                                            @endif
                                                        @else
                                                            <strong>{{ 'No rating available 😞' }}</strong>
                                                        @endif
                                                    </ul>
                                                </div>
                                                <div class="price_box">
                                                    <small>by: <strong>{!! $upload->is_anonymous == 'on' ? 'Anonymous':'<a href="'.route('profile.show', $upload->created_by_user->username ).'">' .$upload->created_by_user->username .'</a>' !!}</strong></small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    --}}{{--<div class="col-lg-12">
                        <div class="card shadow mt-3">
                            <div class="small_product_area">
                                <div class="card-header">
                                    <h4 class="m-0">Latest Torrents</h4>
                                </div>
                            </div>
                            <div class="card-body sidebar m-2 p-0 bg-white">
    
                                <div class="small_sidebar_wrapper">
    
                                    @foreach($all_latest as $latest)
                                        
                                        <div class="small_sidebar_items p-2">
                                            <div class="product_thumb">
                                                <a href="{{ route('landing.categories.show', [$latest->category->parentCategory->slug, $latest->category->slug, $latest->slug]) }}">
                                                    <img class="img-w-70 img-h-80" src="{{ asset('torrents/'.$latest->category->parentCategory->name.'/'.$latest->category->name.'/'.$latest->image) }}" alt="{{ $latest->name }}">
                                                </a>
                                            </div>
                                            <div class="product_content">
                                                <div class="product_name">
                                                    <a href="{{ route('landing.categories.show', [$latest->category->parentCategory->slug, $latest->category->slug, $latest->slug]) }}">
                                                        <h3 class="m-0">{{ Str::limit($latest->name, 40) }}</h3>
                                                    </a>
                                                </div>
                                                <div class="product_rating m-0">
                                                    <ul>
                                                        @if($latest->imdbDetail)
                                                            --}}{{----}}{{--                                                {{ $IMDB->getRating() .'/10' }}--}}{{----}}{{--
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $latest->imdbDetail->rating > 0 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $latest->imdbDetail->rating > 1 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $latest->imdbDetail->rating > 2 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $latest->imdbDetail->rating > 3 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $latest->imdbDetail->rating > 4 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $latest->imdbDetail->rating > 5 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $latest->imdbDetail->rating > 6 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $latest->imdbDetail->rating > 7 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $latest->imdbDetail->rating > 8 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $latest->imdbDetail->rating > 9 ? 'star':'star-outline' }}"></i></a></li>
                                                        @else
                                                            {{ 'No rating available 😞' }}
                                                        @endif
                                                    </ul>
                                                </div>
                                                <div class="price_box">
                                                    <small>by: <strong>{!! $latest->created_by_user->username !!}</strong></small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>--}}{{--
                </div>
            </div>--}}
        </div>
    @else
    
    <h1>Content Not Found</h1>
    
    @endif

@endsection
