jQuery(document).ready(function($) {
    $(document.body).on('change', '#js-delivery input[type=radio]', function () {
        // Prevent a bug in 1.7.5 that forces a carrier selection to go to the next step
        if ($('.js-cart-payment-step-refresh').length) {
            $('.js-cart-payment-step-refresh').remove();
        }
    });
});
