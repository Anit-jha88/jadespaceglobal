<?php

if (!defined('ABSPATH'))
    exit;

class PGPPW_Webhook {

    private $debug;
    private $api;

    public function __construct($args) {
        $this->debug = $args['debug'] ?? false;
        $this->api = $args['api'] ?? null;
        add_action('woocommerce_api_pgppw_webhook', [$this, 'handle_webhook']);
    }

    public function register_webhook() {
        if (!$this->api || !method_exists($this->api, 'sendRequest')) {
            $this->log("API class not initialized correctly.");
            return false;
        }
        $env = ($this->api->environment === 'live') ? 'live' : 'sandbox';
        $id_option = "pgppw_phonepe_webhook_id_$env";
        $user_option = "pgppw_phonepe_webhook_username_$env";
        $pass_option = "pgppw_phonepe_webhook_password_$env";
        $webhook_id = get_option($id_option);
        if ($webhook_id) {
            return true;
        }
        $webhook_url = WC()->api_request_url('PGPPW_Webhook');
        $username = $this->generate_username(12);
        $password = $this->generate_password(12);
        $payload = [
            'url' => $webhook_url,
            'description' => 'WooCommerce Webhook for PhonePe',
            'username' => $username,
            'password' => $password,
            'events' => [
                'checkout.order.completed',
                'checkout.order.failed',
                'pg.refund.accepted',
                'pg.refund.completed',
                'pg.refund.failed',
            ],
        ];

        $response = $this->api->sendRequest('/configs/v1/webhooks', $payload, 'POST');
        //$response = $this->api->sendRequest('/v1/webhooks/configure', $payload, 'POST');

        if (!is_array($response) || isset($response['error'])) {
            $this->log("Webhook registration failed ($env): " . wc_print_r($response, true));
            return false;
        }
        $webhook_id = $response['id'] ?? '';
        if ($webhook_id) {
            update_option($id_option, $webhook_id);
            update_option($user_option, $username);
            update_option($pass_option, $password);
            $this->log("Webhook registered successfully ($env) with ID: $webhook_id");
            return true;
        }
        $this->log("Webhook registered ($env), but no ID returned.");
        return false;
    }

