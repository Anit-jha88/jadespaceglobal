<?php
if (!defined('ABSPATH')) {
    exit;
}

class PGPPW_Gateway extends WC_Payment_Gateway {

    private $api;
    public $log_enabled;
    public $environment;
    public $client_id;
    public $client_secret;
    public $client_version;
    public $debug;
    public $webhook_handler;
    public $webhook;
    public $logger;
    public $redirect_icon;
    public $description;
    public $hide_redirect_icon;
    private static $pgppw_notice_rendered = false;

    public function __construct() {
        $this->supports = array(
            'products',
            'refunds'
        );
        $this->id = 'pgppw_phonepe';
        $this->method_title = 'PhonePe Gateway By Easy Payment';
        $this->method_description = __('Accept UPI, Card, and Net Banking payments in INR only.', 'payment-gateway-for-phonepe-and-for-woocommerce');
        $this->icon = PGPPW_ASSET_URL . '/public/images/phonepev2.svg';
        $this->redirect_icon = PGPPW_ASSET_URL . '/public/images/pgppw-popup.svg';
        $this->has_fields = false;
        $this->init_form_fields();
        $this->init_settings();
        $this->init_properties();
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
        add_action('woocommerce_api_' . strtolower(get_class($this)), array($this, 'handle_callback'));
        add_action('admin_notices', array($this, 'pgppw_leaverev'));
        add_action('wp_ajax_pgppw_handle_review_action', array($this, 'pgppw_handle_review_action'));
        add_action('admin_enqueue_scripts', array($this, 'pgppw_enqueue_scripts'));
        add_filter('safe_style_css', array($this, 'pgppw_allowed_css_properties'));
        if ($this->is_credentials_set()) {
            if (!class_exists('PGPPW_API')) {
                include_once PGPPW_PLUGIN_DIR . '/includes/class-pgppw-api.php';
            }
            $this->api = new PGPPW_API($this->client_id, $this->client_secret, $this->client_version, $this->environment, $this->log_enabled);
            if (!class_exists('PGPPW_Webhook')) {
                include_once PGPPW_PLUGIN_DIR . '/includes/class-pgppw-webhook.php';
            }
            //$this->enabled = 'yes';
            $this->webhook = new PGPPW_Webhook([
                'debug' => $this->log_enabled,
                'api' => $this->api,
            ]);
        }
    }

    public function init_properties() {
        $this->title = $this->get_option('title', 'PhonePe - UPI, Cards, and Net Banking');
        $this->description = $this->get_option('description', '');
        $this->enabled = $this->get_option('enabled', 'no');
        $this->environment = $this->get_option('environment', 'test');
        $this->client_id = $this->get_option(($this->environment === 'live' ? 'live' : 'sandbox') . '_client_id');
        $this->client_secret = $this->get_option(($this->environment === 'live' ? 'live' : 'sandbox') . '_client_secret');
        $this->client_version = $this->get_option(($this->environment === 'live' ? 'live' : 'sandbox') . '_client_version');
        $this->log_enabled = 'yes' === $this->get_option('debug', 'yes');
        $this->hide_redirect_icon = 'yes' === $this->get_option('hide_redirect_icon', 'no');
        $this->order_button_text = __('Pay with PhonePe', 'payment-gateway-for-phonepe-and-for-woocommerce');
    }

    public function admin_options() {
        $return_url = admin_url('admin.php?page=wc-settings&tab=checkout');
        if (function_exists('wc_back_header')) {
            wc_back_header($this->get_method_title(), __('Return to payments', 'payment-gateway-for-phonepe-and-for-woocommerce'), $return_url);
        } else {
            echo '<h2>' . esc_html($this->method_title) . '</h2>';
            wc_back_link(
                    __('Return to payments', 'payment-gateway-for-phonepe-and-for-woocommerce'),
                    admin_url('admin.php?page=wc-settings&tab=checkout')
            );
        }
        echo wp_kses_post(wpautop($this->get_method_description()));
        echo '<p>';
        echo wp_kses(
                __('Need help? Contact <a href="https://wordpress.org/support/plugin/payment-gateway-for-phonepe-and-for-woocommerce/" target="_blank">support</a>.', 'payment-gateway-for-phonepe-and-for-woocommerce'),
                array('a' => array('href' => array(), 'target' => array()))
        );
        echo '</p>';
        echo '<table class="form-table" style="display:none;">' . $this->generate_settings_html($this->get_form_fields(), false) . '</table>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is safe from WooCommerce Settings API.
    }

