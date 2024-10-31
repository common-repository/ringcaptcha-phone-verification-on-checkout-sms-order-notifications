<?php

if (!defined('ABSPATH')) exit;

class RingCaptcha_SMS_Notifications {
    private $options;

    public function __construct() {
        $this->options = get_option('rc2c_settings');
        
        if (isset($this->options['enable_text_notification']) && $this->options['enable_text_notification']) {
            add_action('woocommerce_order_status_pending', [$this, 'send_sms_pending']);
            add_action('woocommerce_order_status_failed', [$this, 'send_sms_failed']);
            add_action('woocommerce_order_status_on-hold', [$this, 'send_sms_on_hold']);
            add_action('woocommerce_order_status_processing', [$this, 'send_sms_processing']);
            add_action('woocommerce_order_status_completed', [$this, 'send_sms_completed']);
            add_action('woocommerce_order_status_refunded', [$this, 'send_sms_refunded']);
            add_action('woocommerce_order_status_cancelled', [$this, 'send_sms_cancelled']);
        }    

        // For admin notifications
        if (isset($this->options['enable_admin_message']) && $this->options['enable_admin_message']) {
            add_action('woocommerce_thankyou', [$this, 'send_admin_notification']);
        }
    }

    private function send_sms($order_id, $message_key) {
        $order = wc_get_order($order_id);
        if (!$order) {
            error_log("Order not found for ID: $order_id"); // Log if the order is not found
            return;
        }

        // Define replacements here
        $replacements = [
            '{name}'         => ucfirst( sanitize_text_field( $order->get_billing_first_name() ) ),
            '{shop_name}'    => get_bloginfo('name'),
            '{order_id}'     => $order_id,
            '{order_amount}' => $order->get_total(),
            // Add any other replacements you need
        ];

        include_once(plugin_dir_path(__FILE__) . '../lib/rc2c_message_variable.php');
        
        $data = [
            'phone' => sanitize_text_field($order->get_billing_phone()), // Use the getter method
            'secret_key' => sanitize_text_field($this->options['secret_key']),
            'message' => sanitize_text_field(str_replace(array_keys($replacements), $replacements, $this->options[$message_key])),
        ];

        include(plugin_dir_path(__FILE__) . '../lib/rc2c_send_sms.php');
    }

       // SMS sending functions for each status
       public function send_sms_pending($order_id) {
        $this->send_sms($order_id, 'pending_message');
    }

    public function send_sms_failed($order_id) {
        $this->send_sms($order_id, 'failed_message');
    }

    public function send_sms_on_hold($order_id) {
        $this->send_sms($order_id, 'on_hold_message');
    }

    public function send_sms_processing($order_id) {
        $this->send_sms($order_id, 'processing_message');
    }

    public function send_sms_completed($order_id) {
        $this->send_sms($order_id, 'completed_message');
    }

    public function send_sms_refunded($order_id) {
        $this->send_sms($order_id, 'refunded_message');
    }

    public function send_sms_cancelled($order_id) {
        $this->send_sms($order_id, 'cancelled_message');
    }

    private function send_sms_request($data) {
        $args = array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'body' => $data,
            'cookies' => array()
        );

        $url = 'https://api.ringcaptcha.com/' . $this->options['app_key'] . '/sms';
        $response = wp_remote_post($url, $args);
    }

    public function send_admin_notification($order_id) {
        $order = wc_get_order($order_id);

        // Check if order exist
        if (!$order) {
            return;
        }

        include_once(plugin_dir_path(__FILE__) . '../lib/rc2c_message_variable.php');

        $data = [
            'phone' => sanitize_text_field($this->options['admin_mobile_number']),
            'secret_key' => sanitize_text_field($this->options['secret_key']),
            'message' => sanitize_text_field(str_replace(array_keys($replacements), $replacements, $this->options['admin_sms_message'])),
        ];

        $this->send_sms_request($data);
    }
}

new RingCaptcha_SMS_Notifications();