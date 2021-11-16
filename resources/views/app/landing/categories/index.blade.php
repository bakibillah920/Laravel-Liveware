@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow mt-3">
                        <div class="card-header">
                            <h4 class="m-0 float-left">{{ $childCategory->name.' '.$category->name }}</h4>
                        </div>
                        <div class="card-body torrent-list">
                            <table class="table table-striped table-hover display responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Torrent Name</th>
                                    <th class="text-center"><i class="zmdi zmdi-download zmdi-hc-lg"></i></th>
                                    <th class="torrent-list-rating text-center">IMDB Rating</th>
                                    <th class="torrent-list-time text-center">Time</th>
                                    <th class="torrent-list-size text-center">Size</th>
                                    <th class="torrent-list-uploader text-center">Uploader</th>
                                </tr>
                                </thead>
                                <tbody> {{--14 tr--}}
                                    @foreach($uploads as $upload)
                                        <?php $torrent = new \App\Helpers\TorrentRW( public_path('torrents/'.$upload->torrent) ); ?>
                                        <tr class="border-bottom">
                                            <td width="50%" class="torrent-list-title">
                                                <a href="{{ route('landing.categories.show',[$category->slug, $childCategory->slug, $upload->slug]) }}" class="hover-title" title="{{ $upload->name }}"
                                                   onmouseover=" return overlib('<img src={!! asset('torrents/'.$upload->image) !!} ' +
                                                       'width=200 border=0>', CENTER, HAUTO, VAUTO);" onmouseout="return nd();"
                                                >
                                                    <div class=" d-flex align-items-center">
                                                        <span class="list-view-icon" style="height: 25px !important; width: 25px !important;">
                                                            @if($upload->category->parentCategory->icon)
                                                                @if(pathinfo(public_path('images/categories/'.$upload->category->icon), PATHINFO_EXTENSION) === 'svg')
                                                                    {!! file_get_contents(public_path('images/categories/'.$upload->category->parentCategory->icon)) !!}
                                                                @else
                                                                    <img src="{{ asset('images/loading/loading1.gif') }}" data-echo="{{ asset('images/categories/'.$upload->category->parentCategory->icon) }}" alt="{{ $upload->category->parentCategory->name }}">
                                                                @endif
                                                            @else
                                                                {!! file_get_contents(public_path('images/categories/Question Mark.svg')) !!}
                                                            @endif
                                                        </span>
                                                        <h5 class="mt-1 ml-2">
                                                            {!! Str::limit($upload->name, 150) !!}
                                                        </h5>
                                                    </div>

                                                </a>
                                            </td>
                                            <td class="torrent-list-time text-center">
                                                <?php
                                                //                                                header("Content-Disposition", "attachment; filename=".$upload->torrent);
                                                ?>
                                                @auth()
                                                    <a href="{!! asset('torrents/'.$upload->torrent) !!}" download target="_blank">
                                                        <i class="zmdi zmdi-download zmdi-hc-2x"></i>
                                                    </a>
                                                @else
                                                    <a href="JavaScript:void(0)" data-toggle="modal" data-target="#loginNoticeModal"
                                                    >
                                                        <i class="zmdi zmdi-download zmdi-hc-2x"></i>
                                                    </a>
                                                    @include('globals.modal.login_notice')
                                                @endauth
                                            </td>
                                            <td width="12%" class="torrent-list-time text-center">
                                                <?php
                                                if ($upload->rating != '')
                                                {
                                                    echo $upload->rating .'/10';
                                                }else {
                                                    echo 'Movie not found. 😞';
                                                }
                                                ?>
                                            </td>
                                            <td width="12%" class="torrent-list-time text-center">
                                                {!! \Carbon\Carbon::parse($upload->created_at)->format('M d, Y') !!}
                                            </td>
                                            <td width="10%" class="torrent-list-size text-center">
                                                {!! $torrent->size( 2 ) !!}
                                            </td>
                                            <td width="16%" class="torrent-list-uploader text-center">
                                                {!! $upload->is_anonymous == 'on' ? 'Anonymous' : '<a href="'. route('profile.show', $upload->created_by_user->username ) .'">'.$upload->created_by_user->username.'</a>' !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if($uploads->links() != '')
                                <div class="shop_toolbar t_bottom mt-4">
                                    <div class="pagination">
                                        {!! $uploads->links() !!}
                                    </div>
                                </div>
                            @endif
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
                                                    @if($recommend->rating != '')
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $recommend->rating > 0 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $recommend->rating > 1 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $recommend->rating > 2 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $recommend->rating > 3 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $recommend->rating > 4 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $recommend->rating > 5 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $recommend->rating > 6 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $recommend->rating > 7 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $recommend->rating > 8 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $recommend->rating > 9 ? 'star':'star-outline' }}"></i></a></li>
                                                        @else
                                                            {{ 'No rating available 😞' }}
                                                        @endif
                                                </ul>
                                            </div>
                                            <div class="price_box">
                                                <small>by: <strong>{!! $recommend->is_anonymous == 'on' ? 'Anonymous': '<a href="'. route('profile.show', $recommend->created_by_user->username ) .'">'.$recommend->created_by_user->username.'</a>' !!}</strong></small>
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
                                                <img class="img-w-70 img-h-80" src="{{ asset('torrents/'.$latest->category->parentCategory->slug.'/'.$latest->category->slug.'/'.$latest->image) }}" alt="{{ $latest->slug }}">
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
                                                    @if ($upload->rating != '')
                                                        <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $upload->rating > 0 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $upload->rating > 1 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $upload->rating > 2 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $upload->rating > 3 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $upload->rating > 4 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $upload->rating > 5 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $upload->rating > 6 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $upload->rating > 7 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $upload->rating > 8 ? 'star':'star-outline' }}"></i></a></li>
                                                            <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $upload->rating > 9 ? 'star':'star-outline' }}"></i></a></li>
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
    
@endsection
