@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow mt-3">
                        <div class="card-header">
                            <h4 class="m-0 float-left">Search result of "{{ $query }}"</h4>
                        </div>
                        <div class="card-body torrent-list">
                            @if (count($results) < 1)
                                    <h3>Content not found!</h3>
                            @else
                                <table class="table table-striped table-hover display table-responsive responsive nowrap" cellspacing="0" width="100%">
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
                                    @foreach($results as $result)
                                        <?php
                                        $torrent = new \App\Helpers\TorrentRW( public_path('torrents/'.$result->torrent) );
                                        /*if ($result->imdbKey)
                                        {
                                            $IMDB = new IMDB($result->imdbKey->key);
                                        }*/
                                        ?>
                                        @can('navcategory-'.$result->category->slug)
                                            <tr class="border-bottom">
                                                <td width="50%" class="torrent-list-title">
                                                    <a href="{{ route('landing.categories.show',[$result->category->parentCategory->slug, $result->category->slug, $result->slug]) }}" class="hover-title" title="Torrent Name"
                                                       onmouseover=" return overlib('<img src={!! asset('torrents/'.$result->image) !!} ' +
                                                           'width=200 border=0>', CENTER);" onmouseout="return nd();"
                                                    >
                                                        <div class=" d-flex align-items-center">
                                                                <span class="list-view-icon" style="height: 25px !important; width: 25px !important;">
                                                                    @if($result->category->parentCategory->icon)
                                                                        <img src="{{ asset('images/categories/'.$result->category->parentCategory->icon) }}" alt="{{ $result->category->parentCategory->name }}">
                                                                    @else
                                                                        {!! file_get_contents(public_path('images/categories/Question Mark.svg')) !!}
                                                                    @endif
                                                                </span>
                                                            <h5 class="mt-1 ml-2">
                                                                {!! Str::limit($result->name, 150) !!}
                                                            </h5>
                                                        </div>
    
                                                    </a>
                                                </td>
                                                <td class="torrent-list-time text-center">
                                                    <?php
                                                    //                                                header("Content-Disposition", "attachment; filename=".$upload->torrent);
                                                    ?>
                                                    @auth()
                                                        <a href="{!! asset('torrents/'.$result->torrent) !!}" download target="_blank">
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
                                                    if ($result->imdbDetail)
                                                    {
                                                        if ($result->imdbDetail) {
                                                            echo $result->imdbDetail->rating .'/10';
                                                        } else {
                                                            echo '😞';
                                                        }
                                                    }else {
                                                        echo '😞';
                                                    }
                                                    ?>
                                                </td>
                                                <td width="12%" class="torrent-list-time text-center">
                                                    {!! \Carbon\Carbon::parse($result->created_at)->format('M d, Y') !!}
                                                </td>
                                                <td width="10%" class="torrent-list-size text-center">
                                                    {!! $torrent->size( 2 ) !!}
                                                </td>
                                                <td width="16%" class="torrent-list-uploader text-center">
                                                    {!! $result->is_anonymous == 'on' ? 'Anonymous' : '<a href="'. route('profile.show', $result->created_by_user->username ) .'">'.$result->created_by_user->username.'</a>' !!}
                                                </td>
                                            </tr>
                                        @endcan
                                    @endforeach
                                    </tbody>
                                </table>
                                
                            @endif
                            {{--@if($results->links() != '')
                                <div class="shop_toolbar t_bottom mt-4">
                                    <div class="pagination">
                                        {!! $results->links() !!}
                                    </div>
                                </div>
                            @endif--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
