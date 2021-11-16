@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mt-3">
                <div class="card-header p-3 m-0">
                    <h4 class="m-0">Pinned Torrents</h4>
                </div>
                <div class="card-body product_carousel column6 owl-carousel p-0 bg-white">
                    @foreach($pinned as $pin)
                        <?php
                        /*if ($pin->imdbKey)
                        {
                            $IMDB = new IMDB($pin->imdbKey->key);
                        }*/
//                        $torrent = new \App\Helpers\TorrentRW( public_path('torrents/'.$pin->torrent) );
                        ?>
                        <div class="single_product p-0 bg-white">
                            <div class="product_thumb">
                                <a href="{{ route('landing.categories.show', [$pin->category->parentCategory->slug, $pin->category->slug, $pin->slug]) }}">
                                    <img class="column6-slider" src="{{ asset('torrents/'.$pin->image) }}" alt="{{ $pin->name }}">
                                </a>
                                {{--<div class="label_product">
                                    <span class="label_sale">sale</span>
                                </div>--}}
                            </div>
                            <div class="product_content">
                                @if($pin->imdbKey)
                                    <div class="product_rating">
                                        <ul>
                                            @if($pin->rating != '')
                                                {{--                                                {{ $IMDB->getRating() .'/10' }}--}}
                                                <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $pin->rating > 0 ? 'star':'star-outline' }}"></i></a></li>
                                                <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $pin->rating > 1 ? 'star':'star-outline' }}"></i></a></li>
                                                <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $pin->rating > 2 ? 'star':'star-outline' }}"></i></a></li>
                                                <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $pin->rating > 3 ? 'star':'star-outline' }}"></i></a></li>
                                                <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $pin->rating > 4 ? 'star':'star-outline' }}"></i></a></li>
                                                <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $pin->rating > 5 ? 'star':'star-outline' }}"></i></a></li>
                                                <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $pin->rating > 6 ? 'star':'star-outline' }}"></i></a></li>
                                                <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $pin->rating > 7 ? 'star':'star-outline' }}"></i></a></li>
                                                <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $pin->rating > 8 ? 'star':'star-outline' }}"></i></a></li>
                                                <li><a href="JavaScript:void(0)"><i class="zmdi zmdi-{{ $pin->rating > 9 ? 'star':'star-outline' }}"></i></a></li>
                                            @else
                                                <div class="product_rating">
                                                    <ul>
                                                        <li><a href="JavaScript:void(0)">{{ 'No rating available 😞' }}</a></li>
                                                    </ul>
                                                </div>
                                            @endif
                                        </ul>
                                    </div>
                                @else
                                    <div class="product_rating">
                                        <ul>
                                            <li><a href="JavaScript:void(0)">{{ 'No rating available 😞' }}</a></li>
                                        </ul>
                                    </div>
                                @endif
                                <div class="product_name pl-2 pr-2" style="height: 35px; margin-top: 5px !important; overflow: hidden;">
                                    <a href="{{ route('landing.categories.show', [$pin->category->parentCategory->slug, $pin->category->slug, $pin->slug]) }}" title="{{ $pin->name }}"><h3>{!! Str::limit($pin->name, 40) !!}</h3></a>

                                    @can('manage-pin-delete')
                                        <a href="JavaScript:void(0)"
                                           class="btn btn-danger btn-just-icon btn-sm unpinTorrent"
                                           data-id="{{ $pin->pin->id }}"
                                           title="unpin Torrent" onclick="unpinTorrent()"
                                           style="z-index: 9999; top: 20px; right: 20px; position: absolute;">
                                                <span class="material-icons">
                                                    push_pin
                                                </span>
                                        </a>
                                    @endcan
                                    @push('script')
                                        <script>
                                            $('body').on('click', '.unpinTorrent', function () {
                                                $.ajax({
                                                    headers: {
                                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                    },
                                                    {{--url: "{{ route('pins.destroy', $pin->pin->id) }}",--}}
                                                    url: "pins/"+$(this).data('id'),
                                                    data: { id:$('.unpinTorrent_{{ $pin->pin->id }}').data('id') },
                                                    type: 'DELETE',
                                                    // _method: 'DELETE',

                                                    success: function (data) {
                                                        // $('.pinSection').load( ' .pins' )
                                                        location.reload();
                                                    },
                                                    /*error: function (data) {
                                                        console.log('error:' + data);
                                                        alert('Failed to Pin!')
                                                    }*/
                                                });
                                            })
                                        </script>
                                    @endpush
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-9" id="shoutBoxContainer">

                    <div class="row">

                        <div class="col-lg-12">
                            <div class="card shadow mt-3">
                                <div class="card-header">
                                    <h4 class="m-0 float-left" style="width: 90% !important;">Notice Box</h4>
                                    @can('manage-noticebox')
                                        <span class="updateOption">
                                            <a href="{{ route('noticeBoxEdit') }}"
                                               class="btn btn-warning btn-just-icon float-right"
                                               data-formsize="large">
                                                <i class="zmdi zmdi-edit"></i>
                                            </a>
                                        </span>
                                    @endcan


                                </div>
                                <div class="card-body noticeBoxWrapper">
                                    <div class="noticeBox">
                                        <?php
                                            $content = file_get_contents(base_path('./resources/views/app/landing/noticeBox/content.blade.php'));
                                            $bbcode = new \App\Helpers\Bbcode();
                                            $linkify = new \App\Helpers\Linkify();
                                            ?>
                                        {!! $bbcode->parse($linkify->linky($content)) !!}
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-12">
                            @include('app.landing.shouts.index')

                            {{--<chat-box></chat-box>

                            <script src="{{ mix('js/app.js') }}"></script>--}}

                            <div class="row">
                                <div class="col-lg-12" id="shoutBoxContainer">
                                    <div class="card shadow mt-3">
                                        <div class="card-header">
                                            <h4 class="m-0 float-left">Torrent List</h4>
                                        </div>
                                        <div class="card-body p-1" id="shoutBox">
                                            <div class="card-body torrent-list p-0" style="width: 100%;">
                                                <table class="table table-striped table-hover table-responsive">
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
                                                                if ($upload->imdbKey)
                                                                {
                                                                    // $IMDB = new IMDB($upload->imdbKey->key);
                                                                    if ($upload->rating) {
                                                                        echo $upload->rating .'/10';
                                                                    } else {
                                                                        echo 'Movie not found. 😞';
                                                                    }
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
                        </div>

                    </div>

                </div>

                <div class="col-lg-3 ml-auto">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow mt-3">
                                <div class="small_product_area">
                                    <div class="card-header">
                                        <h4 class="m-0">Recommended Torrents</h4>
                                    </div>
                                </div>
                                <div class="card-body sidebar m-2 p-0 bg-white">
                                    <div class="{{--small_sidebar_wrapper--}}">
                                        @foreach($recommended as $recommend)
                                            <?php
                                            /*if ($recommend->imdbKey)
                                            {
                                                $IMDB = new IMDB($recommend->imdbKey->key);
                                            }*/
                                            //                                $torrent = new Torrent( public_path('torrents/file/'.$upload->category->parentCategory->name.'/'.$upload->category->name.'/'.$upload->torrent) );
                                            ?>
                                            <div class="small_sidebar_items p-2">
                                                <div class="product_thumb">
                                                    <a href="{{ route('landing.categories.show', [$recommend->category->parentCategory->slug, $recommend->category->slug, $recommend->slug]) }}">
                                                        <img class="img-w-70 img-h-80" src="{{ asset('images/loading/loading1.gif') }}" data-echo="{{ asset('torrents/'.$recommend->image) }}" alt="{{ $recommend->name }}">
                                                    </a>
                                                </div>
                                                <div class="product_content">
                                                    <div class="product_name">
                                                        <a href="{{ route('landing.categories.show', [$recommend->category->parentCategory->slug, $recommend->category->slug, $recommend->slug]) }}" title="{{ $recommend->name }}">
                                                            <h3 class="m-0">{{ Str::limit($recommend->name, 40) }}</h3>
                                                        </a>
                                                    </div>
                                                    <div class="product_rating m-0">
                                                        <ul>
                                                            @if($recommend->rating != '')
                                                                    {{--                                                {{ $IMDB->getRating() .'/10' }}--}}
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
                                                        <small>by: <strong>{!! $recommend->is_anonymous == 'on' ? 'Anonymous' : '<a href="'. route('profile.show', $recommend->created_by_user->username ) .'">'.$recommend->created_by_user->username.'</a>' !!}</strong></small>
                                                        <span class="recommendSection">
                                                            <span class="recommends">
                                                                @if(!$recommend->recommend)
                                                                    @can('manage-recommend-create')
                                                                        <a href="JavaScript:void(0)"
                                                                           class="btn btn-success btn-just-icon btn-sm recommendTorrent"
                                                                           data-id="{{ $recommend->id }}"
                                                                           {{--data-delete="{{ $upload->id }}"--}} title="Recommend Torrent" onclick="recommendTorrent()">
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
                                                                           class="btn btn-danger btn-just-icon btn-sm unrecommendTorrent" id="unrecommendTorrent_{{$recommend->recommend->id}}"
                                                                           data-id="{{ $recommend->recommend->id }}"
                                                                           {{--data-delete="{{ $upload->id }}"--}} title="unpin Torrent" {{--onclick="unrecommendTorrent()"--}}>
                                                                            <span class="material-icons">
                                                                                campaign
                                                                            </span>
                                                                        </a>
                                                                    @endcan
                                                                    @push('script')
                                                                        <script>
                                                                            $('body').on('click', '.unrecommendTorrent', function () {
                                                                                {{--alert({{ $recommend->recommend->id }});--}}
                                                                                $.ajax({
                                                                                    headers: {
                                                                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                                    },
                                                                                    // url: "{{ route('recommends.destroy', $recommend->recommend->id) }}",
                                                                                    url: "recommends/"+$(this).data('id'),
                                                                                    // data: { id:$(this).data('id') },
                                                                                    type: 'DELETE',
                                                                                    // _method: 'DELETE',

                                                                                    success: function (data) {
                                                                                        // $('.pinSection').load( ' .pins' )
                                                                                        location.reload();
                                                                                    }/*,
                                                                                    error: function (data) {
                                                                                        console.log('error:' + data);
                                                                                        alert('Failed to Pin!')
                                                                                    }*/
                                                                                });
                                                                            })
                                                                        </script>
                                                                    @endpush
                                                                    <script>
                                                                    function unrecommendTorrent() {
                                                                        alert($(this).data('id'));
                                                                        /*$.ajax({
                                                                            headers: {
                                                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                            },
                                                                            url: "{{ route('recommends.destroy', $recommend->recommend->id) }}",
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
                                                                        });*/
                                                                    }
                                                                </script>
                                                                @endif
                                                            </span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--<div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12" id="shoutBoxContainer">
                    <div class="card shadow mt-3">
                        <div class="card-header">
                            <h4 class="m-0 float-left">Notice Box</h4>
                        </div>
                        <div class="card-body" id="shoutBox">
                        </div>
                    </div>
                </div>
            </div>
        </div>--}}
    </div>
