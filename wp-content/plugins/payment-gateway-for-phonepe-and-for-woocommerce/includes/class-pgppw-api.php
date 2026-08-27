<?php

class PGPPW_API {

    public $client_id;
    public $client_secret;
    public $client_version;
    public $authUrl;
    public $logger;
    public $debug;
    public $environment;
    public $merchantId;
    public $baseUrl;
    public $webhookUrl;

    public function __construct($client_id, $client_secret, $client_version, $environment, $debug, $merchantId = '') {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->client_version = $client_version;
        $this->environment = $environment;
        $this->authUrl = ($this->environment === 'live') ? 'https://api.phonepe.com/apis/identity-manager' : 'https://api-preprod.phonepe.com/apis/pg-sandbox';
        $this->baseUrl = ($this->environment === 'live') ? 'https://api.phonepe.com/apis/pg' : 'https://api-preprod.phonepe.com/apis/pg-sandbox';
        $this->webhookUrl = ($this->environment === 'live') ? 'https://api.phonepe.com/apis/omx-service' : 'https://api-preprod.phonepe.com/apis/pg-sandbox';
        $this->debug = $debug;
        $this->logger = wc_get_logger();
        $this->merchantId = $merchantId;
    }

    public function log($message, $context = []) {
        if ($this->debug) {
            $this->logger->debug($message, array_merge(['source' => 'pgppw_phonepe'], $context));
        }
    }

    public function get_pgppw_oauth_token() {
        $env = ($this->environment === 'live') ? 'live' : 'sandbox';
        $transient_key = 'pgppw_oauth_token_' . $env;

        // 1) Try from cache
        $cached_token = get_transient($transient_key);
        if ($cached_token && is_array($cached_token) && !empty($cached_token['access_token'])) {
            return $cached_token;
        }

        // 2) Request a new token
        $url = trailingslashit($this->authUrl) . 'v1/oauth/token';
        $body = array(
            'client_id' => $this->client_id,
            'client_version' => $this->client_version,
            'client_secret' => $this->client_secret,
            'grant_type' => 'client_credentials',
        );

        $response = wp_remote_post($url, array(
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded',
            ),
            'body' => http_build_query($body, '', '&'),
            'timeout' => 15,
        ));

        if (is_wp_error($response)) {
            return new WP_Error('pgppw_api_error', 'Failed to connect to PhonePe', array(
                'error' => $response->get_error_message(),
            ));
        }

        $code = wp_remote_retrieve_response_code($response);
        $body_raw = wp_remote_retrieve_body($response);
        $decoded = json_decode($body_raw, true);

        // 3) Non-2xx HTTP response: surface provider details if JSON, else raw body
        if ($code < 200 || $code >= 300) {
            $provider_code = (is_array($decoded) && isset($decoded['code'])) ? $decoded['code'] : null;
            $provider_err = (is_array($decoded) && isset($decoded['errorCode'])) ? $decoded['errorCode'] : null;
            $provider_msg = (is_array($decoded) && isset($decoded['message'])) ? $decoded['message'] : 'Invalid response from PhonePe';

            return new WP_Error(
                    'pgppw_api_error',
                    $provider_msg,
                    array(
                'response_code' => $code,
                'provider_code' => $provider_code,
                'error_code' => $provider_err,
                'response_body' => $body_raw,
                    )
            );
        }

        // 4) Must be valid JSON object/array at this point
        if (!is_array($decoded)) {
            return new WP_Error('pgppw_api_error', 'Invalid or malformed JSON from PhonePe', array(
                'response_code' => $code,
                'response_body' => $body_raw,
                'json_error' => function_exists('json_last_error_msg') ? json_last_error_msg() : 'JSON decode error',
            ));
        }

        // 5) Validate required fields
        if (!isset($decoded['access_token']) || !isset($decoded['expires_in'])) {
            return new WP_Error('pgppw_api_error', 'Invalid token response from PhonePe', array(
                'response_code' => $code,
                'response_body' => $body_raw,
            ));
        }

        // 6) Cache with buffered expiry (min 300s)
        $expires_in = (int) $decoded['expires_in'];
        $ttl = max($expires_in - 600, 300); // buffer 10 min

        set_transient($transient_key, $decoded, $ttl);

        if (!empty($this->debug)) {
            wc_get_logger()->info(
                    '[PhonePe Token] New token generated and cached for ' . $ttl . ' seconds.',
                    array('source' => 'pgppw_phonepe')
            );
        }

