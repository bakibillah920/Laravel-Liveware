@extends('layouts.app')

@section('content')

    <?php
    $IMDB = new IMDB($upcoming->imdbURL);
    ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow mt-3">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-10">
                                    <h4 class="m-0 float-left">Upcoming Torrent: {!! $upcoming->name !!}</h4>
                                </div>
                                <div class="col-lg-2">
                                    <a href="{{ URL::previous() }}" class="float-right btn btn-outline-warning btn-sm"
                                    >
                                        <i class="zmdi zmdi-arrow-left zmdi-hc-lg"></i> Go Back
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body torrent-detail">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <img src="{{ asset('upcomings/'.$upcoming->image) }}" alt="" style="width: 100% !important; height: 250px !important;">
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div class="torrent-quick-info">
                                        <div class="torrent-detail">
                                            <div class="torrent-detail-label float-left">IMDB Rating</div>
                                            <div class="torrent-detail-value float-right">
                                                :
                                                @if($IMDB->isReady)
                                                    {{ $IMDB->getRating() .'/10' }}
                                                @else
                                                    {{ 'n/A 😞' }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="torrent-detail">
                                            <div class="torrent-detail-label float-left">Release</div>
                                            <div class="torrent-detail-value float-right">
                                                :
                                                @if($IMDB->isReady)
                                                    {{ $IMDB->getReleaseDate() }}
                                                @else
                                                    {{ 'n/A 😞' }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="torrent-detail">
                                            <div class="torrent-detail-label float-left">Genre</div>
                                            <div class="torrent-detail-value float-right">
                                                :
                                                @if($IMDB->isReady)
                                                    {{ $IMDB->getGenre() }}
                                                @else
                                                    {{ 'n/A 😞' }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="torrent-detail">
                                            <div class="torrent-detail-label float-left">Director</div>
                                            <div class="torrent-detail-value float-right">
                                                :
                                                @if($IMDB->isReady)
                                                    {{ $IMDB->getDirector() }}
                                                @else
                                                    {{ 'n/A 😞' }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="torrent-detail">
                                            <div class="torrent-detail-label float-left">Awards</div>
                                            <div class="torrent-detail-value float-right">
                                                :
                                                @if($IMDB->isReady)
                                                    {{ $IMDB->getAwards() }}
                                                @else
                                                    {{ 'n/A 😞' }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="torrent-detail">
                                            <div class="torrent-detail-label float-left">Quality</div>
                                            <div class="torrent-detail-value float-right">:
                                                @if($upcoming->resolution != '')
                                                    {{ $upcoming->resolution }}
                                                @else
                                                    {{ 'n/A' }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="torrent-detail">
                                            <div class="torrent-detail-label float-left">
                                                {{--<span class="badge badge-info p-2">Uploader</span>--}}
                                                <span class="text-info">Uploader</span>
                                            </div>
                                            <div class="torrent-detail-value float-right">: <span class="text-info">{!! $upcoming->created_by_user->username !!}</span></div>
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
                                        <script src="https://www.chd4.com/jscript/txtbbcode.js"></script>
                                        {!! $upcoming->getDescription() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