@endsection

@section('script')
    <script>

        /*var x = document.getElementById("newShoutAudio");

        function playAudio() {
            x.play();
        }

        function pauseAudio() {
            x.pause();
        }*/

        $('.shoutCreate').on('click', function (e) {
            e.preventDefault();

            $("#message").sync();

            let shoutForm = $('#shoutForm');
            let action = shoutForm.attr('action');
            let shoutBox = $('#shoutForm');
            let shoutCreate = $('.shoutCreate');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: action,
                data:new FormData(shoutForm[0]),
                async:false,
                type:'post',
                processData: false,
                contentType: false,
                success: function (data) {

                    if($.isEmptyObject(data.error))
                    {

                        $(".wysibb-body").html(''); // shoutbox

                        $("#shoutForm").trigger("reset");

                        $('.shout-box').replaceWith($(' .shout-box',data));

                        $(".error").attr('hidden', true);

                    }
                    else
                    {
                        // printErrorMsg(data.error);
                        printErrorToaster(data.error);
                    }
                    // console.log(data);

                },
                error: function (data) {
                    alert('Could not send the message! Please use the help desk to inform us about this...');
                    //console.log('Error:', data);
                    $('.overlay-wrapper').remove();
                }
            });
        });


        $(document).ready(function (e) {


            setInterval(function(){

                let action = '{{ route('shouts.newShout') }}';

                let maxShout = 0;
                $('.shouts').each(function() {
                    let shoutId = parseInt($(this).data('shoutid'));
                    maxShout = (shoutId > maxShout) ? shoutId : maxShout;
                });

                if (maxShout > 0)
                {

                    // alert(maxShout);
                    // setTimeout(function(){
                    // $('.shoutBoxWrapper').load(' .shout-box');

                    // alert(maxShout);

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: action,
                        data: {maxShout:maxShout},
                        type:'post',

                        /*async:false,
                        processData: false,
                        contentType: false,*/
                        success: function (data) {

                            if($.isEmptyObject(data.error))
                            {

                                if (data.hasNew === 'true')
                                {
                                    // alert('updated');

                                    $('audio #newShoutAudio').attr('src', data.hasNew);
                                    $('audio').get(0).load();
                                    $('audio').get(0).play();

                                    $('.shoutBoxWrapper').load(' .shout-box');

                                }
                                // console.log(data)

                            }
                            else
                            {
                                // printErrorMsg(data.error);
                                printErrorToaster(data.error);
                            }
                            // console.log(data.hasNew);

                        },
                        /*error: function (data) {
                            alert('Operation Failed!');
                            //console.log('Error:', data);
                            $('.overlay-wrapper').remove();
                        }*/
                    });
                }

            }, 7*1000);

        });
    </script>


    <script>
        /*const app = new Vue({
            el: '#app',
            data: {
                shouts: {},
                commentBox: '',
                user: {!! Auth::check() ? Auth::user()->toJson() : 'null' !!}
        },
        mounted() {
            this.getComments();
            this.listen();
        },
        methods: {
            getComments() {
                axios.get('/shouts')
                    .then((response) => {
                        this.shouts = response.data
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            postComment() {
                axios.post('/shouts', {
                    // api_token: this.user.api_token,
                    shout: this.commentBox // body
                })
                    .then((response) => {
                        this.shouts.unshift(response.data);
                        this.commentBox = '';
                    })
                    .catch((error) => {
                        console.log(error);
                    })
            },
            listen() {
                Echo.private('shout-broadcast')
                    .listen('.newshout', function(shout) {
                        console.log('connected');
                        this.shouts.unshift(shout);
                    });

            },
        }
    })*/
    </script>
@endsection
