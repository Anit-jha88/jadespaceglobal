var $ = jQuery;

$(document).ready(function () {
    const $deactivationModal = $(".easyphonepe-deactivation-Modal");
    if ($deactivationModal.length) {
        new PhonePeModal($deactivationModal);
    }

    // Handle deactivation without reason
    $("#easyphonepe-deactivation-no-reason").on("click", function (e) {
        e.preventDefault();
        $('.easyphonepe-deactivation-Modal').block({
            message: null,
            overlayCSS: { background: '#fff', opacity: 0.6 }
        });

        const $link = $(this);
        const url = $link.attr("href");
        const data = {
            action: 'easy_phonepe_send_deactivation',
            reason: 'reason-other',
            reason_details: 'other'
        };

        $.post(ajaxurl, data).always(() => {
            window.location.replace(url);
        });
    });

    // Handle deactivation with Mixpanel reason tracking
    $("#easyphonepe-mixpanel-send-deactivation").on("click", function (e) {
        e.preventDefault();
        $('.easyphonepe-deactivation-Modal').block({
            message: null,
            overlayCSS: { background: '#fff', opacity: 0.6 }
        });

        const $button = $('#easyphonepe-mixpanel-send-deactivation');
        const selected = $("input[name='reason']:checked");
        const reason = selected.val();
        const reasonDetails = selected
            .siblings('.easyphonepe-deactivation-Modal-fieldHidden')
            .find('textarea')
            .val();

        if (!reason) {
            alert("Please select a reason before deactivating.");
            return;
        }

        $button.prop('disabled', true).css({
            cursor: 'not-allowed',
            opacity: '0.6'
        });

        const data = {
            action: 'easy_phonepe_send_deactivation',
            reason: reason,
            reason_details: reasonDetails || ''
        };

        $.post(ajaxurl, data).always(() => {
            window.location.replace($button.attr("href"));
        });
    });
});

class PhonePeModal {
    constructor($elem) {
        this.elem = $elem;
        this.overlay = $('.easyphonepe-deactivation-Modal-overlay');
        this.radio = $('input[name=reason]', $elem);
        this.closer = $('.easyphonepe-deactivation-Modal-close, .easyphonepe-deactivation-Modal-cancel', $elem);
        this.returnBtn = $('.easyphonepe-deactivation-Modal-return', $elem);
        this.opener = $('.plugins [data-slug="payment-gateway-for-phonepe-and-for-woocommerce"] .deactivate');
        this.question = $('.easyphonepe-deactivation-Modal-question', $elem);
        this.button = $('.button-primary', $elem);
        this.title = $('.easyphonepe-deactivation-Modal-header h2', $elem);
        this.textFields = $('input[type=text], textarea', $elem);
        this.hiddenReason = $('#deactivation-reason', $elem);
        this.hiddenDetails = $('#deactivation-details', $elem);
        this.titleText = this.title.text();

        this.bindEvents();
    }

    bindEvents() {
        this.opener.on("click", (e) => {
            e.preventDefault();
            this.open();
        });

        this.closer.on("click", (e) => {
            e.preventDefault();
            this.close();
        });

        this.elem.on("keyup", (event) => {
            if (event.keyCode === 27) {
                this.close(); // ESC key
            }
        });

        this.returnBtn.on("click", (e) => {
            e.preventDefault();
            this.returnToQuestion();
        });

        this.radio.on("change", (e) => {
            this.change($(e.currentTarget));
        });

        this.textFields.on("keyup", (e) => {
            const value = $(e.currentTarget).val();
            this.hiddenDetails.val(value);
            if (value !== '') {
                this.button.removeClass('deactivation-isDisabled').removeAttr("disabled");
            } else {
                this.button.addClass('deactivation-isDisabled').attr("disabled", true);
            }
        });
    }

    change($elem) {
        this.hiddenReason.val($elem.val());
        this.hiddenDetails.val('');
        this.textFields.val('');

        $('.easyphonepe-deactivation-Modal-fieldHidden').removeClass('deactivation-isOpen');
        $('.easyphonepe-deactivation-Modal-hidden').removeClass('deactivation-isOpen');

        const $field = $elem.siblings('.easyphonepe-deactivation-Modal-fieldHidden');
        if ($field.length) {
            $field.addClass('deactivation-isOpen');
            $field.find('textarea').focus();
            this.button.addClass('deactivation-isDisabled').attr("disabled", true);
        } else {
            this.button.removeClass('deactivation-isDisabled').removeAttr("disabled");
        }
    }

    returnToQuestion() {
        $('.easyphonepe-deactivation-Modal-fieldHidden, .easyphonepe-deactivation-Modal-hidden').removeClass('deactivation-isOpen');
        this.question.addClass('deactivation-isOpen');
        this.returnBtn.removeClass('deactivation-isOpen');
        this.title.text(this.titleText);
        this.hiddenReason.val('');
        this.hiddenDetails.val('');
        this.radio.prop('checked', false);
        this.button.addClass('deactivation-isDisabled').attr("disabled", true);
    }

    open() {
        this.elem.show();
        this.overlay.show();
        localStorage.setItem('deactivation-hash', '');
    }

    close() {
        this.returnToQuestion();
        this.elem.hide();
        this.overlay.hide();
    }
}