    public function handle_webhook() {
        $request_method = isset($_SERVER['REQUEST_METHOD']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_METHOD'])) : '';
        if ($request_method !== 'POST') {
            status_header(405);
            exit;
        }

        $raw_body = file_get_contents('php://input');
        $data = json_decode($raw_body, true);

        if (!is_array($data)) {
            $this->log('Invalid JSON in webhook body.');
            status_header(400);
            echo esc_html__('Invalid JSON', 'payment-gateway-for-phonepe-and-for-woocommerce');
            exit;
        }

        // Sanitize headers
        $headers = function_exists('getallheaders') ? getallheaders() : [];
        $auth_header = sanitize_text_field($headers['Authorization'] ?? $headers['authorization'] ?? '');

        $env = ($this->api->environment === 'live') ? 'live' : 'sandbox';
        $username = sanitize_text_field(get_option("pgppw_phonepe_webhook_username_$env"));
        $password = sanitize_text_field(get_option("pgppw_phonepe_webhook_password_$env"));
        $expected_auth = hash('sha256', "$username:$password");

        if ($auth_header !== $expected_auth) {
            $this->log('Invalid webhook authorization');
            status_header(401);
            echo esc_html__('Unauthorized', 'payment-gateway-for-phonepe-and-for-woocommerce');
            exit;
        }

        $event = sanitize_text_field($data['event'] ?? '');
        $payload = is_array($data['payload'] ?? null) ? $data['payload'] : [];
        $state = sanitize_text_field($payload['state'] ?? '');
        $order_id = sanitize_text_field($payload['merchantOrderId'] ?? $payload['originalMerchantOrderId'] ?? '');

        if (empty($order_id) || !($order = wc_get_order($order_id))) {
            $this->log('Order not found: ' . esc_html($order_id));
            status_header(404);
            echo esc_html__('Order not found', 'payment-gateway-for-phonepe-and-for-woocommerce');
            exit;
        }

        switch ($event) {
            case 'checkout.order.completed':
                if ($state === 'COMPLETED') {
                    $transaction_id = sanitize_text_field($payload['transactionId'] ?? $payload['orderId'] ?? 'N/A');
                    $amount_raw = isset($payload['amount']) ? floatval($payload['amount']) / 100 : 0;
                    $amount_text = $amount_raw > 0 ? wc_trim_zeros(number_format($amount_raw, 2)) : 'N/A';
                    $payment_mode_map = [
                        'CARD' => 'Credit/Debit Card',
                        'UPI' => 'UPI',
                        'NETBANKING' => 'Net Banking',
                    ];
                    $payment_method_code = $payload['paymentDetails'][0]['paymentMode'] ?? 'N/A';
                    $payment_method = $payment_mode_map[$payment_method_code] ?? $payment_method_code;
                    if (!in_array($order->get_status(), ['processing', 'completed'], true)) {
                        $order->set_transaction_id($transaction_id);
                        $order->payment_complete($transaction_id);
                        $note = "✅ PhonePe Payment Successful\n\n";
                        $note .= "Transaction ID: {$transaction_id}\n";
                        $note .= "Amount: ₹{$amount_text}\n";
                        $note .= "Payment Method: {$payment_method}";
                        $order->add_order_note(esc_html($note));
                        $order->update_meta_data('_phonepe_payment_method', $payment_method);
                        $order->update_meta_data('_phonepe_amount', $amount_raw);
                        $order->set_payment_method_title('PhonePe');
                        $order->save();
                    }
                    $this->log("Order {$order_id} marked as completed via webhook. Txn ID: {$transaction_id}");
                }
                break;
            case 'checkout.order.failed':
                if ($state === 'FAILED') {
                    $order->update_status('failed', esc_html__('❌ Payment failed.', 'payment-gateway-for-phonepe-and-for-woocommerce'));
                    $order->add_order_note(esc_html__("❌ Payment failed.\nReason: Transaction state marked as FAILED.", 'payment-gateway-for-phonepe-and-for-woocommerce'));
                    $order->set_payment_method_title('PhonePe');
                    $order->save();
                    $this->log("Order {$order_id} marked as failed.");
                }
                break;
            case 'pg.refund.accepted':
            case 'pg.refund.completed':
            case 'pg.refund.failed':
                $this->handle_refund_event($event, $payload, $order);
                break;
            default:
                $this->log('Unhandled webhook event: ' . esc_html($event));
        }

        status_header(200);
        echo esc_html__('OK', 'payment-gateway-for-phonepe-and-for-woocommerce');
        exit;
    }

    private function handle_refund_event($event, $payload, $order) {
        $refund_id = $payload['merchantRefundId'] ?? $payload['refundId'] ?? '';
        $state = $payload['state'] ?? '';
        $note = '';
        $should_mark_refunded = false;
        switch ($state) {
            case 'COMPLETED':
                $note = "💸 Refund successfully completed.\nRefund ID: {$refund_id}";
                $should_mark_refunded = true;
                break;
            case 'FAILED':
                $note = "⚠️ Refund failed.\nRefund ID: {$refund_id}\nPlease check PhonePe dashboard for further details.";
                break;
            default:
                break;
        }
        if (!empty($note)) {
            $order->add_order_note($note);
        }
        if ($should_mark_refunded && $order->get_status() !== 'refunded') {
            $order->set_payment_method_title('PhonePe');
            $order->update_status('refunded', 'Order marked as refunded.');
        }
    }

    public function generate_username($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        if ($max < 1) {
            throw new Exception('$keyspace must be at least two characters long');
        }
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }

    public function generate_password($length = 20) {
        $length = min(max($length, 8), 20);
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $password = $letters[random_int(0, strlen($letters) - 1)] . $numbers[random_int(0, strlen($numbers) - 1)];
        $allChars = $letters . $numbers;
        for ($i = 2; $i < $length; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }
        $password = str_shuffle($password);
        return $password;
    }

    private function log($message) {
        if ($this->debug) {
            wc_get_logger()->info('[PhonePe Webhook] ' . $message, ['source' => 'pgppw_phonepe']);
        }
    }
}
