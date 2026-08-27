<?php

class PGPPW {

    protected $loader;
    protected $plugin_name;
    protected $version;

    public function __construct() {
        if (defined('PGPPW_VERSION')) {
            $this->version = PGPPW_VERSION;
        } else {
            $this->version = '1.0.11';
        }
        $this->plugin_name = 'payment-gateway-for-phonepe-and-for-woocommerce';
        $this->load_dependencies();
        $this->define_admin_hooks();
        add_filter('woocommerce_payment_gateways', array($this, 'pgppw_phonepe_woocommerce_payment_gateways'), 10, 1);
        $prefix = is_network_admin() ? 'network_admin_' : '';
        add_filter("{$prefix}plugin_action_links_" . PGPPW_BASENAME, array($this, 'pgppw_phonepe_plugin_action_links'), 10, 4);
        add_filter('plugin_row_meta', array($this, 'add_pgppw_phonepe_plugin_meta_links'), 10, 2);
        add_filter('woocommerce_available_payment_gateways', array($this, 'conditional_payment_gateways'), 10, 1);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_phonepe_hide_place_order_script'));
    }

    public function enqueue_phonepe_hide_place_order_script() {
        if (is_checkout()) {
            wp_enqueue_script(
                    'pgppw-public-js',
                    PGPPW_ASSET_URL . 'public/js/pgppw.js',
                    array('jquery'),
                    $this->version,
                    true
            );
            wp_localize_script('pgppw-public-js', 'pgppw_js', array(
                'currency_code' => get_woocommerce_currency(),
            ));
        }
    }

    public function enqueue_styles() {
        wp_enqueue_style('pgppw-public-css', PGPPW_ASSET_URL . 'public/css/pgppw.css', array(), $this->version, 'all');
    }

    private function load_dependencies() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-pgppw-function.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-pgppw-loader.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-pgppw-admin.php';
        $this->loader = new PGPPW_Loader();
    }

    private function define_admin_hooks() {
        $plugin_admin = new PGPPW_Admin($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
    }

    public function run() {
        $this->loader->run();
    }

    public function get_plugin_name() {
        return $this->plugin_name;
    }

    public function get_loader() {
        return $this->loader;
    }

    public function get_version() {
        return $this->version;
    }

    public function pgppw_phonepe_woocommerce_payment_gateways($gateways) {
        include_once PGPPW_PLUGIN_DIR . '/includes/class-pgppw-gateway.php';
        $gateways[] = 'PGPPW_Gateway';
        return $gateways;
    }

    public function pgppw_phonepe_plugin_action_links($actions, $plugin_file, $plugin_data, $context) {
        $custom_actions = array(
            'configure' => sprintf('<a href="%s">%s</a>', admin_url('admin.php?page=wc-settings&tab=checkout&section=pgppw_phonepe'), __('Settings', 'payment-gateway-for-phonepe-and-for-woocommerce')),
        );
        return array_merge($custom_actions, $actions);
    }

    public function add_pgppw_phonepe_plugin_meta_links($meta, $file) {
        if (basename($file) === basename(PGPPW_BASENAME)) {
            $meta[] = '<a href="https://wordpress.org/support/plugin/payment-gateway-for-phonepe-and-for-woocommerce/">' . __('Community support', 'payment-gateway-for-phonepe-and-for-woocommerce') . '</a>';
            $meta[] = '<a href="https://wordpress.org/support/plugin/payment-gateway-for-phonepe-and-for-woocommerce/reviews/#new-post" target="_blank" rel="noopener noreferrer">' . __('Rate our plugin', 'payment-gateway-for-phonepe-and-for-woocommerce') . '</a>';
        }
        return $meta;
    }

    public function conditional_payment_gateways($available_gateways) {
        global $wp;
        if (is_checkout_pay_page() && isset($wp->query_vars['order-pay'])) {
            $order_id = absint($wp->query_vars['order-pay']);
            $order = wc_get_order($order_id);
            $selected_currency = $order ? $order->get_currency() : null;
        } else {
            $selected_currency = get_woocommerce_currency();
        }
        if ($selected_currency !== 'INR' && isset($available_gateways['pgppw_phonepe'])) {
            //unset($available_gateways['pgppw_phonepe']);
        }
        return $available_gateways;
    }
}
