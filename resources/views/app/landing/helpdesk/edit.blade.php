@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mt-3">
                <div class="card-header">
                    <h4 class="m-0 float-left"> Answer Question</h4>

                    <a href="{{ URL::previous() }}" class="float-right btn btn-outline-warning btn-sm"
                    >
                        <i class="zmdi zmdi-arrow-left zmdi-hc-lg"></i> Go Back
                    </a>
                </div>
                <div class="card-body torrent-list" id="globalModal">
                    <span class="load-overlay"></span>

                    <form action="{{ route('help.update', $help->id) }}" method="post" id="globalForm" class="form-horizontal pageLoad" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="form-group print-error-msg" style="display:none">
                            <a href="{{ route('help.index') }}" id="redirectPath"></a>
                            <div class="row ml-0 mr-0">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="bmd-label-floating" for="name"> Request By : </label>
                                    <div class="border p-3">{{ $help->requester ? $help->requester->username : 'Guest' }}</div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="bmd-label-floating" for="name"> Request Date : </label>
                                    <div class="border p-3">{{ \Carbon\Carbon::parse($help->created_at)->format('d M, y - g:i A') }}</div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="bmd-label-floating" for="Subject"> Subject : </label>
                                    <div class="border p-3">{{ $help->subject }}</div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="bmd-label-floating" for="description"> Question : </label>
                                    <div class="border p-3">{{ $help->getQuestion() }}</div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="bmd-label-floating" for="answer"> Answer the question : </label>
                                    <textarea name="answer" id="answer">{{ $help->getAnswer() }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 ml-auto">
                                <a href="{{ URL::previous() }}" id="closeBtn" class="form-control btn btn-danger float-left pt-2 pb-2 mr-1"><i class="zmdi zmdi-arrow-left zmdi-hc-lg"></i> Go Back</a>
                            </div>
                            <div class="col-lg-2">
                                <button type="button" id="submitBtn" class="storeData form-control btn btn-success pt-2 pb-2 ml-1"><i class="zmdi zmdi-check zmdi-hc-lg"></i> Give Answer</button>
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
        $('#submitBtn').on('click', function () {
            $("#answer").sync();
        })
    </script>

@endpush

