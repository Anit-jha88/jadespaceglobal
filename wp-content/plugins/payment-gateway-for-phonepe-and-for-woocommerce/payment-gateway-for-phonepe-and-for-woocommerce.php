<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Payment Gateway for PhonePe and for Woocommerce
 * Plugin URI:        https://wordpress.org/plugins/payment-gateway-for-phonepe-and-for-woocommerce/
 * Description:       Accept payments through UPI, Cards, and Net Banking — developed by an official PhonePe Partner.
 * Version:           1.0.11
 * Author:            Easy Payment
 * Author URI:        https://profiles.wordpress.org/easypayment/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       payment-gateway-for-phonepe-and-for-woocommerce
 * Requires at least: 5.0
 * Requires PHP: 7.4
 * Requires Plugins: woocommerce
 * Tested up to: 6.9.1
 * WC requires at least: 3.4
 * WC tested up to: 10.4.3
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!defined('PGPPW_PLUGIN_DIR')) {
    define('PGPPW_PLUGIN_DIR', dirname(__FILE__));
}
define('PGPPW_VERSION', time());
if (!defined('PGPPW_ASSET_URL')) {
    define('PGPPW_ASSET_URL', plugin_dir_url(__FILE__));
}
if (!defined('PGPPW_BASENAME')) {
    define('PGPPW_BASENAME', plugin_basename(__FILE__));
}
if (!defined('PGPPW_PLUGIN_VERSION')) {
    define('PGPPW_PLUGIN_VERSION', '1.0.11');
}

function pgppw_activate() {
    set_transient('easy_pgppw_for_woocommerce_redirect', true, 30);
    require_once plugin_dir_path(__FILE__) . 'includes/class-pgppw-activator.php';
    PGPPW_Activator::activate();
}

function pgppw_deactivate() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-pgppw-deactivator.php';
    PGPPW_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'pgppw_activate');
register_deactivation_hook(__FILE__, 'pgppw_deactivate');

require plugin_dir_path(__FILE__) . 'includes/class-pgppw.php';

function pgppw_run() {

    $plugin = new PGPPW();
    $plugin->run();
}

pgppw_run();

add_action('before_woocommerce_init', function () {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__, true);
    }
});

add_action('woocommerce_blocks_loaded', function () {
    try {
        if (!class_exists('Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType')) {
            return;
        }
        require_once( PGPPW_PLUGIN_DIR . '/checkout-block/pgppw_phonepe-block.php' );
        add_action(
                'woocommerce_blocks_payment_method_type_registration',
                function (Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry) {
                    $payment_method_registry->register(new PGPPW_Block);
                }
        );
    } catch (Exception $ex) {
        
    }
});


add_action('admin_init', 'easy_pgppw_for_woocommerce_redirect_to_settings');

function easy_pgppw_for_woocommerce_redirect_to_settings() {
    // Check if the transient is set and user has access to the admin panel
    if (get_transient('easy_pgppw_for_woocommerce_redirect')) {
        // Remove the transient so it only redirects once
        delete_transient('easy_pgppw_for_woocommerce_redirect');

        // Make sure the redirect only happens for administrators
        if (is_admin() && current_user_can('manage_options')) {
            wp_redirect(admin_url('admin.php?page=wc-settings&tab=checkout&section=pgppw_phonepe'));
            exit;
        }
    }
}