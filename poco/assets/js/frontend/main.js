(function ($) {
    'use strict';

    function login_dropdown() {
        $('.site-header-account').mouseenter(function () {
            if (!$('.account-dropdown', this).has('.account-wrap').length) {
                $('.account-dropdown', this).append($('.account-wrap'));
            }
        });

        $(document).on('click', '.site-header-account > a', function (e) {
            e.preventDefault();
            var $parent = $(this).closest('.site-header-account');
            if ($parent.hasClass('active')) {
                $parent.removeClass('active');
            } else {
                $parent.addClass('active');
            }
        }).mouseup(function(e) {
            var container = $(".site-header-account");

            if (!container.is(e.target) && container.has(e.target).length === 0)
            {
                container.removeClass('active');
            }
        });
    }

    function handleWindow() {
        var body = document.querySelector('body');

        if (window.innerWidth > body.clientWidth + 5) {
            body.classList.add('has-scrollbar');
            body.setAttribute('style', '--scroll-bar: ' + (window.innerWidth - body.clientWidth) + 'px');
        } else {
            body.classList.remove('has-scrollbar');
        }
    }

    handleWindow();
    login_dropdown();
})(jQuery);