    public function process_admin_options() {
        parent::process_admin_options();
        delete_transient('pgppw_oauth_token_live');
        delete_transient('pgppw_oauth_token_sandbox');
        $this->init_properties();
        if ($this->is_credentials_set()) {
            if (!class_exists('PGPPW_API')) {
                include_once PGPPW_PLUGIN_DIR . '/includes/class-pgppw-api.php';
            }
            $this->api = new PGPPW_API(
                    $this->client_id,
                    $this->client_secret,
                    $this->client_version,
                    $this->environment,
                    $this->log_enabled
            );
        }
    }

    public function is_credentials_set() {
        if (!empty($this->client_id) && !empty($this->client_secret) && !empty($this->client_version)) {
            return true;
        } else {
            return false;
        }
    }

    public function is_available() {
        if ($this->is_credentials_set() && $this->enabled === 'yes') {
            return true;
        }
        return false;
    }

    public function init_form_fields() {
        $this->form_fields = [
            'section_one' => [
                'title' => __('Step 1: Registration (Skip this step if you already have a PhonePe business account)', 'payment-gateway-for-phonepe-and-for-woocommerce'),
                'type' => 'title',
                'class' => 'pgppw-phonepe-collapsible-section'
            ],
            'signup_account' => array(
                'type' => 'phonepe_signup_banner',
            ),
            'section_two' => [
                'title' => __('Step 2: PhonePe Gateway Settings', 'payment-gateway-for-phonepe-and-for-woocommerce'),
                'type' => 'title',
                'class' => 'pgppw-phonepe-collapsible-section'
            ],
            'enabled' => [
                'title' => __('Enable/Disable', 'payment-gateway-for-phonepe-and-for-woocommerce'),
                'label' => __('Enable PhonePe', 'payment-gateway-for-phonepe-and-for-woocommerce'),
                'type' => 'checkbox',
                'default' => 'no',
            ],
            'title' => [
                'title' => __('Title', 'payment-gateway-for-phonepe-and-for-woocommerce'),
                'type' => 'text',
                'default' => __('PhonePe - UPI, Cards, and Net Banking', 'payment-gateway-for-phonepe-and-for-woocommerce'),
                'desc_tip' => true,
            ],
            'description' => [
                'title' => __('Description', 'payment-gateway-for-phonepe-and-for-woocommerce'),
                'type' => 'textarea',
                'css' => 'width: 400px;',
                'default' => __('Click the "Pay With PhonePe" button below to process your order.', 'payment-gateway-for-phonepe-and-for-woocommerce'),
            ],
            'environment' => array(
                'title' => __('Environment', 'payment-gateway-for-phonepe-and-for-woocommerce'),
                'type' => 'select',
                'class' => 'wc-enhanced-select',
                'label' => __('Select PhonePe Environment', 'payment-gateway-for-phonepe-and-for-woocommerce'),
                'default' => 'yes',
                'description' => __('Choose the PhonePe environment. Select "Sandbox" for testing transactions (no real transactions will occur) or "Production" for live transactions.', 'payment-gateway-for-phonepe-and-for-woocommerce'),
                'desc_tip' => true,
                'options' => array(
                    'test' => __('Sandbox (Test Mode)', 'payment-gateway-for-phonepe-and-for-woocommerce'),
                    'live' => __('Production (Live)', 'payment-gateway-for-phonepe-and-for-woocommerce'),
                ),
            ),
            'live_client_id' => ['title' => __('Live Client ID', 'payment-gateway-for-phonepe-and-for-woocommerce'), 'type' => 'text'],
            'live_client_secret' => ['title' => __('Live Client secret', 'payment-gateway-for-phonepe-and-for-woocommerce'), 'type' => 'text'],
            'live_client_version' => ['title' => __('Live Client Version', 'payment-gateway-for-phonepe-and-for-woocommerce'), 'type' => 'text'],
            'sandbox_client_id' => ['title' => __('Sandbox Client ID', 'payment-gateway-for-phonepe-and-for-woocommerce'), 'type' => 'text'],
            'sandbox_client_secret' => ['title' => __('Sandbox Client Secret', 'payment-gateway-for-phonepe-and-for-woocommerce'), 'type' => 'text'],
            'sandbox_client_version' => ['title' => __('Sandbox Client Version', 'payment-gateway-for-phonepe-and-for-woocommerce'), 'type' => 'text'],
            'section_three' => [
                'title' => __('Step 3: Additional Settings', 'payment-gateway-for-phonepe-and-for-woocommerce'),
                'type' => 'title',
                'class' => 'pgppw-phonepe-collapsible-section'
            ],
            'hide_redirect_icon' => [
                'title' => __('Hide Redirect Icon', 'payment-gateway-for-phonepe-and-for-woocommerce'),
                'type' => 'checkbox',
                'label' => __('Do not show the redirect icon at checkout', 'payment-gateway-for-phonepe-and-for-woocommerce'),
                'default' => 'no',
                'desc_tip' => true,
                'description' => __('When enabled, the redirect icon will NOT be shown for PhonePe checkout.', 'payment-gateway-for-phonepe-and-for-woocommerce'),
            ],
            'debug' => array(
                'title' => __('Debug log', 'payment-gateway-for-phonepe-and-for-woocommerce'),
                'type' => 'checkbox',
                'label' => __('Enable logging', 'payment-gateway-for-phonepe-and-for-woocommerce'),
                'default' => 'yes',
                /* translators: %s is the file path where PhonePe logs are stored */
                'description' => sprintf(__('Log PhonePe Payment, Refund inside %s', 'payment-gateway-for-phonepe-and-for-woocommerce'),
                        '<code>' . WC_Log_Handler_File::get_log_file_path('pgppw_phonepe') . '</code>'
                ),
            ),
            'section_four' => [
                'title' => __('Helpful Tips', 'payment-gateway-for-phonepe-and-for-woocommerce'),
                'type' => 'title',
                'class' => 'pgppw-phonepe-collapsible-section'
            ],
            'our_razorpay_plugin' => [
                'title' => __('Hide Redirect Icon', 'payment-gateway-for-phonepe-and-for-woocommerce'),
                'type' => 'razorpay_promo',
            ],
        ];
        $slug = 'easy-payment-gateway-for-razorpay-and-for-woocommerce';
        $plugin_file = $slug . '/' . $slug . '.php';
        // Hide section if Razorpay plugin already installed or active.
        if (file_exists(WP_PLUGIN_DIR . '/' . $plugin_file) || is_plugin_active($plugin_file)) {
            unset($this->form_fields['section_four']);
            unset($this->form_fields['our_razorpay_plugin']);
        }
    }

