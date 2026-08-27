(function ($) {
    function togglePlaceOrderButtonForPhonePe() {
        const selectedPaymentMethod = $('input[name="payment_method"]:checked').val();
        const currency = pgppw_js.currency_code;
        if ((selectedPaymentMethod === 'pgppw_phonepe' || selectedPaymentMethod === 'easy_razorpay') && currency !== 'INR') {
            $('#place_order').hide();
        } else {
            $('#place_order').show();
        }
    }
    $(document).ready(function () {
        togglePlaceOrderButtonForPhonePe();
        $(document.body).on('change', 'input[name="payment_method"]', togglePlaceOrderButtonForPhonePe);
        $(document.body).on('updated_checkout', togglePlaceOrderButtonForPhonePe);
    });
})(jQuery);
