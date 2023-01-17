(function($) {
    $(document).ready(function(e) {
        $(".serp-more").on("click", function(e) {
            e.preventDefault();
            targetLess = '.serph-' + $(this).attr('more');
            targetMore = '.joblg-' + $(this).attr('more');
            $(targetLess).toggle();
            $(targetMore).slideToggle();
            console.log(" TargetLess: " + targetLess + targetMore);
        });

        $(".serp-less").on("click", function(e) {
            e.preventDefault();
            targetLess = '.serph-' + $(this).attr('less');
            targetMore = '.joblg-' + $(this).attr('less');
            $(targetLess).toggle();
            $(targetMore).toggle();
            console.log(" TargetLess: " + targetLess + targetMore);
        });
    });
})(jQuery);