<script defer>

    // Error toaster for storing data
    function printErrorToaster (msg) {
        $.each( msg, function( key, value ) {
            toastr.error(value)
        });
    }

    // Success toaster for storing data
    function printSuccessToaster (msg) {
        toastr.success('Updated Successfully!')
    }

    // Error toaster for storing data
    /*function printErrorMsg (msg) {
        $(".print-error-msg").find(".row").html('');
        $(".print-error-msg").css('display','block');
        $.each( msg, function( key, value ) {
            $(".print-error-msg").find('.row').append(
                '<div class="col-md-12 ml-auto mr-auto pt-1 pb-1 mt-1 mb-1 alert alert-danger">' +
                '    <button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
                '        <i class="fas fa-times"></i>\n' +
                '    </button>' +
                '    <span>\n' +
                '        <b>' +value+ '</b>'+
                '    </span>' +
                '</div>'
            );
        });
    }*/

    // Create record
    $('body').on('click', '.editData', function() {
        let route = $(this).data('route');
        let formType = $(this).data('formtype');
        let formSize = $(this).data('formsize');
        let globalModal = $('#globalModal');
        let modalDialog = $(".modal-dialog");
        let modalTitle = $(".modal-title");
        let closeBtn = $("#closeBtn");
        let submitBtn = $("#submitBtn");
        if(formType == "modal"){
            globalModal.modal({
                backdrop: 'static',
                keyboard: false
            });
            if(formSize == "fullScreen"){
                modalDialog.addClass("modal-fs");
            }
            if(formSize == "extraLarge"){
                modalDialog.addClass("modal-xl");
            }
            if(formSize == "large"){
                modalDialog.addClass("modal-lg");
            }
            if(formSize == "medium"){
                modalDialog.addClass("modal-md");
            }
            if(formSize == "small"){
                modalDialog.addClass("modal-sm");
            }
            modalTitle.html('Edit Record');
            submitBtn.attr('hidden', false);
            closeBtn.html('Close');
            submitBtn.html('Update');
            submitBtn.addClass('updateData');
        }
        else
        {
            location.href = route;
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: route,
            // dataType: 'json',
            dataType: 'html',
            success: function (data) {
                globalModal.find('.loadForm').html(data);
                //console.log(response);
            },
            error: function (data) {
                //console.log('Error:', data);
            }
        });
    });

    // Store record
    $('body').on('click', '.updateData', function(e) {
        e.preventDefault();
        let action = $('#globalForm').attr('action');
        let loadingGif = "/images/loading-gif/6.gif";
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

                    $('.overlay-wrapper').remove();
                    if($.isEmptyObject(data.error)){

                        if (globalForm.hasClass('pageLoad'))
                        {
                            location.href = redirectPath;
                        }

                        else {

                            printSuccessToaster(data.success);

                            $('.overlay-wrapper').remove();

                            globalModal.trigger("reset");
                            globalModal.modal('hide');

                            globalTable.replaceWith($('#globalTable', data));

                            $('tr').each(function (index, value) {
                                if ($(this).data('row') === $('#globalForm').data('id')) {
                                    $(this).css('background', '#d8f5e0');
                                    $(this).delay(1500).queue(function (next) {
                                        $(this).css('background', 'transparent');
                                        next();
                                    });
                                }
                            });

                            $("#datatable").DataTable();

                            $(".error").attr('hidden', true);
                        }
                    }else{
                        $('.overlay-wrapper').remove();
                        // console.log(data);
                        printErrorToaster(data.error);
                        submitBtn.html('Try Again');
                    }

                }, .5*1000);

            },
            error: function (data) {

                if (data.error != '')
                {
                    alert('Error: '+ data.error);
                    printErrorToaster(data.error);
                }
                else
                {
                    alert('Error: Opps something went wrong! Please try again...');
                }

                //console.log('Error:', data);
                $('.overlay-wrapper').remove();
                submitBtn.html('Save');
            }
        });
    });


</script>
