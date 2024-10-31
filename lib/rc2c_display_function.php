<?php

/********************************************
* Add the RingCaptcha widget on checkout page
*********************************************/

if ( ! defined( 'ABSPATH' ) ) exit;

function rc2c_custom_checkout_field_old() {
    global $rc2c_options;
    $options = get_option('rc2c_settings');

    $accept_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_ACCEPT_LANGUAGE'])) : 'en';

    $locales = explode(',', $accept_language);
    $user_locale = 'en'; 
    if (!empty($locales)) {
        $user_locale = substr($locales[0], 0, 2); 
    }

    $gdpr_implementation = isset($rc2c_options['gdpr_implementation']) ? $rc2c_options['gdpr_implementation'] : false;
    $js_implementation = isset($rc2c_options['js_implementation']) ? $rc2c_options['js_implementation'] : false;
    $app_key = isset($rc2c_options['app_key']) ? sanitize_text_field($rc2c_options['app_key']) : '';
    $gdpr_consent_message_default = "I would like to receive discount updates and promotions in accordance with GDPR standards.";
    $gdpr_consent_message = !empty($rc2c_options['gdpr_consent_message']) ? $rc2c_options['gdpr_consent_message'] : $gdpr_consent_message_default;

    if ($gdpr_implementation == true) {
        echo '<div id="widget-point"></div>';
        echo '<div style="clear:both;"></div>';
        echo '<input id="gdpr_consent" type="hidden" name="gdpr_consent" value="false">';

        if ($js_implementation == true) { 
            echo '<input id="ringcaptcha_verified" type="hidden" name="ringcaptcha_verified" value="false">';
        }

        wp_enqueue_script('ringcaptcha-gdpr', plugin_dir_url(__FILE__) . 'ringcaptcha-gdpr.js', array('jquery'), '1.0', true);
        wp_localize_script('ringcaptcha-gdpr', 'rc_options', array(
            'app_key' => esc_attr($app_key),
            'js_implementation' => $js_implementation,
            'gdpr_consent_message' => $gdpr_consent_message,
        ));
        wp_enqueue_script('api-ringcaptcha', 'https://cdn.ringcaptcha.com/widget/v2/bundle.min.js', array(), '1.0', true);

    } else if ($js_implementation == true) {
        echo '<input id="ringcaptcha_verified" type="hidden" name="ringcaptcha_verified" value="false">';
        echo '<div id="widget-point"></div>';
        echo '<div style="clear:both;"></div>';

        wp_enqueue_script('ringcaptcha-verified', plugin_dir_url(__FILE__) . 'ringcaptcha-verified.js', array('jquery'), '1.0', true);
        wp_localize_script('ringcaptcha-verified', 'rc_options', array('app_key' => esc_attr($app_key)));
        wp_enqueue_script('api-ringcaptcha', 'https://cdn.ringcaptcha.com/widget/v2/bundle.min.js', array(), '1.0', true);
    } else {
        ?>
        <div data-widget data-app="<?php echo esc_attr($app_key); ?>" data-locale="<?php echo esc_attr($user_locale); ?>" data-type="dual"></div>
        <div style="clear:both;"></div>
        <?php
        wp_enqueue_script('api-ringcaptcha', 'https://cdn.ringcaptcha.com/widget/v2/bundle.min.js', array(), '1.0', true);
    }

    wp_nonce_field('rc2c_nonce_action', 'rc2c_nonce');
}

add_action('woocommerce_checkout_billing', 'rc2c_custom_checkout_field_old', 15);

