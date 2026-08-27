jQuery(document).ready(function ($) {
    $('.pgppw-action-button').on('click', function (e) {
        $('.pgppw-review-notice').fadeOut();
        e.preventDefault();

        const action = $(this).data('action');
        const reviewUrl = (typeof pgppwAjax !== 'undefined' && pgppwAjax.review_url)
                ? pgppwAjax.review_url
                : 'https://wordpress.org/support/plugin/payment-gateway-for-phonepe-and-for-woocommerce/reviews/#new-post';

        if (action === 'reviewed') {
            window.open(reviewUrl, '_blank');
        }

        $.post(pgppwAjax.ajax_url, {
            action: 'pgppw_handle_review_action',
            review_action: action,
            nonce: pgppwAjax.nonce
        }, function (response) {
            if (response && response.success) {
                $('.pgppw-review-notice').fadeOut();
            } else {
                console.error(response && response.data ? response.data : 'Unknown error');
            }
        });
    });
});
