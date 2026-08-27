<?php

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

final class PGPPW_Block extends AbstractPaymentMethodType {

    private $gateway;
    protected $name = 'pgppw_phonepe';
    public $icon;

    public function initialize() {
        if (!class_exists('PGPPW_Gateway')) {
            include_once ( PGPPW_PLUGIN_DIR . '/includes/class-pgppw-gateway.php');
        }
        $this->gateway = new PGPPW_Gateway();
    }

    public function is_active() {
        return $this->gateway->is_available();
    }

    public function get_payment_method_script_handles() {
        wp_register_script('pgppw_phonepe-blocks-integration', PGPPW_ASSET_URL . 'checkout-block/pgppw_phonepe.js', array('jquery', 'react', 'wc-blocks-registry', 'wc-settings', 'wp-element', 'wp-i18n', 'wp-polyfill', 'wp-element', 'wp-plugins'), PGPPW_PLUGIN_VERSION, true);
        if (function_exists('wp_set_script_translations')) {
            wp_set_script_translations('pgppw_phonepe-blocks-integration', 'payment-gateway-for-phonepe-and-for-woocommerce');
        }
        return ['pgppw_phonepe-blocks-integration'];
    }

    public function get_payment_method_data() {
        $this->icon = apply_filters('pgppw_phonepe_icon', PGPPW_ASSET_URL . '/public/images/phonepe.png');
        return [
            'title' => $this->gateway->title,
            'description' => $this->gateway->description,
            'supports' => $this->get_supported_features(),
            'icons' => $this->gateway->icon,
            'currency' => get_woocommerce_currency(),
            'placeOrderButtonLabel' => $this->gateway->order_button_text,
            'redirect_icon' => $this->gateway->redirect_icon,
            'manage_woocommerce' => current_user_can('manage_woocommerce') ? 'yes' : 'no',
            'inr_notice' => __('PhonePe is only supported when currency is INR.', 'payment-gateway-for-phonepe-and-for-woocommerce'),
            'admin_guide' => __('To enable PhonePe payments, change your store currency to INR or use a multi-currency plugin.', 'payment-gateway-for-phonepe-and-for-woocommerce'),
            'hide_redirect_icon' => $this->gateway->hide_redirect_icon ? 'yes' : 'no'
        ];
    }
}