        return $decoded;
    }

    public function sendRequest($endpoint, $payload = [], $method = 'POST') {
        $url = ( stripos($endpoint, 'webhooks') !== false ) ? rtrim($this->webhookUrl, '/') . $endpoint : rtrim($this->baseUrl, '/') . $endpoint;

        // --- OAuth token ---
        $token_data = $this->get_pgppw_oauth_token();
        if (is_wp_error($token_data)) {
            $this->log('OAuth Token Error', ['code' => $token_data->get_error_code(), 'msg' => $token_data->get_error_message()]);
            return $token_data; // WP_Error
        }
        if (!is_array($token_data) || empty($token_data['access_token'])) {
            $this->log('OAuth Token Missing', ['raw' => $token_data]);
            return new WP_Error('pgppw_token_missing', 'OAuth token missing or malformed.');
        }

        $headers = [
            'Content-Type' => 'application/json',
            // If your provider uses standard Bearer, change to 'Bearer '.
            'Authorization' => 'O-Bearer ' . $token_data['access_token'],
        ];

        $args = [
            'method' => strtoupper($method),
            'headers' => $headers,
            'timeout' => 30,
        ];

        if ('POST' === $args['method']) {
            $json = wp_json_encode($payload);
            if (false === $json) {
                $this->log('JSON Encode Failed', ['payload' => $payload]);
                return new WP_Error('pgppw_json_encode_failed', 'Failed to encode request payload to JSON.');
            }
            $args['body'] = $json;
        }

        $this->log('API Request Sent', [
            'url' => $url,
            'method' => $args['method'],
            'headers' => ['Content-Type' => $headers['Content-Type'], 'Authorization' => '***redacted***'],
            'payload' => ( 'POST' === $args['method'] ) ? $payload : 'N/A',
        ]);

        $response = ( 'POST' === $args['method'] ) ? wp_remote_post($url, $args) : wp_remote_get($url, $args);

        if (is_wp_error($response)) {
            $this->log('API Request Failed', ['error' => $response->get_error_message()]);
            return $response; // WP_Error
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);

        $decoded = json_decode($body, true);
        $this->log('API Response Received', [
            'status_code' => $code,
            'parsed_json' => $decoded ?? 'Non-JSON response',
        ]);

        if (null === $decoded && JSON_ERROR_NONE !== json_last_error()) {
            if (!empty($this->debug)) {
                $this->log('API Raw Body', ['raw_body' => $body]);
            }
            return new WP_Error('pgppw_bad_json', 'Invalid JSON in gateway response.', ['body' => $body, 'status_code' => $code]);
        }

        // Treat non-2xx as errors; include provider's message if present
        if ($code < 200 || $code >= 300) {
            $msg = isset($decoded['message']) ? $decoded['message'] : 'HTTP error from gateway.';
            return new WP_Error('pgppw_http_' . $code, (string) $msg, ['status_code' => $code, 'response' => $decoded]);
        }

        return $decoded; // success
    }

    public function createPaymentRequest($amount, $orderId, $merchantUserId, $redirectUrl, $callbackUrl) {
        $this->log('Creating Payment Request', ['order_id' => $orderId]);

        // Convert to minor units safely
        $amount_minor = (int) round((float) wc_format_decimal($amount, 2) * 100);

        $payload = [
            'merchantOrderId' => (string) $orderId,
            'merchantUserId' => (string) $merchantUserId,
            'amount' => $amount_minor,
            'paymentFlow' => [
                'type' => 'PG_CHECKOUT',
                'message' => 'Proceed to complete the payment',
                'merchantUrls' => [
                    'redirectUrl' => $redirectUrl,
                    // Include callback if your provider expects it here; rename key if needed (e.g., notifyUrl)
                    'callbackUrl' => $callbackUrl,
                ],
            ],
        ];

        // Bubble up WP_Error directly; success returns decoded array/object
        return $this->sendRequest('/checkout/v2/pay', $payload, 'POST');
    }

    public function checkOrderStatus($merchantOrderId) {
        $endpoint = "/checkout/v2/order/{$merchantOrderId}/status";
        return $this->sendRequest($endpoint, [], 'GET');
    }

    public function initiateRefund($originalMerchantOrderId, $merchantRefundId, $amount) {
        $payload = [
            'merchantRefundId' => $merchantRefundId,
            'originalMerchantOrderId' => $originalMerchantOrderId,
            'amount' => intval($amount * 100)
        ];
        return $this->sendRequest('/checkout/v2/refund', $payload, 'POST');
    }

    public function checkRefundStatus($merchantRefundId) {
        $endpoint = "/payments/v2/refund/{$merchantRefundId}/status";
        return $this->sendRequest($endpoint, [], 'GET');
    }
}
