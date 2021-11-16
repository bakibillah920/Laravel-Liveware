@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mt-3">
                <div class="card-header">
                    <h4 class="m-0 float-left"> Upcoming Torrent</h4>

                    <a href="{{ URL::previous() }}" class="float-right btn btn-outline-warning btn-sm"
                    >
                        <i class="zmdi zmdi-arrow-left zmdi-hc-lg"></i> Go Back
                    </a>
                </div>
                <div class="card-body torrent-list" id="globalModal">
                    <span class="load-overlay"></span>

                    <form action="{{ route('upcoming.update', $upcoming->id) }}" method="post" id="globalForm" class="form-horizontal pageLoad" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="form-group print-error-msg" style="display:none">
                            <a href="{{ route('upcoming.index') }}" id="redirectPath"></a>
                            <div class="row ml-0 mr-0">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="bmd-label-floating" for="category"> Category : </label>
                                    <select name="category" class="form-control select2">
                                        @foreach($categories as $category)
                                            @if(count($category->childCategories) > 0)
                                                <optgroup label="{{ $category->name }}">
                                                    @foreach($category->childCategories as $childCategory)
                                                        <option value="{{ $childCategory->id }}"{{ $childCategory->id == $upcoming->category_id ? 'selected':'' }}>{{ $childCategory->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating" for="name"> Name : </label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ $upcoming->name }}" required="true">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="bmd-label-floating" for="upload_date"> Upload Date : </label>
                                    <input type="date" name="upload_date" id="upload_date" class="form-control" value="{{ $upcoming->upload_date }}" required="true">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="bmd-label-floating" for="image"> Image : </label>
                                    <input type="file" name="image" id="image" class="form-control">
                                    <small>Existing image: {{ $upcoming->image }}</small>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label class="bmd-label-floating" for="imdbURL"> IMDB URL : </label>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text imdb-search-warning" style="display: block;"><i class="zmdi zmdi-hourglass text-warning"></i></span>
                                            <span class="input-group-text imdb-search-success" style="display: none;"><i class="zmdi zmdi-check-all text-success"></i></span>
                                            <span class="input-group-text imdb-search-danger" style="display: none;"><i class="zmdi zmdi-close text-danger"></i></span>
                                        </div>
                                        <input type="url" name="imdbURL" id="imdbURL" class="form-control" value="{{ $upcoming->imdbURL }}">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="imdbSearch" style="cursor: pointer;"><i class="zmdi zmdi-search text-info"></i></span>
                                        </div>
                                    </div>
                                    <span id="imdbResult"><small class="imdb-warning text-warning"><strong>Enter IMDB URL & Click on the search button to check IMDB Database</strong></small></span>
                                    {{--<small class="imdb-warning text-warning" hidden><strong>Click on the search button to check IMDB Database</strong></small>
                                    <small class="imdb-success text-success" hidden><strong>Match found: </strong>Movie name</small>
                                    <small class="imdb-danger text-danger" hidden><strong>No Match found</strong></small>--}}
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="bmd-label-floating" for="description"> Description : </label>
                                    <textarea name="description" id="description" class="description form-control" rows="20">{!! $upcoming->description !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 ml-auto">
                                <a href="{{ URL::previous() }}" id="closeBtn" class="form-control btn btn-danger float-left pt-2 pb-2 mr-1"><i class="zmdi zmdi-arrow-left zmdi-hc-lg"></i> Go Back</a>
                            </div>
                            <div class="col-lg-2">
                                <button type="button" id="submitBtn" class="storeData form-control btn btn-success pt-2 pb-2 ml-1"><i class="zmdi zmdi-check-all zmdi-hc-lg"></i> Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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

