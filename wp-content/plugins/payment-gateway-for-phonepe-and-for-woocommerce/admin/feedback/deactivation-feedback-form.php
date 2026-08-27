<?php
defined('ABSPATH') || die('Cheatin&#8217; uh?');
$deactivation_url = wp_nonce_url('plugins.php?action=deactivate&amp;plugin=' . rawurlencode(PGPPW_BASENAME), 'deactivate-plugin_' . PGPPW_BASENAME);
?>
<div class="easyphonepe-deactivation-Modal">
    <div class="easyphonepe-deactivation-Modal-header">
        <div>
            <button class="easyphonepe-deactivation-Modal-return deactivation-icon-chevron-left"><?php esc_html_e('Return', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?></button>
            <h2><?php esc_html_e('We’re sorry to see you go! 💔', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?></h2>
        </div>
        <button class="easyphonepe-deactivation-Modal-close deactivation-icon-close"><?php esc_html_e('Close', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?></button>
    </div>
    <div class="easyphonepe-deactivation-Modal-content">
        <div class="easyphonepe-deactivation-Modal-question deactivation-isOpen">
            <p><?php esc_html_e('Can you please tell us why you’re deactivating the plugin? Your feedback helps us make it better.', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?></p>
            <ul>
                <li>
                    <input type="radio" name="reason" id="reason-temporary" value="Temporary Deactivation">
                    <label for="reason-temporary"><?php esc_html_e('Temporary deactivation (troubleshooting)', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?></label>
                </li>
                <li>
                    <input type="radio" name="reason" id="reason-broke" value="Broken Layout">
                    <label for="reason-broke"><?php esc_html_e('Compatibility issue', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?></label>
                    <div class="easyphonepe-deactivation-Modal-fieldHidden">
                        <textarea placeholder="<?php esc_attr_e('Please describe what part of the layout or functionality was affected.', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?>"></textarea>
                    </div>
                </li>
                <li>
                    <input type="radio" name="reason" id="reason-complicated" value="Complicated">
                    <label for="reason-complicated"><?php esc_html_e('Difficult to set up', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?></label>
                    <div class="easyphonepe-deactivation-Modal-fieldHidden">
                        <textarea placeholder="<?php esc_attr_e('What part of the setup was confusing or unclear?', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?>"></textarea>
                    </div>
                </li>
                <li>
                    <input type="radio" name="reason" id="not-provided" value="features not provided">
                    <label for="not-provided"><?php esc_html_e('Missing features', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?></label>
                    <div class="easyphonepe-deactivation-Modal-fieldHidden">
                        <textarea placeholder="<?php esc_attr_e('Which features were you looking for?', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?>"></textarea>
                    </div>
                </li>
                <li>
                    <input type="radio" name="reason" id="reason-other" value="Other">
                    <label for="reason-other"><?php esc_html_e('Other', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?></label>
                    <div class="easyphonepe-deactivation-Modal-fieldHidden">
                        <textarea placeholder="<?php esc_attr_e('Please share why you’re deactivating the PhonePe plugin so we can make improvements.', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?>"></textarea>
                    </div>
                </li>
            </ul>
            <input id="deactivation-reason" type="hidden" value="">
            <input id="deactivation-details" type="hidden" value="">


            <input id="deactivation-reason" type="hidden" value="">
            <input id="deactivation-details" type="hidden" value="">
        </div>
        <p style="margin-top: 20px;">
            <?php esc_html_e('Your privacy is important to us. No personal data is collected with this form—just your valuable feedback and basic system information (such as WordPress and plugin versions) to help us improve our plugin.', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?>
        </p>
    </div>

    <div class="easyphonepe-deactivation-Modal-footer">
        <a href="https://wordpress.org/support/plugin/payment-gateway-for-phonepe-and-for-woocommerce" class="button button-primary" target="_blank" title="<?php esc_attr_e('Visit our support page for assistance', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?>"><?php esc_html_e('Get Support', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?></a>
        <div>
            <a href="<?php echo esc_attr($deactivation_url); ?>" class="button button-primary deactivation-isDisabled" disabled id="easyphonepe-mixpanel-send-deactivation"><?php esc_html_e('Send & Deactivate', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?></a>
        </div>
        <a id="easyphonepe-deactivation-no-reason" href="<?php echo esc_attr($deactivation_url); ?>" class=""><?php esc_html_e('I rather wouldn\'t say', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?></a>
    </div>
</div>
<div class="easyphonepe-deactivation-Modal-overlay"></div>
