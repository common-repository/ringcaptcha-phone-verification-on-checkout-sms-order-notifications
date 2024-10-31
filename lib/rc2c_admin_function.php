<?php

/******************************
* Send SMS notifications
******************************/

if (!defined('ABSPATH')) exit;

if (isset($rc2c_options['enable_text_notification']) && $rc2c_options['enable_text_notification'] == true) {
    // Function to send SMS notifications based on order status
    function rc2c_send_sms_notification($order_id, $message_key) {
        $order = wc_get_order($order_id);
        
        if (!$order) {
            return;
        }

        global $rc2c_options;

        // SMS Replacements Variables
        $message_variable_file = plugin_dir_path(__FILE__) . 'rc2c_message_variable.php';
        if (file_exists($message_variable_file)) {
            include($message_variable_file);
        }

        $data = array();
        $data['phone'] = sanitize_text_field($order->get_meta('_billing_phone'));
        $data['secret_key'] = isset($rc2c_options['secret_key']) ? sanitize_text_field($rc2c_options['secret_key']) : '';
        $data['message'] = is_array($rc2c_options) && isset($rc2c_options[$message_key]) ? 
            sanitize_text_field(str_replace(array_keys($replacements), $replacements, $rc2c_options[$message_key])) : '';

        // Sends SMS using RingCaptcha API
        $send_sms_file = plugin_dir_path(__FILE__) . 'rc2c_send_sms.php';
        if (file_exists($send_sms_file)) {
            include($send_sms_file);
        } else {
            error_log('Send SMS file not found: ' . $send_sms_file);
        }
    }

    // Hook into WooCommerce order status changes
    if (isset($rc2c_options['enable_pending']) && $rc2c_options['enable_pending'] == true) {
        add_action('woocommerce_order_status_pending', function($order_id) {
            rc2c_send_sms_notification($order_id, 'pending_message');
        });
    }

    if (isset($rc2c_options['enable_failed']) && $rc2c_options['enable_failed'] == true) {
        add_action('woocommerce_order_status_failed', function($order_id) {
            rc2c_send_sms_notification($order_id, 'failed_message');
        });
    }

    if (isset($rc2c_options['enable_on_hold']) && $rc2c_options['enable_on_hold'] == true) {
        add_action('woocommerce_order_status_on-hold', function($order_id) {
            rc2c_send_sms_notification($order_id, 'on_hold_message');
        });
    }

    if (isset($rc2c_options['enable_processing']) && $rc2c_options['enable_processing'] == true) {
        add_action('woocommerce_order_status_processing', function($order_id) {
            rc2c_send_sms_notification($order_id, 'processing_message');
        });
    }

    if (isset($rc2c_options['enable_completed']) && $rc2c_options['enable_completed'] == true) {
        add_action('woocommerce_order_status_completed', function($order_id) {
            rc2c_send_sms_notification($order_id, 'completed_message');
        });
    }

    if (isset($rc2c_options['enable_refunded']) && $rc2c_options['enable_refunded'] == true) {
        add_action('woocommerce_order_status_refunded', function($order_id) {
            rc2c_send_sms_notification($order_id, 'refunded_message');
        });
    }

    if (isset($rc2c_options['enable_cancelled']) && $rc2c_options['enable_cancelled'] == true) {
        add_action('woocommerce_order_status_cancelled', function($order_id) {
            rc2c_send_sms_notification($order_id, 'cancelled_message');
        });
    }

    if (isset($rc2c_options['enable_admin_message']) && $rc2c_options['enable_admin_message'] == true) {
        add_action('woocommerce_new_order', function($order_id) {
            $data['phone'] = isset($rc2c_options['admin_mobile_number']) ? sanitize_text_field($rc2c_options['admin_mobile_number']) : '';
            rc2c_send_sms_notification($order_id, 'admin_sms_message');
        });
    }
}