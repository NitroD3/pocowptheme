(function ($) {
    "use strict";
    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction('frontend/element_ready/poco-products-tabs.default', ($scope) => {
            let $tabs = $scope.find('.elementor-tabs');
            let $contents = $scope.find('.elementor-tabs-content-wrapper');
            let $carousel = $('.woocommerce-carousel ul, .poco-carousel', $scope);
            $contents.find('.elementor-tab-content').hide();
            // Active tab
            $contents.find('.elementor-active').show();

            var windowsize = $(window).width();

            $(window).resize(function () {
                var windowsize = $(window).width();
            });
            if (windowsize > 767) {
                $tabs.find('.elementor-tab-title').on('click', function (e) {
                    e.preventDefault();
                    $tabs.find('.elementor-tab-title').removeClass('elementor-active');
                    $contents.find('.elementor-tab-content').removeClass('elementor-active').hide();
                    $(this).addClass('elementor-active');
                    let id = $(this).attr('aria-controls');
                    $contents.find('#' + id).addClass('elementor-active').show();
                    $carousel.slick('refresh');
                });
            } else {
                $tabs.find('.elementor-tab-title').on('click', function (e) {
                    e.preventDefault();
                    if ($(this).hasClass('elementor-active')) {
                        $(this).removeClass('elementor-active');
                        let id = $(this).attr('aria-controls');
                        $contents.find('#' + id).removeClass('elementor-active').slideUp();
                    } else {
                        $tabs.find('.elementor-tab-title').removeClass('elementor-active');
                        $contents.find('.elementor-tab-content').removeClass('elementor-active').slideUp();
                        $(this).addClass('elementor-active');
                        let id = $(this).attr('aria-controls');
                        $contents.find('#' + id).addClass('elementor-active').slideToggle();
                        $carousel.slick('refresh');
                    }
                });
            }
            if (typeof data === 'undefined') {
                return;
            }
            $carousel.slick(
                {
                    dots: data.navigation === 'both' || data.navigation === 'dots' ? true : false,
                    arrows: data.navigation === 'both' || data.navigation === 'arrows' ? true : false,
                    infinite: data.loop,
                    speed: 300,
                    slidesToShow: parseInt(data.items),
                    autoplay: data.autoplay,
                    autoplaySpeed: parseInt(data.autoplayTimeout),
                    slidesToScroll: 1,
                    lazyLoad: 'ondemand',
                    responsive: [
                        {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: parseInt(data.items_tablet),
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: parseInt(data.items_mobile),
                            }
                        }
                    ]
                }
            ).on('setPosition', function (event, slick) {
                slick.$slides.css('height', slick.$slideTrack.height() + 'px');
            });

        });
    });
})(jQuery);