    public function pgppw_phonepe_create_webhook() {
        try {
            $this->webhook->register_webhook();
        } catch (Exception $ex) {
            
        }
    }

    public function generate_razorpay_promo_html($field_key, $data) {
        if (isset($data['type']) && $data['type'] === 'razorpay_promo') {
            $slug = 'easy-payment-gateway-for-razorpay-and-for-woocommerce';
            $plugin_file = $slug . '/' . $slug . '.php';
            if (file_exists(WP_PLUGIN_DIR . '/' . $plugin_file) || is_plugin_active($plugin_file)) {
                return '';
            }
            $field_key = $this->get_field_key($field_key);
            $install_url = wp_nonce_url(
                    self_admin_url('update.php?action=install-plugin&plugin=' . $slug),
                    'install-plugin_' . $slug
            );
            ob_start();
            ?>
            <tr valign="top">
                <td class="forminp" id="<?php echo esc_attr($field_key); ?>">
                    <div class="pgppw-razorpay-promo" role="region" aria-labelledby="<?php echo esc_attr($field_key); ?>-heading"style="padding:0px 20px; max-width:820px;">
                        <h4 id="<?php echo esc_attr($field_key); ?>-heading" style="margin:0 0 10px; font-size:16px;">
                            💡 <?php esc_html_e('Why Multiple Payment Gateways Matter', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?>
                        </h4>
                        <p style="margin:0 0 14px; font-size:14px; line-height:1.6; color:#333;">
                            <?php esc_html_e('Payment failures or downtime can happen with any provider. Having multiple gateways ensures your customers can always complete their checkout — smoothly and without interruption.', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?>
                        </p>
                        <table role="presentation" style="margin:0 0 16px 6px; font-size:13.5px; line-height:1.6; color:#333;">
                            <tr>
                                <td style="padding:2px 8px 2px 0;">✅ <strong><?php esc_html_e('Higher success rate:', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?></strong></td>
                                <td style="padding:2px 0;"><?php esc_html_e('If one gateway fails, another can instantly take over.', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?></td>
                            </tr>
                            <tr>
                                <td style="padding:2px 8px 2px 0;">💳 <strong><?php esc_html_e('Customer choice:', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?></strong></td>
                                <td style="padding:2px 0;"><?php esc_html_e('Different shoppers trust different payment methods and gateways.', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?></td>
                            </tr>
                            <tr>
                                <td style="padding:2px 8px 2px 0;">🚀 <strong><?php esc_html_e('Better conversions:', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?></strong></td>
                                <td style="padding:2px 0;"><?php esc_html_e('Reduces abandoned carts caused by payment issues.', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?></td>
                            </tr>
                        </table>
                        <div style="display:flex; align-items:center; gap:14px; margin-top:10px;">
                            <img src="https://ps.w.org/easy-payment-gateway-for-razorpay-and-for-woocommerce/assets/icon-256x256.png" alt="<?php esc_attr_e('Razorpay plugin icon', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?>" width="64" height="64" style="border-radius:8px; flex-shrink:0;">
                            <div style="flex:1;">
                                <strong style="font-size:15px;">
                                    <?php esc_html_e('Try our Razorpay for WooCommerce Plugin', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?>
                                </strong>
                                <p style="margin:4px 0 0; font-size:13.5px; color:#555;">
                                    <?php esc_html_e('Simple, fast, and built for reliability alongside PhonePe.', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?>
                                </p>
                            </div>
                            <a href="<?php echo esc_url($install_url); ?>"
                               class="button button-primary"
                               style="font-weight:600; flex-shrink:0;">
                                <?php esc_html_e('Install Now', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?>
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
            <?php
            return ob_get_clean();
        }
    }

    public function generate_phonepe_signup_banner_html($field_key, $data) {
        if (isset($data['type']) && $data['type'] === 'phonepe_signup_banner') {
            $field_key = $this->get_field_key($field_key);
            ob_start();
            ?>
            <tr valign="top">

                <td class="forminp" id="<?php echo esc_attr($field_key); ?>">
                    <?php
                    // Links
                    $tracking_url = 'https://easypaymentplugins.com/phonepe-signup'; // Redirect with tracking
                    $direct_url = 'https://business.phonepe.com/register?referral-code=RF2502041417463473449057'; // Direct PhonePe
                    ?>

                    <div class="pgppw-phonepe-signup-box" role="region" aria-labelledby="<?php echo esc_attr($field_key); ?>-heading">
                        <!-- For Business Owners -->
                        <h4 id="<?php echo esc_attr($field_key); ?>-heading">
                            <span class="dashicons dashicons-businessperson" aria-hidden="true"></span>
                            <?php esc_html_e('For Business Owners', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?>
                        </h4>
                        <p class="description">
                            <?php esc_html_e('Sign up your business to start accepting payments with PhonePe.', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?>
                        </p>
                        <p>
                            <button type="button"
                                    class="button button-primary pgppw-register-btn"
                                    data-url="<?php echo esc_url($tracking_url); ?>"
                                    aria-label="<?php esc_attr_e('Sign up with PhonePe', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?>">
                                        <?php esc_html_e('Sign Up with PhonePe', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?>
                            </button>
                        </p>

                        <hr />

                        <!-- For Developers -->
                        <h4>
                            <span class="dashicons dashicons-admin-links" aria-hidden="true"></span>
                            <?php esc_html_e('For Developers', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?>
                        </h4>
                        <p class="description">
                            <?php esc_html_e('Share this referral link with your client for easy sign-up.', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?>
                        </p>

                        <div class="pgppw-copy-wrap">

                            <input type="text"
                                   id="<?php echo esc_attr($field_key); ?>-ref-link"
                                   class="pgppw-copy-input"
                                   value="<?php echo esc_attr($direct_url); ?>"
                                   readonly />
                            <button type="button"
                                    class="button pgppw-copy-link"
                                    data-link="<?php echo esc_attr($direct_url); ?>">
                                        <?php esc_html_e('Copy Link', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?>
                            </button>
                        </div>
                        <hr />
                        <p class="description">
                            <?php esc_html_e('After signing up, PhonePe will provide the API credentials required in the connection section below.'); ?><br/> 
                            <?php esc_html_e('If you already have PhonePe API credentials, proceed directly to Step 2.', 'payment-gateway-for-phonepe-and-for-woocommerce'); ?>
                        </p>
                    </div>
                </td>
            </tr>
            <?php
            return ob_get_clean();
        }
    }

    public function process_payment($order_id) {
        try {
            $order = wc_get_order($order_id);
            if (!$order instanceof WC_Order) {
                wc_add_notice(__('Invalid order.', 'payment-gateway-for-phonepe-and-for-woocommerce'), 'error');
                return array('result' => 'failure');
            }

            $amount = (float) $order->get_total();
            $merchant_user_id = (int) $order->get_user_id();
            $merchantTransactionId = 'MT' . time() . substr($order_id, -2) . wp_rand(100, 999);

            $callback_url = add_query_arg(
                    array(
                        'pgppw_phonepe_action' => 'payment_callback',
                        'order_id' => $order_id,
                        'transactionId' => $merchantTransactionId,
                    ),
                    WC()->api_request_url('PGPPW_Gateway')
            );

            $redirect_url = add_query_arg(
                    array(
                        'pgppw_phonepe_action' => 'process_payment',
                        'order_id' => $order_id,
                        'transactionId' => $merchantTransactionId,
                    ),
                    WC()->api_request_url('PGPPW_Gateway')
            );

            if (empty($this->api)) {
                wc_add_notice(__('Payment gateway error. Please try again later.', 'payment-gateway-for-phonepe-and-for-woocommerce'), 'error');
                $order->add_order_note('PGPPW: API client not initialized.');
                return array('result' => 'failure');
            }

            $response = $this->api->createPaymentRequest($amount, $order_id, $merchant_user_id, $redirect_url, $callback_url);

            // Handle transport/protocol errors first
            if (is_wp_error($response)) {
                $msg = $response->get_error_message();
                $code = $response->get_error_code();
                wc_add_notice(sprintf(__('Payment failed: %s', 'payment-gateway-for-phonepe-and-for-woocommerce'), esc_html($msg)), 'error');
                $order->update_status('failed', sprintf('PGPPW WP_Error (%s): %s', $code, $msg));
                return array('result' => 'failure');
            }

            // Normalize (array|object) and read redirect URL/message
            $redirect = null;
            $message = 'Unknown error';

            if (is_array($response)) {
                $redirect = $response['redirectUrl'] ?? ( $response['redirect'] ?? null );
                $message = $response['message'] ?? $message;
            } elseif (is_object($response)) {
                $redirect = $response->redirectUrl ?? ( $response->redirect ?? null );
                $message = $response->message ?? $message;
            }

            if (!empty($redirect)) {
                // Save txn id for callbacks/reconciliation
                $order->update_meta_data('_pgppw_txn_id', $merchantTransactionId);
                $order->save();

                return array(
                    'result' => 'success',
                    'redirect' => esc_url_raw($redirect),
                );
            }

            wc_add_notice(
                    sprintf(
                            __('Payment failed: %s', 'payment-gateway-for-phonepe-and-for-woocommerce'),
                            esc_html((string) $message)
                    ),
                    'error'
            );
            $order->update_status('failed', 'PGPPW createPaymentRequest returned no redirect URL. Message: ' . (string) $message);
            return array('result' => 'failure');
        } catch (Throwable $e) {
            if (isset($order) && $order instanceof WC_Order) {
                $order->update_status('failed', 'PGPPW exception: ' . $e->getMessage());
            }
            wc_add_notice(__('Unexpected error while processing payment. Please try again.', 'payment-gateway-for-phonepe-and-for-woocommerce'), 'error');
            return array('result' => 'failure');
        }
    }

    public function handle_callback() {
        /* phpcs:disable WordPress.Security.NonceVerification.Recommended */
        /* phpcs:disable WordPress.Security.NonceVerification.Missing */
        if (!isset($_GET['pgppw_phonepe_action']) || empty($_GET['pgppw_phonepe_action'])) {
            return;
        }
        $pgppw_phonepe_action = sanitize_text_field(wp_unslash($_GET['pgppw_phonepe_action']));
        switch ($pgppw_phonepe_action) {
            case "process_payment":
                $this->pgppw_phonepe_process_payment();
                break;
        }
    }

    public function pgppw_phonepe_process_payment() {
        if (!isset($_GET['transactionId']) || !isset($_GET['order_id'])) {
            return;
        }
        $order_id = sanitize_text_field(wp_unslash($_GET['order_id']));
        $transaction_id = sanitize_text_field(wp_unslash($_GET['transactionId']));
        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }
        $status_response = $this->api->checkOrderStatus($order_id);
        $this->pgppw_phonepe_log("PhonePe Transaction Status Response: " . json_encode($status_response));
        if ($status_response && isset($status_response['state']) && $status_response['state'] === 'COMPLETED') {
            if (!in_array($order->get_status(), ['processing', 'completed'], true)) {
                $transaction_id = sanitize_text_field($status_response['transactionId'] ?? $status_response['orderId'] ?? 'N/A');
                $order->payment_complete($transaction_id);
                $amount_raw = !empty($status_response['amount']) ? (float) $status_response['amount'] / 100 : 0;
                $amount_text = $amount_raw > 0 ? wc_trim_zeros(number_format($amount_raw, 2)) : 'N/A';
                $payment_mode_map = [
                    'CARD' => 'Credit/Debit Card',
                    'UPI' => 'UPI',
                    'NETBANKING' => 'Net Banking',
                ];
                $payment_code = $status_response['paymentDetails'][0]['paymentMode'] ?? 'N/A';
                $payment_method = $payment_mode_map[$payment_code] ?? $payment_code;
                $order->update_meta_data('_phonepe_payment_method', $payment_method);
                $order->update_meta_data('_phonepe_amount', $amount_raw);
                $order->set_payment_method_title('PhonePe');
                $order->save();
                $order_note = "✅ PhonePe Payment Successful\n\n";
                $order_note .= "Transaction ID: {$transaction_id}\n";
                $order_note .= "Amount: ₹{$amount_text}\n";
                $order_note .= "Payment Method: {$payment_method}";
                $order->add_order_note(esc_html($order_note));
            }
            wp_safe_redirect($this->get_return_url($order));
            exit;
        } elseif ($status_response && isset($status_response['state']) && $status_response['state'] === 'COMPLETED') {
            $transaction_id = $status_response['orderId'] ?? 'N/A';
            $order->set_transaction_id($transaction_id);
            $order->update_status('on-hold', 'Payment is Pending.');
            $order->set_payment_method_title('PhonePe');
            $order->save();
            $amount = !empty($status_response['amount']) ? ($status_response['amount'] / 100) : 'N/A'; // Convert to rupees
            $payment_type = $status_response['paymentDetails'][0]['paymentMode'] ?? 'N/A';
            $order_note = "✅ PhonePe Payment Successful\n\n"
                    . "Transaction ID: $transaction_id\n"
                    . "Amount: ₹$amount\n"
                    . "Payment Method: $payment_type \n";
            $order->add_order_note($order_note);
            wp_redirect($this->get_return_url($order));
            exit;
        } else {
            $error_message = $status_response['message'] ?? __('Payment failed, please try again.', 'payment-gateway-for-phonepe-and-for-woocommerce');
            $order->add_order_note("❌ PhonePe Payment Failed: $error_message");
            wc_add_notice(__('Payment failed, please try again.', 'payment-gateway-for-phonepe-and-for-woocommerce'), 'error');
            wp_redirect(wc_get_checkout_url());
            exit;
        }
    }

    public function process_refund($order_id, $amount = null, $reason = '') {
        $refund_id = 'RFND' . time();
        $order = wc_get_order($order_id);
        if (!$order) {
            wc_add_notice(__('Refund failed: Invalid Order ID.', 'payment-gateway-for-phonepe-and-for-woocommerce'), 'error');
            return false;
        }
        $this->pgppw_phonepe_log("Refund Request Initiated: Order ID: {$order_id}, Refund ID: {$refund_id}, Amount: ₹{$amount}");
        $order->add_order_note("🔄 PhonePe Refund Requested\nRefund ID: $refund_id\nAmount: ₹$amount\n\n📌 *The refund status will be updated automatically. No action is needed.*");
        $response = $this->api->initiateRefund($order_id, $refund_id, $amount);
        $this->pgppw_phonepe_log("PhonePe Refund API Response: " . json_encode($response));
        if (!empty($response['refundId']) && !empty($response['state'])) {
            $refund_transaction_id = $response['refundId'];
            $refund_state = strtoupper($response['state']);
            update_post_meta($order_id, '_phonepe_refund_txn_id', $refund_transaction_id);
            update_post_meta($order_id, '_phonepe_merchant_refund_txn_id', $refund_id);
            switch ($refund_state) {
                case 'COMPLETED':
                case 'CONFIRMED':
                    $order->add_order_note("✅ PhonePe Refund Successful\nRefund ID: $refund_id\nAmount: ₹$amount\nTransaction ID: $refund_transaction_id\n\n📌 *Refund is complete. No further action is needed.*");
                    return true;

                case 'PENDING':
                    $order->add_order_note("⏳ PhonePe Refund Pending\nRefund ID: $refund_id\nAmount: ₹$amount\nTransaction ID: $refund_transaction_id\n\n📌 *PhonePe is processing the refund. The status will update automatically.*");
                    return true;

                case 'FAILED':
                default:
                    $order->add_order_note("❌ PhonePe Refund Failed\nRefund ID: $refund_id\nAmount: ₹$amount\nTransaction ID: $refund_transaction_id\n\n📌 *Please try again or check with PhonePe support.*");
                    return false;
            }
        } else {
            $order->add_order_note("⚠️ PhonePe Refund API Error: Missing or invalid refund response.");
            return false;
        }
    }

    public function payment_fields() {
        if (get_woocommerce_currency() !== 'INR') {
            echo '<div class="phonepe_checkout_notice">';
            echo '<p class="phonepe_notice_message">';
            echo esc_html__('PhonePe is only supported when currency is INR.', 'payment-gateway-for-phonepe-and-for-woocommerce');
            echo '</p>';
            if (current_user_can('manage_woocommerce')) {
                echo '<small class="phonepe_notice_admin_guide">';
                echo esc_html__('To enable PhonePe payments, change your store currency to INR or use a multi-currency plugin.', 'payment-gateway-for-phonepe-and-for-woocommerce');
                echo '</small>';
            }
            echo '</div>';
            return;
        }
        ?>
        <div class="wc_pgppw_container">
            <?php if ($this->hide_redirect_icon === false) { ?>
                <img src="<?php echo esc_url($this->redirect_icon); ?>" alt="Payment Icon" />
            <?php } ?>
            <p><?php echo $this->description; ?></p>
        </div>
        <?php
    }

    public function pgppw_phonepe_log($message, $level = 'info') {
        if ($this->log_enabled) {
            if (empty($this->logger)) {
                $this->logger = wc_get_logger();
            }
            $this->logger->log($level, $message, array('source' => 'pgppw_phonepe'));
        }
    }

    public function pgppw_leaverev() {
        if (self::$pgppw_notice_rendered) {
            return;
        }
        self::$pgppw_notice_rendered = true;

        if (
                !isset($_GET['page'], $_GET['tab'], $_GET['section']) ||
                $_GET['page'] !== 'wc-settings' ||
                $_GET['tab'] !== 'checkout' ||
                $_GET['section'] !== 'pgppw_phonepe'
        ) {
            return;
        }

        if (!current_user_can('manage_woocommerce')) {
            return;
        }

        $plugin_name = 'PhonePe Gateway by Easy Payment';
        $review_url = 'https://wordpress.org/support/plugin/payment-gateway-for-phonepe-and-for-woocommerce/reviews/#new-post';
        $text_domain = 'payment-gateway-for-phonepe-and-for-woocommerce';

        // Ensure activation time exists
        $activation_time = (int) get_option('pgppw_activation_time');
        if (empty($activation_time)) {
            $activation_time = time();
            update_option('pgppw_activation_time', $activation_time);
        }

        $hide_state = get_option('pgppw_review_notice_hide_v1', ''); // '', 'later', 'never'
        $next_show_time = (int) get_option('pgppw_next_show_time', time());
        $since_activation = time() - $activation_time;

        // ⏱ Rules: show only after 1 day from activation, respect snooze, stop if 'never'
        $required_activation_delay = 1 * DAY_IN_SECONDS; // 1 day
        if ('never' === $hide_state || $since_activation < $required_activation_delay || time() < $next_show_time) {
            return;
        }

        // Notice HTML
        $html = '<div class="notice notice-success pgppw-review-notice">';
        $html .= '<p style="font-size:100%"></p>';
        $html .= '<h2 style="margin:0" class="title">' .
                sprintf(
                        esc_html__('Thank you for using %s 💕', $text_domain),
                        '<b>' . esc_html($plugin_name) . '</b>'
                ) .
                '</h2>';

        $html .= '<p>' .
                sprintf(
                        wp_kses(
                                __(
                                        'If you have a moment, we\'d love it if you could leave us a <b><a target="_blank" href="%1$s">quick review</a>.</b> It motivates us and helps us keep improving. 💫 <br>Have feature ideas? Include them in your review — your feedback shapes our roadmap, and we love turning your ideas into reality.',
                                        $text_domain
                                ),
                                ['b' => [], 'a' => ['href' => [], 'target' => []], 'br' => []]
                        ),
                        esc_url($review_url)
                ) .
                '</p>';

        $html .= '<div style="padding:5px 0 12px 0;display:flex;align-items:center;">';
        $html .= '<a target="_blank" class="button button-primary pgppw-action-button" data-action="reviewed" style="margin-right:10px;" href="' . esc_url($review_url) . '">✏️ ' .
                esc_html__('Write Review', $text_domain) .
                '</a>';
        $html .= '<button type="button" class="button button-secondary pgppw-action-button" data-action="never" style="margin-right:10px;">✌️ ' .
                esc_html__('Done!', $text_domain) .
                '</button>';
        $html .= '<div style="flex:auto;"></div>';
        $html .= '<button type="button" class="button button-secondary pgppw-action-button" data-action="later" style="margin-right:10px;">⏰ ' .
                esc_html__('Remind me later', $text_domain) .
                '</button>';
        $html .= '<a href="#" class="button-link pgppw-action-button" data-action="never" style="font-size:small;">' .
                esc_html__('Hide', $text_domain) .
                '</a>';
        $html .= '</div>';
        $html .= '</div>';

        echo wp_kses($html, [
            'div' => ['class' => [], 'style' => []],
            'p' => ['style' => []],
            'h2' => ['class' => [], 'style' => []],
            'b' => [],
            'br' => [],
            'a' => ['href' => [], 'target' => [], 'class' => [], 'style' => [], 'data-action' => []],
            'button' => ['type' => [], 'class' => [], 'style' => [], 'data-action' => []],
            'span' => ['style' => [], 'class' => []],
        ]);
    }

    public function pgppw_handle_review_action() {
        check_ajax_referer('pgppw_review_nonce', 'nonce');
        $action = isset($_POST['review_action']) ? sanitize_text_field($_POST['review_action']) : '';

        if ($action === 'later') {
            update_option('pgppw_next_show_time', time() + (7 * DAY_IN_SECONDS)); // 7 days
            update_option('pgppw_review_notice_hide_v1', 'later');
        } elseif ($action === 'never' || $action === 'reviewed') {
            update_option('pgppw_review_notice_hide_v1', 'never');
        } else {
            wp_send_json_error('Invalid action');
        }

        wp_send_json_success();
    }

    public function pgppw_enqueue_scripts($hook) {
        if (
                !isset($_GET['page'], $_GET['tab'], $_GET['section']) ||
                $_GET['page'] !== 'wc-settings' ||
                $_GET['tab'] !== 'checkout' ||
                $_GET['section'] !== 'pgppw_phonepe'
        ) {
            return;
        }



        wp_enqueue_script(
                'pgppw-review-ajax',
                PGPPW_ASSET_URL . '/admin/js/pgppw-review-ajax.js',
                array('jquery'),
                '1.0',
                true
        );

        wp_localize_script('pgppw-review-ajax', 'pgppwAjax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('pgppw_review_nonce'),
            'review_url' => 'https://wordpress.org/support/plugin/payment-gateway-for-phonepe-and-for-woocommerce/reviews/#new-post'
        ));
    }

    public function pgppw_allowed_css_properties($styles) {
        $styles[] = 'display';
        $styles[] = 'align-items';
        $styles[] = 'flex';
        $styles[] = 'auto';
        $styles[] = 'margin';
        $styles[] = 'padding';
        return $styles;
    }
}
