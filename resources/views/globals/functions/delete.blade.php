<script defer>

    // Error toaster for storing data
    function printErrorToaster (msg) {
        $.each( msg, function( key, value ) {
            toastr.error(value)
        });
    }

    var body =  $('body');
    var url = $(location).attr('href');

    // Delete single record
    $('body').on('click', '.deleteData', function(e) {
        var route = $(this).data('route');
        var el = this;
        var dataDelete = $(this).data('delete');
        if ($('.deleteReasonModal').hasClass('show'))
        {
            let deleteForm = $('#deleteForm');
            let formData = new FormData(deleteForm[0]);
        }
        bootbox.confirm("Do you really want to delete record?", function (result) {
            if (result) {
                // AJAX Request
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: route,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                    },

                    success: function (data) {

                        // $('.overlay-wrapper').remove();
                        if($.isEmptyObject(data.error))
                        {
                            if ($(el).closest('tr').hasClass('child'))
                            {

                                // if ()

                                // alert($(el).closest('tr').index('tr') - 1);
                                let index1 = $(el).closest('tr').index('tr');
                                let index2 = $(el).closest('tr').index('tr') - 1;

                                $("tr").eq(index1).css('background', '#f5d8d8');
                                $("tr").eq(index1).fadeOut(800, function () {
                                    $(this).remove();
                                });

                                $("tr").eq(index2).css('background', '#f5d8d8');
                                $("tr").eq(index2).fadeOut(800, function () {
                                    $(this).remove();
                                });
                            }
                            // Removing row from HTML Table
                            else
                            {
                                $(el).closest('tr').css('background', '#f5d8d8');
                                $(el).closest('tr').fadeOut(800, function () {
                                    $(this).remove();
                                });
                            }
                        }
                        else
                        {

                            if ($('#deleteReasonModal').hasClass('show'))
                            {
                                alert('open');
                            }
                            else{
                                alert('close');
                            }

                            // printErrorMsg(data.error);
                            printErrorToaster(data.error);
                        }
                        // console.log(data);

                    },
                    error: function (data) {
                        alert('Operation Failed!');
                        //console.log('Error:', data);
                    }

                });
            }
        });
    });

</script>
