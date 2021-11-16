@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">

{{--                    <h1 class="text-center">{{ $category->name }}</h1>--}}

                    {{--@foreach($category->childCategories as $childCategory)
                        @if(count($childCategory->uploads) > 0)

                            <div class="card shadow mt-3">
                                <div class="card-header">
                                    <h4 class="m-0 float-left">{{ $childCategory->name }}</h4>
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
                                            <tbody> --}}{{--14 tr--}}{{--

                                            @foreach($childCategory->uploads as $upload)
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
                                                                                <img src="{{ asset('images/categories/'.$upload->category->parentCategory->icon) }}" alt="{{ $upload->category->parentCategory->name }}">
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
                                                            echo '😞';
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
                                </div>
                            </div>
                        @else
                            <div class="card shadow mt-3">
                                <div class="card-header">
                                    <h4 class="m-0 float-left">{{ $childCategory->name }}</h4>
                                </div>
                                <div class="card-body torrent-list">
                                    <h4 class="text-danger">Nothing Found In {{ $childCategory[0] }} Section!</h4>
                                </div>
                            </div>
                        @endif
                    @endforeach--}}

                    <div class="card shadow mt-3">
                        <div class="card-header">
                            <h4 class="m-0 float-left">{{ $category->name }}</h4>
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
                                            <a href="{{ route('landing.categories.show',[$upload->category->parentCategory->slug, $upload->category->slug, $upload->slug]) }}" class="hover-title" title="{{ $upload->name }}"
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
                                            if ($upload->rating)
                                            {
                                                echo $upload->rating .'/10';
                                            }else 
                                            {
                                                echo '😞';
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
                        </div>
                    </div>
                    <div class="card shadow p-3 mt-3">
                        <div class="ml-auto mr-auto">
                            {{ $uploads->links() }}
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

@endsection
