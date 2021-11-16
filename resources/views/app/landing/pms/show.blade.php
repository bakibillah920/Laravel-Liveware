@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow mt-3">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-10">
                                    <h4 class="m-0 float-left">Showing Mail:</h4>
                                </div>
                                <div class="col-lg-2">
                                    <a href="{{ URL::previous() }}" class="float-right btn btn-outline-warning btn-sm"
                                    >
                                        <i class="zmdi zmdi-arrow-left zmdi-hc-lg"></i> Go Back
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card shadow mt-3">
                        <div class="card-header">
                            <h4 class="m-0 float-left">Subject: {!! $pm->subject !!}</h4>
                        </div>
                        <div class="card-body torrent-detail">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="torrent-description">
                                        <script src="https://www.chd4.com/jscript/txtbbcode.js"></script>
                                        {!! $pm->getDescription() !!}
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
