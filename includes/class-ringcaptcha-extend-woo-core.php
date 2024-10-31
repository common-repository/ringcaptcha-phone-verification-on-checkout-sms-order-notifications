<?php
class RingCaptcha_Extend_Woo_Core {
    private $name = 'ringcaptcha';

    public function init() {
        $this->save_phone_verification();
        $this->show_phone_verification_in_order();
        $this->show_phone_verification_in_order_confirmation();
        $this->show_phone_verification_in_order_email();
        $this->validate_phone_verification();
        $this->add_phone_number();

        add_action('woocommerce_checkout_create_order', [$this, 'save_phone_number_from_widget'], 10, 2);
    }

    function save_phone_number_from_widget($order, $data) {
        if (!isset($_POST['rc2c_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['rc2c_nonce'])), 'rc2c_nonce_action')) {
            return;
        }

        if (isset($_POST['billing_phone'])) {
            $order->set_billing_phone(sanitize_text_field(wp_unslash($_POST['billing_phone'])));
        }
    }

    private function save_phone_verification() {
        add_action('woocommerce_store_api_checkout_update_order_from_request', function($order, $request) {
            if (!isset($request['rc2c_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($request['rc2c_nonce'])), 'rc2c_nonce_action')) {
                return;
            }

            $ringcaptcha_request_data = $request['extensions'][$this->name];
            $phone_verification_data = $ringcaptcha_request_data['phoneVerification'] ?? '';

            if (!empty($phone_verification_data)) {
                $order->update_meta_data('ringcaptcha_phone_verification', $phone_verification_data);
                $order->save();
            }
        }, 10, 2);
    }

    private function add_phone_number() {
        add_action('woocommerce_store_api_checkout_update_order_from_request', function($order, $request) {
            if (!isset($request['rc2c_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($request['rc2c_nonce'])), 'rc2c_nonce_action')) {
                return;
            }

            $ringcaptcha_request_data = $request['extensions'][$this->name];
            $phone_number = $ringcaptcha_request_data['phoneNumber'] ?? '';

            if (!empty($phone_number)) {
                $order->set_billing_phone(sanitize_text_field($phone_number));
                $order->save();
            } else {
                error_log('No phone number found in extensions data.');
            }
        }, 10, 2);
    }

    private function show_phone_verification_in_order() {
        add_action('woocommerce_admin_order_data_after_billing_address', function($order) {
            $phone_verification_data = $order->get_meta('ringcaptcha_phone_verification');
            if ($phone_verification_data) {
                echo '<div><strong>' . esc_html__('Phone Verification', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications') . '</strong>';
                echo '<p>' . esc_html($phone_verification_data) . '</p></div>';
            }
        });
    }

    private function show_phone_verification_in_order_confirmation() {
        add_action('woocommerce_thankyou', function($order_id) {
            $order = wc_get_order($order_id);
            $phone_verification_data = $order->get_meta('ringcaptcha_phone_verification');
            if ($phone_verification_data) {
                echo '<h2>' . esc_html__('Phone Verification', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications') . '</h2>';
                echo '<p>' . esc_html($phone_verification_data) . '</p>';
            }
        });
    }

    private function show_phone_verification_in_order_email() {
        add_action('woocommerce_email_after_order_table', function($order, $sent_to_admin, $plain_text, $email) {
            $phone_verification_data = $order->get_meta('ringcaptcha_phone_verification');
            if ($phone_verification_data) {
                echo '<h2>' . esc_html__('Phone Verification', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications') . '</h2>';
                echo '<p>' . esc_html($phone_verification_data) . '</p>';
            }
        }, 10, 4);
    }

    private function validate_phone_verification() {
        add_action('woocommerce_checkout_process', function() {
            // Check if the nonce is set and valid
            if (!isset($_POST['rc2c_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['rc2c_nonce'])), 'rc2c_nonce_action')) {
                return;
            }

            // Sanitize and validate phone verification status
            $phone_verification = isset($_POST['extensions']['ringcaptcha']['phoneVerification']) ? sanitize_text_field(wp_unslash($_POST['extensions']['ringcaptcha']['phoneVerification'])) : '';

            if ($phone_verification !== 'verified') {
                // Add an error notice if the phone number is not verified
                wc_add_notice(__('Please verify your phone number before placing the order.', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'), 'error');
            }
        });
    }

    public function add_nonce_field() {
        echo '<input type="hidden" name="rc2c_nonce" value="' . esc_attr(wp_create_nonce('rc2c_nonce_action')) . '" />';
    }
}