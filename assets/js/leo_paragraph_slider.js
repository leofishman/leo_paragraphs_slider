(function ($, drupalSettings) {
    $(document).ready(function () {

        $('.home-slider').slick({
            arrows: true,
            dots: true,
            responsive: [
                {
                    breakpoint: 900,
                    settings: {
                        dots:false
                    }
                }
            ]
        });
    });

})(jQuery, drupalSettings);
