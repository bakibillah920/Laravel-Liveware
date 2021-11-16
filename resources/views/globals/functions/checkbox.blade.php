<script defer>
    <!--   Variables JS   -->
    var body =  $('body');

    /*body.on('click', '.check_uncheck', function () {
        if( $(this).is(':checked')) {
            $(this).closest('tr').css('background', '#e0fffa');
        }else{
            $(this).closest('tr').css('background', 'transparent');
        }
    });*/

    // Check All || UnCheck All
    body.on('click', '.checkAll', function() {
        $(".check_uncheck").prop('checked', this.checked);
    });

    // Show || Hide removeAll btn if checked is > 1 else hide
    body.on('change', '.checkAll, .check_uncheck', function() {
        if($('.check_uncheck').filter(':checked').length > 1){
            $('.deleteAllData').removeAttr('hidden');
        }else{
            $('.deleteAllData').attr('hidden', true);
        }

        $('.check_uncheck').each(function (index, value){
            if ($('.check_uncheck').is(':checked')) {
                if( $(this).is(':checked')) {
                    $(this).closest('tr').css('background', '#e0fffa');
                }else{
                    $(this).closest('tr').css('background', 'transparent');
                }
            }else{
                $(this).closest('tr').css('background', 'transparent');
            }
        });

    });

</script>
