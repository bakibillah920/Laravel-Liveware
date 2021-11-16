@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('Update Password') }}</div>

                <div class="card-body" id="globalModal">

                    <span class="load-overlay"></span>

                    <form action="{{ route('updatePassword') }}" method="post" id="globalForm" class="form-horizontal pageLoad" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="form-group print-error-msg" style="display:none">
                            <a href="{{ route('editPassword') }}" id="redirectPath"></a>
                            <div class="row ml-0 mr-0">

                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="oldPassword" class="col-md-12 col-form-label  text-md-left">{{ __('Old Password') }}</label>

                            <div class="col-md-12">
                                <input id="oldPassword" type="password" class="form-control @error('oldPassword') is-invalid @enderror" name="oldPassword" value="{{ $oldPassword ?? old('oldPassword') }}" required autocomplete="oldPassword" autofocus>

                                @error('oldPassword')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-12 col-form-label  text-md-left">{{ __('New Password') }}</label>

                            <div class="col-md-12">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label  text-md-left">{{ __('Confirm Password') }}</label>

                            <div class="col-md-12">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary form-control updatePassword">
                                    {{ __('Update Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('script')
    <script>

        // Error toaster for storing data
        function printErrorToaster (msg) {
            $.each( msg, function( key, value ) {
                toastr.error(value)
            });
        }
        // Success toaster for storing data
        function printSuccessToaster (msg) {
            $.each( msg, function( key, value ) {
                toastr.success(value)
            });
        }

        // Store record
        $('body').on('click', '.updatePassword', function(e) {
            e.preventDefault();
            let action = $('#globalForm').attr('action');
            let globalModal = $('#globalModal');
            let globalForm = $('#globalForm');
            let globalTable = $('#globalTable');
            let submitBtn = $('#submitBtn');
            // check if the input is valid
            /*if(! globalForm.valid()){
                submitBtn.html('Try again');
                return false;
            }*/

            $('.load-overlay').append(
                '<div class="overlay-wrapper d-flex align-items-center justify-content-center">\n' +
                '                <div class="overlay">\n' +
                '                    <i class="fa fa-refresh fa-spin fa-5x fa-fw"></i>\n' +
                '                    <br>\n' +
                '                    <br>\n' +
                '                    <h4>Your request is being processed!</h4>\n' +
                '                    <h4>Please Wait</h4>\n' +
                '                </div>\n' +
                '            </div>'
            );

            // submitBtn.html('<img src="'+ loadingGif +'" alt="loading" width="25px">');
            $.ajax({
                url: action,
                data:new FormData(globalForm[0]),
                async:false,
                type:'post',
                processData: false,
                contentType: false,
                success: function (data) {

                    setTimeout(function(){

                        // globalModal.html(data);
                        // console.log(data);

                        $('.overlay-wrapper').remove();
                        if($.isEmptyObject(data.error)){

                            printSuccessToaster(data.success);

                            $('.overlay-wrapper').remove();

                            globalForm.trigger("reset");

                            $(".error").attr('hidden', true);

                        }else{
                            $('.overlay-wrapper').remove();
                            // console.log(data);
                            printErrorToaster(data.error);
                            submitBtn.html('Try Again');
                        }

                    }, 2*1000);

                },
                error: function (data) {
                    alert('Operation Failed!');
                    //console.log('Error:', data);
                    $('.overlay-wrapper').remove();
                    printErrorToaster(data.error);
                    submitBtn.html('Try Again');
                }
            });
        });


    </script>
@endpush
