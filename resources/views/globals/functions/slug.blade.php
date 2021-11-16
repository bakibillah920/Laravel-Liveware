<script defer>
    // Slug Start
    function toSlug(){
        var slug = function(str) {
            var $slug = '';
            var trimmed = $.trim(str);
            $slug = trimmed.replace(/[^a-z0-9-]/gi, '-').
            replace(/-+/g, '-').
            replace(/^-|-$/g, '');
            return $slug.toLowerCase();
        };
        $('body').on('keyup', '.slug-input',function() {
            var takedata = $('.slug-input').val();
            $('.slug-output').val(slug(takedata));
        });
    }
    function forceSlug(){
        var slug = function(str) {
            var $slug = '';
            var trimmed = $.trim(str);
            $slug = trimmed.replace(/[^a-z0-9-]/gi, '-').
            replace(/-+/g, '-').
            replace(/^-|-$/g, '-');
            return $slug.toLowerCase();
        };
        $('body').on('keyup', '.slug-output', function() {
            var takedata = $('.slug-output').val();
            $('.slug-output').val(slug(takedata));
        });
    }
    // Slug End


</script>
