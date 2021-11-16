@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mt-3">
                <div class="card-header">
                    <h4 class="m-0 float-left">Send Mail</h4>

                    <a href="{{ URL::previous() }}" class="float-right btn btn-outline-warning btn-sm"
                    >
                        <i class="zmdi zmdi-arrow-left zmdi-hc-lg"></i> Go Back
                    </a>
                </div>
                <div class="card-body torrent-list" id="globalModal">
                    <span class="load-overlay"></span>

                    <form action="{{ route('mail-box.store') }}" method="post" id="globalForm" class="form-horizontal pageLoad" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group print-error-msg" style="display:none">
                            <a href="{{ route('mail-box.index') }}" id="redirectPath"></a>
                            <div class="row ml-0 mr-0">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="bmd-label-floating" for="receiver"> Receiver : </label>
                                    <select name="receiver[]" class="form-control select2" multiple>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{$receiver->id == $user->id ? 'selected':''}}>{{ $user->username }} [ Role: {{ $user->roles[0]->name }} ]</option>
                                        @endforeach
                                    </select>
                                    <small><span class="text-danger">Note: </span>Multiple users can be selected!</small>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="bmd-label-floating" for="receiver"> Subject : </label>
                                    <input type="text" class="form-control" name="subject" placeholder="Subject...">
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="bmd-label-floating" for="receiver"> Description : </label>
                                    <textarea name="description" id="description" cols="30" rows="10"></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-2 ml-auto">
                                <a href="{{ URL::previous() }}" id="closeBtn" class="form-control btn btn-danger float-left pt-2 pb-2 mr-1"><i class="zmdi zmdi-arrow-left zmdi-hc-lg"></i> Go Back</a>
                            </div>
                            <div class="col-lg-2">
                                <button type="button" id="submitBtn" class="storeData form-control btn btn-success pt-2 pb-2 ml-1"> Send</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{--<div class="col-lg-12">
            <div class="card shadow mt-3">
                <div class="card-header">
                    Sent Items to "username"
                </div>
                <div class="card-body">
                    @foreach($old_pms as $old_pm)
                        <div class="border {{ $old_pm->reciever_id == Auth::user()->id ? 'bg-info':''}}">
                            {{ $old_pm->subject }}
                            <hr>
                            {{ $old_pm->getDescription() }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>--}}
    </div>

@endsection

@push('script')

    <script>
        imdbURL = $('#imdbURL').val();
        $('#imdbSearch').on('click', function (e) {
            e.preventDefault();

            let imdbURL = $('#imdbURL').val();
            let action = "{{ route('check_imdb') }}";
            let imdbResult = $('#imdbResult');

            $('.load-overlay').append(
                '<div class="overlay-wrapper text-info d-flex align-items-center justify-content-center">\n' +
                '                <div class="overlay">\n' +
                '                    <i class="fa fa-refresh fa-spin fa-5x fa-fw"></i>\n' +
                '                    <br>\n' +
                '                    <br>\n' +
                '                    <h4>Your request is being processed!</h4>\n' +
                '                    <h4>Please Wait</h4>\n' +
                '                </div>\n' +
                '            </div>'
            );

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: action,
                data: { imdbURL:imdbURL },
                type:'post',

                success: function (data) {

                    setTimeout(function(){

                        $('.overlay-wrapper').remove();

                        console.log('success:' + data);

                        if (data !== 'n/A')
                        {
                            imdbResult.replaceWith(
                                '<span id="imdbResult"><small class="imdb-success text-success"><strong>Match found: </strong>'+data+' (Original Title)</small></span>'
                            );
                            $('.imdb-search-warning').css('display','none');
                            $('.imdb-search-success').css('display','block');
                            $('.imdb-search-danger').css('display','none');
                        }

                        if (data === 'n/A')
                        {
                            imdbResult.html(
                                '<span id="imdbResult"><small class="imdb-success text-danger"><strong>No Match Found. 😞</strong></small></span>'
                            );
                            $('.imdb-search-warning').css('display','none');
                            $('.imdb-search-success').css('display','none');
                            $('.imdb-search-danger').css('display','block');
                        }

                    }, 2*1000);
                },
                error: function (data) {
                    console.log('error:' + data);
                    $('.overlay-wrapper').remove();
                }
            });
        });
    </script>

    <script>
        // Initialize Datatable
        $(document).ready( function () {
            $('#datatable').DataTable();
        } );

        $('#submitBtn').on('click', function () {
            $("#description").sync();
        })
    </script>

@endpush

