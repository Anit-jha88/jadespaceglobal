<?php

if (!function_exists('pgppw_phonepe_is_local_server')) {

    function pgppw_phonepe_is_local_server() {
        if (!isset($_SERVER['HTTP_HOST']) || !isset($_SERVER['REMOTE_ADDR'])) {
            return false;
        }
        $http_host = sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST']));
        $remote_addr = sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR']));
        if ($http_host === 'localhost' || strpos($remote_addr, '10.') === 0 || strpos($remote_addr, '192.168') === 0) {
            return true;
        }
        $live_sites = [
            'HTTP_CLIENT_IP',
            'HTTP_X_REAL_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
        ];
        foreach ($live_sites as $ip) {
            if (!empty($_SERVER[$ip])) {
                return false;
            }
        }
        if (in_array($remote_addr, ['127.0.0.1', '::1'], true)) {
            return true;
        }
        $site_url = site_url();
        $fragments = explode('.', wp_parse_url($site_url, PHP_URL_HOST));
        if (in_array(end($fragments), ['dev', 'local', 'localhost', 'test'], true)) {
            return true;
        }
        return false;
    }
}