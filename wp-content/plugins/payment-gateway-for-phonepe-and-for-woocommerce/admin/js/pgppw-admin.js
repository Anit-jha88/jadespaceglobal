class EPPhonePeAdmin {
    setupEnvironmentSwitching() {
        const $envSelector = jQuery('#woocommerce_pgppw_phonepe_environment');
        const sandboxFields = [
            '#woocommerce_pgppw_phonepe_sandbox_client_id',
            '#woocommerce_pgppw_phonepe_sandbox_client_secret',
            '#woocommerce_pgppw_phonepe_sandbox_client_version'
        ].map(id => jQuery(id).closest('tr'));
        const liveFields = [
            '#woocommerce_pgppw_phonepe_live_client_id',
            '#woocommerce_pgppw_phonepe_live_client_secret',
            '#woocommerce_pgppw_phonepe_live_client_version'
        ].map(id => jQuery(id).closest('tr'));
        const toggleFields = function () {
            const isSandbox = ($envSelector.val() === 'test' || $envSelector.val() === 'sandbox');
            if (isSandbox) {
                sandboxFields.forEach($row => $row.show());
                liveFields.forEach($row => $row.hide());
            } else {
                sandboxFields.forEach($row => $row.hide());
                liveFields.forEach($row => $row.show());
            }
        };
        $envSelector.on('change', toggleFields);
        toggleFields(); // initial
    }

    setupCollapsibles() {
        const headerSel = 'h3.pgppw-phonepe-collapsible-section';
        const updateSections = () => {
            jQuery(headerSel).each((_, el) => {
                const $el = jQuery(el);
                $el.removeClass('active');
                $el.nextUntil(headerSel).hide();
            });
            const lastId = localStorage.getItem('pgppw_last_opened_section');
            if (lastId) {
                const $last = jQuery(`#${lastId}`);
                if ($last.length) {
                    $last.addClass('active')
                         .nextUntil(headerSel)
                         .show();
                    return;
                }
            }
            const $first = jQuery(headerSel).first();
            if ($first.length) {
                $first.addClass('active')
                      .nextUntil(headerSel)
                      .show();
            }
        };
        jQuery(headerSel).on('click', function () {
            const $this = jQuery(this);
            const isActive = $this.hasClass('active');
            jQuery(`${headerSel}.active`)
                .removeClass('active')
                .nextUntil(headerSel)
                .hide();
            if (!isActive) {
                $this
                    .addClass('active')
                    .nextUntil(headerSel)
                    .show();
                if ($this.attr('id')) {
                    localStorage.setItem('pgppw_last_opened_section', $this.attr('id'));
                }
            } else {
                localStorage.removeItem('pgppw_last_opened_section');
            }
        });
        updateSections();
    }
}
jQuery(document).ready(function () {
    const epPhonePeAdmin = new EPPhonePeAdmin();
    epPhonePeAdmin.setupEnvironmentSwitching();
    epPhonePeAdmin.setupCollapsibles();
});
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('table.form-table').forEach(function (table) {
        if (table.querySelectorAll('tr').length === 0) {
            table.style.display = 'none';
        }
    });
});
jQuery(document).ready(function ($) {
    $(document).on('click', '.pgppw-register-btn', function () {
        const url = $(this).data('url');
        window.open(url, '_blank'); // opens in new tab
    });
    $(document).on('click', '.pgppw-copy-link', function () {
        const $btn = $(this);
        const link = $btn.data('link');
        navigator.clipboard.writeText(link).then(() => {
            $btn.text('Copied!').prop('disabled', true);
            setTimeout(() => {
                $btn.text('Copy Link').prop('disabled', false);
            }, 1500);
        });
    });
});