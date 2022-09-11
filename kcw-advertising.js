jQuery(document).ready(function() {
    var $merch_wrapper = jQuery("div.kcw-advertising-merch-cards-wrapper");
    var $merch_list = jQuery("ul", $merch_wrapper);

    var merch_card_count = jQuery("li", $merch_list).length;
    var merch_card_width = jQuery("li:first-child", $merch_list).outerWidth();


    (function kcw_merch_banner_init() {
        var list_padding = parseInt($merch_list.css("padding-left"));
        var min_container_width = (merch_card_count*merch_card_width)+list_padding+merch_card_width;
        //Set the width of the scrolling container
        $merch_list.css({"min-width": min_container_width});
        $merch_list.css({display : "block", opacity: 1});
        //$merch_list.animate({"opacity" : "1"}, 500);

        max_right_shift = min_container_width-$merch_wrapper.outerWidth();

        autoScrollMerchBanner(2*autoScrollDuration);
    })();

    //This stuff lags / stutters on live site

    var autoScrollDuration = 10000;
    function autoScrollMerchBanner(duration) {
        $merch_list.transit({"margin-left" : -2*merch_card_width}, duration, "linear", shiftMerchBannerElements);
    }
    function shiftMerchBannerElements() {
        var first = jQuery("li:first-child", $merch_list);
        $merch_list.append(first)
        $merch_list.css({"margin-left" : -merch_card_width});
        autoScrollMerchBanner(autoScrollDuration);
    }

    $merch_wrapper.on("mousedown touchstart", function(e) {
    });
    $merch_wrapper.on("mousemove touchmove", function() {
    });
    $merch_wrapper.on("mouseup touchend", function() {
    });
});