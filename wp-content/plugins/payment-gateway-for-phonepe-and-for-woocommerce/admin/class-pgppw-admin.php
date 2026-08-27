<?php

class PGPPW_Admin {

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_action('admin_footer', array($this, 'easy_phonepe_add_deactivation_feedback_form'));
        add_action('admin_enqueue_scripts', array($this, 'easy_phonepe_add_deactivation_feedback_form_scripts'));
        add_action('wp_ajax_easy_phonepe_send_deactivation', array($this, 'easy_phonepe_handle_plugin_deactivation_request'));
    }

    public function enqueue_scripts() {
        if (isset($_GET['section']) && 'pgppw_phonepe' === $_GET['section']) {
            wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/pgppw-admin.css', [], $this->version);
            wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/pgppw-admin.js', array('jquery'), $this->version, false);
        }
    }
    
    public function easy_phonepe_add_deactivation_feedback_form() {
        global $pagenow;
        if ('plugins.php' != $pagenow) {
            return;
        }
        include_once(PGPPW_PLUGIN_DIR . '/admin/feedback/deactivation-feedback-form.php');
    }

    public function easy_phonepe_add_deactivation_feedback_form_scripts() {
        global $pagenow;
        if ('plugins.php' != $pagenow) {
            return;
        }
        wp_enqueue_script('jquery-blockui');
        wp_enqueue_style('deactivation-feedback-modal-phonepe', PGPPW_ASSET_URL . 'admin/feedback/css/deactivation-feedback-modal.css', null, PGPPW_PLUGIN_VERSION);
        wp_enqueue_script('deactivation-feedback-modal-phonepe', PGPPW_ASSET_URL . 'admin/feedback/js/deactivation-feedback-modal.js', null, PGPPW_PLUGIN_VERSION, true);
        wp_localize_script('deactivation-feedback-modal-phonepe', 'phonepe_feedback_form_ajax_data', array('nonce' => wp_create_nonce('easy_phonepe-ajax')));
    }

    public function easy_phonepe_handle_plugin_deactivation_request() {
        $reason = isset($_POST['reason']) ? sanitize_text_field($_POST['reason']) : '';
        $reason_details = isset($_POST['reason_details']) ? sanitize_text_field($_POST['reason_details']) : '';
        $url = 'https://api.airtable.com/v0/appxxiU87VQWG6rOO/Sheet1';
        $api_key = 'patgeqj8DJfPjqZbS.9223810d432db4efccf27354c08513a7725e4a08d11a85fba75de07a539c8aeb';
        $data = array(
            'reason' => $reason . ' : ' . $reason_details,
            'plugin' => 'phonepe',
            'php_version' => phpversion(),
            'wp_version' => get_bloginfo('version'),
            'wc_version' => (!defined('WC_VERSION') ) ? '' : WC_VERSION,
            'locale' => get_locale(),
            'theme' => wp_get_theme()->get('Name'),
            'theme_version' => wp_get_theme()->get('Version'),
            'multisite' => is_multisite() ? 'Yes' : 'No',
            'plugin_version' => PGPPW_PLUGIN_VERSION
        );
        $args = array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode(array(
                'records' => array(
                    array(
                        'fields' => array(
                            'reason' => json_encode($data),
                            'date' => date('M d, Y h:i:s A')
                        ),
                    ),
                ),
            )),
            'method' => 'POST'
        );
        $response = wp_remote_post($url, $args);
        if (is_wp_error($response)) {
            wp_send_json_error(array(
                'message' => 'Error communicating with Airtable',
                'error' => $response->get_error_message()
            ));
        } else {
            wp_send_json_success(array(
                'message' => 'Deactivation feedback submitted successfully',
                'response' => json_decode(wp_remote_retrieve_body($response), true)
            ));
        }
    }
}