function rc2c_custom_override_checkout_fields_old($fields) {
    global $rc2c_options;

    if (isset($rc2c_options['enable']) && $rc2c_options['enable'] == true) {
        $fields['billing']['billing_email'] = array(
            'label' => __('Email', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'),
            'placeholder' => __('Email', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'),
            'required' => true,
            'clear' => false,
            'type' => 'text',
            'class' => array('form-row-wide')
        );
        unset($fields['billing']['billing_phone']);

        require_once('Ringcaptcha.php');

        $app_key = isset($rc2c_options['app_key']) ? $rc2c_options['app_key'] : '';
        $secret_key = isset($rc2c_options['secret_key']) ? $rc2c_options['secret_key'] : '';
        $lib = new Ringcaptcha($app_key, $secret_key);
        $lib->setSecure(true);

        if (!empty($_POST['billing_first_name']) || !empty($_POST['billing_last_name']) || !empty($_POST['billing_country']) || !empty($_POST['billing_address_1']) || !empty($_POST['billing_city']) || !empty($_POST['billing_state']) || !empty($_POST['billing_postcode']) || !empty($_POST['billing_email'])) {
            if (!isset($_POST['rc2c_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['rc2c_nonce'])), 'rc2c_nonce_action')) {
                wc_add_notice(__('Security check failed', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'), 'error');
                return $fields;
            }

            // Sanitize input fields
            $billing_first_name = sanitize_text_field(wp_unslash($_POST['billing_first_name']));
            $billing_last_name = sanitize_text_field(wp_unslash($_POST['billing_last_name']));
            $billing_country = sanitize_text_field(wp_unslash($_POST['billing_country']));
            $billing_address_1 = sanitize_text_field(wp_unslash($_POST['billing_address_1']));
            $billing_city = sanitize_text_field(wp_unslash($_POST['billing_city']));
            $billing_state = sanitize_text_field(wp_unslash($_POST['billing_state']));
            $billing_postcode = sanitize_text_field(wp_unslash($_POST['billing_postcode']));
            $billing_email = sanitize_email(wp_unslash($_POST['billing_email']));

            if (isset($_POST["ringcaptcha_session_id"]) && isset($_POST["ringcaptcha_pin_code"]) && isset($_POST["ringcaptcha_phone_number"])) {
                $ringcaptcha_session_id = sanitize_text_field(wp_unslash($_POST["ringcaptcha_session_id"]));
                $ringcaptcha_pin_code = sanitize_text_field(wp_unslash($_POST["ringcaptcha_pin_code"]));
                $ringcaptcha_phone_number = sanitize_text_field(wp_unslash($_POST["ringcaptcha_phone_number"]));

                if ($rc2c_options['js_implementation'] == true && isset($_POST['ringcaptcha_verified']) && sanitize_text_field(wp_unslash($_POST['ringcaptcha_verified'])) == "true") {
                    $user_phone = $ringcaptcha_phone_number;

                } else if ($rc2c_options['js_implementation'] == false && $lib->isValid($ringcaptcha_pin_code, $ringcaptcha_session_id)) {
                    $user_phone = $lib->getPhoneNumber();

                } else {
                    wc_add_notice(
                        sprintf(
                            '<strong>%s</strong> %s',
                            esc_html(__('Billing Phone', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications')),
                            esc_html__('is a required field (please verify your phone number).', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications')
                        ),
                        'error',
                        30
                    );
                }
            } else {
                wc_add_notice(
                    sprintf(
                        '<strong>%s</strong> %s',
                        esc_html__('Billing Phone', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'),
                        esc_html__('is a required field (please verify your phone number).', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications')
                    ),
                    'error',
                    30
                );
            }
        }
    }

    return $fields;
}

add_filter('woocommerce_checkout_fields', 'rc2c_custom_override_checkout_fields_old');

function rc2c_custom_checkout_field_process_old() {
    // Verify the nonce for security
    if (!isset($_POST['rc2c_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['rc2c_nonce'])), 'rc2c_nonce_action')) {
        wc_add_notice(esc_html__('Security check failed', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'), 'error');
        return;
    }

    $billing_phone = isset($_POST['ringcaptcha_phone_number']) ? sanitize_text_field(wp_unslash($_POST['ringcaptcha_phone_number'])) : '';

    if (empty($billing_phone)) {
        wc_add_notice(esc_html__('Phone is a required field.', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'), 'error');
    }
}
add_action('woocommerce_checkout_process', 'rc2c_custom_checkout_field_process_old');

function rc2c_phone_verified_old($order_id) {
    // Verify the nonce for security
    if (!isset($_POST['rc2c_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['rc2c_nonce'])), 'rc2c_nonce_action')) {
        return; 
    }

    global $rc2c_options;
    
    // Check if the feature is enabled
    if (isset($rc2c_options['enable']) && $rc2c_options['enable'] == true) {
        $user_phone = isset($_POST['ringcaptcha_phone_number']) ? sanitize_text_field(wp_unslash($_POST['ringcaptcha_phone_number'])) : '';

        if (!empty($user_phone)) {
            $order = wc_get_order($order_id);
            if ($order) {
                error_log("Updating order meta for _billing_phone");
                $order->set_billing_phone(sanitize_text_field($user_phone)); // Use setter method
                $order->save();
            }
        }
    }
}

function rc2c_gdpr_meta_old($order_id) {
    // Verify the nonce for security
    if (!isset($_POST['rc2c_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['rc2c_nonce'])), 'rc2c_nonce_action')) {
        return;
    }

    $order = wc_get_order($order_id);
    if ($order) {
        $order->update_meta_data(
            'gdpr_consent',
            isset($_POST['gdpr_consent']) ? sanitize_text_field(wp_unslash($_POST['gdpr_consent'])) : ''
        );
        $order->save();
    }
}

add_action('woocommerce_checkout_update_order_meta', 'rc2c_phone_verified_old'); // Save phone verification after order creation
add_action('woocommerce_checkout_update_order_meta', 'rc2c_gdpr_meta_old'); // Save GDPR consent after order creation