<script defer>
    // Show record
    $('body').on('click', '.showData', function() {
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
            modalTitle.html('Show Record');
            closeBtn.html('Close');
            submitBtn.attr('hidden', true);
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

</script>
