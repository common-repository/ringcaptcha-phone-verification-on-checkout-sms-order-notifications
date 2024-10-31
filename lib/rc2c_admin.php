<?php
/******************************
* Admin Option for plugin
******************************/

if ( ! defined( 'ABSPATH' ) ) exit;

function rc2c_options_page_old() {
    global $rc2c_options;
    global $rc2c_plugin_name;

    // Retrieve the options and ensure it's an array
    $rc2c_options = get_option('rc2c_settings', array());
    ?>

    <div class="wrap">
        <h2><?php echo esc_html($rc2c_plugin_name) . ' ' . esc_html(__('Options', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications')); ?></h2>
        <form method="post" action="options.php">
            <?php settings_fields('rc2c_settings_group'); ?>
            <?php wp_nonce_field('rc2c_options_nonce_action', 'rc2c_options_nonce'); ?>
            <p>
                <input id="rc2c_settings[enable]" name="rc2c_settings[enable]" type="checkbox" value="1" <?php checked(1, isset($rc2c_options['enable'])); ?> />
                <label class="description" for="rc2c_settings[enable]"><?php echo esc_html__('Enable RingCaptcha2wooCommerce', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></label>
            </p>
            <h3><?php echo esc_html__('RingCaptcha Settings', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></h3>
            <table class="form-table">
                <tr valign="top">
                    <?php echo wp_kses_post(__('To get your App Key and Secret Key click <a href="https://my.ringcaptcha.com/register" target="_blank">Here</a>', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications')); ?>
                </tr>
                <tr valign="top">
                    <th class="titledesc" scope="row"><label class="description" for="rc2c_settings[app_key]"><?php echo esc_html__('App key', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></label></th>
                    <td class="forminp"><input id="rc2c_settings[app_key]" name="rc2c_settings[app_key]" type="text" value="<?php echo esc_attr(isset($rc2c_options['app_key']) ? $rc2c_options['app_key'] : ''); ?>"/></td>
                </tr>
                <tr valign="top">
                    <th class="titledesc" scope="row"><label class="description" for="rc2c_settings[secret_key]"><?php echo esc_html__('Secret key', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></label></th>
                    <td class="forminp"><input id="rc2c_settings[secret_key]" name="rc2c_settings[secret_key]" type="text" value="<?php echo esc_attr(isset($rc2c_options['secret_key']) ? $rc2c_options['secret_key'] : ''); ?>"/></td>
                </tr>
            </table>
            <hr />
            <h3><?php echo esc_html__('Admin SMS Order Notifications', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></h3>
            <p style="color:#ff0000;"><?php echo wp_kses_post('*Note: Please contact us at <a href="mailto:support@ringcaptcha.com?subject=Request%20for%20SMS%20Activation&body=Hi%20Ringcaptcha,%20we%20are%20requesting%20to%20activate%20the%20SMS%20notification%20from%20WooCommerce%20Phone%20Verification%20by%20RingCaptcha">support@ringcaptcha.com</a> if you want to use SMS notifications. <br/>&emsp;&emsp;&emsp;&nbsp;You could also reach us through our Intercom (the chat pop-up) <a href="https://ringcaptcha.com" target="_blank">here.</a>', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></p>
            <table class="form-table">
                <tr valign="top">
                    <td class="forminp"><fieldset>
                        <input id="rc2c_settings[enable_admin_message]" name="rc2c_settings[enable_admin_message]" type="checkbox" value="1" <?php checked(1, isset($rc2c_options['enable_admin_message'])); ?> />
                        <label class="description" for="rc2c_settings[enable_admin_message]"><?php echo esc_html__('Enable SMS notifications', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></label>
                    </fieldset></td>
                </tr>
                <tr valign="top">
                    <th class="titledesc" scope="row"><?php echo esc_html__('Admin Mobile Number', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></th>
                    <td class="forminp"><input id="rc2c_settings[admin_mobile_number]" placeholder="<?php echo esc_html('+18558233280'); ?>" name="rc2c_settings[admin_mobile_number]" type="text" value="<?php echo esc_attr(isset($rc2c_options['admin_mobile_number']) ? $rc2c_options['admin_mobile_number'] : ''); ?>"/></td>
                </tr>
                <tr valign="top">
                    <th class="titledesc" scope="row"><?php echo esc_html__('SMS Message', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></th>
                    <td class="forminp"><textarea style="width:40%; height: 65px;" placeholder="<?php echo esc_html('Hi {shop_name}, you have a new order ({order_id}) with total amount of ${order_amount}.'); ?>" name="rc2c_settings[admin_sms_message]"><?php echo esc_textarea(isset($rc2c_options['admin_sms_message']) ? $rc2c_options['admin_sms_message'] : ''); ?></textarea></td>
                </tr>
            </table>
            <hr />
            <h3><?php echo esc_html__('Customer SMS Order Notifications', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></h3>
            <table class="form-table">
                <tr valign="top">
                    <td class="forminp"><fieldset>
                        <input id="rc2c_settings[enable_text_notification]" name="rc2c_settings[enable_text_notification]" type="checkbox" value="1" <?php checked(1, isset($rc2c_options['enable_text_notification'])); ?> />
                        <label class="description" for="rc2c_settings[enable_text_notification]"><?php echo esc_html__('Enable SMS notifications', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></label>
                    </fieldset></td>
                </tr>
                <tr valign="top">
                    <th class="titledesc" scope="row"><?php echo esc_html__('Enable which order statuses you want your customers to be notified', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></th>
                    <td class="forminp"><fieldset>
                        <input id="rc2c_settings[enable_pending]" name="rc2c_settings[enable_pending]" type="checkbox" value="1" <?php checked(1, isset($rc2c_options['enable_pending'])); ?> />
                        <label class="description" for="rc2c_settings[enable_pending]"><?php echo esc_html__('Pending', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></label>
                    </fieldset>
                    <fieldset>
                        <input id="rc2c_settings[enable_on_hold]" name="rc2c_settings[enable_on_hold]" type="checkbox" value="1" <?php checked(1, isset($rc2c_options['enable_on_hold'])); ?> />
                        <label class="description" for="rc2c_settings[enable_on_hold]"><?php echo esc_html__('On-Hold', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></label>
                    </fieldset>
                    <fieldset>
                        <input id="rc2c_settings[enable_processing]" name="rc2c_settings[enable_processing]" type="checkbox" value="1" <?php checked(1, isset($rc2c_options['enable_processing'])); ?> />
                        <label class="description" for="rc2c_settings[enable_processing]"><?php echo esc_html__('Processing', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></label>
                    </fieldset>
                    <fieldset>
                        <input id="rc2c_settings[enable_completed]" name="rc2c_settings[enable_completed]" type="checkbox" value="1" <?php checked(1, isset($rc2c_options['enable_completed'])); ?> />
                        <label class="description" for="rc2c_settings[enable_completed]"><?php echo esc_html__('Completed', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></label>
                    </fieldset>
                    <fieldset>
                        <input id="rc2c_settings[enable_cancelled]" name="rc2c_settings[enable_cancelled]" type="checkbox" value="1" <?php checked(1, isset($rc2c_options['enable_cancelled'])); ?> />
                        <label class="description" for="rc2c_settings[enable_cancelled]"><?php echo esc_html__('Cancelled', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></label>
                    </fieldset>
                    <fieldset>
                        <input id="rc2c_settings[enable_refunded]" name="rc2c_settings[enable_refunded]" type="checkbox" value="1" <?php checked(1, isset($rc2c_options['enable_refunded'])); ?> />
                        <label class="description" for="rc2c_settings[enable_refunded]"><?php echo esc_html__('Refunded', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></label>
                    </fieldset>
                    <fieldset>
                        <input id="rc2c_settings[enable_failed]" name="rc2c_settings[enable_failed]" type="checkbox" value="1" <?php checked(1, isset($rc2c_options['enable_failed'])); ?> />
                        <label class="description" for="rc2c_settings[enable_failed]"><?php echo esc_html__('Failed', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></label>
                    </fieldset>
                    </td>
                </tr>
                <tr valign="top">
                    <th class="titledesc" scope="row"><?php echo esc_html__('Message Variables', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></th>
                    <td class="forminp">
                        <?php echo wp_kses_post('<ul>
                            <li><code>{name}</code> &ndash; Customer first name</li>
                            <li><code>{shop_name}</code> &ndash; Your shop name</li>
                            <li><code>{order_id}</code> &ndash; Order ID</li>
                            <li><code>{order_amount}</code> &ndash; The total amount of the order</li>
                        </ul>', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?>
                    </td>
                </tr>
                <!-- SMS Messages -->
                <?php
                $statuses = ['pending', 'on_hold', 'processing', 'completed', 'cancelled', 'refunded', 'failed'];
                foreach ($statuses as $status) {
                    ?>
                    <tr valign="top">
                        <th class="titledesc"
                            scope="row"><?php 
                                // translators: %s: order status
                                echo sprintf(esc_html__('%s SMS Message', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'), esc_html(ucfirst($status))); ?></th>
                        <td class="forminp">
                            <textarea style="width:40%; height: 65px;"
                                placeholder="<?php
                                    // translators: %s: order status 
                                    echo esc_html('Hi {name}, your order ({order_id}) is now ' . ucfirst($status) . '.'); ?>" name="rc2c_settings[<?php echo esc_attr($status); ?>_message]"><?php echo esc_textarea(isset($rc2c_options["{$status}_message"]) ? $rc2c_options["{$status}_message"] : ''); ?></textarea>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <hr />
            <h3><?php echo esc_html('GDPR'); ?></h3>
            <p><?php echo wp_kses_post(__('<b>*Note:</b> Need to get consent from users in order to be GDPR compliant? No problem!<br/>RingCaptcha now has a GDPR-compliant version of the phone verification widget.', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications')); ?></p>
            <input id="rc2c_settings[gdpr_implementation]" name="rc2c_settings[gdpr_implementation]" type="checkbox" value="1" <?php checked(1, isset($rc2c_options['gdpr_implementation'])); ?> />
            <label class="description" for="rc2c_settings[gdpr_implementation]"><?php echo esc_html__('Check if you need to get consent from users for GDPR compliance.', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></label>
            <table class="form-table">
                <tr valign="top">
                    <th class="titledesc" scope="row"><?php echo esc_html__('GDPR Consent Message', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></th>
                    <td class="forminp"><textarea style="width:40%; height: 65px;" placeholder="<?php echo esc_html__('I would like to receive discount updates and promotions in accordance with GDPR standards.', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?>" name="rc2c_settings[gdpr_consent_message]"><?php echo esc_textarea(isset($rc2c_options['gdpr_consent_message']) ? $rc2c_options['gdpr_consent_message'] : ''); ?></textarea></td>
                </tr>
            </table>
            <hr />
            <h3><?php echo esc_html__('Troubleshooting', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></h3>
            <p><?php echo wp_kses_post(__('<b>*Note:</b> Some hosting providers do not support calling HTTP requests making it unable for the plugin to verify through <br/> our API if the phone number has successfully verified. This causes a \'stuck on checkout page\' problem. Check this <br/> fix to use a Javascript workaround.', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications')); ?></p>
            <input id="rc2c_settings[js_implementation]" name="rc2c_settings[js_implementation]" type="checkbox" value="1" <?php checked(1, isset($rc2c_options['js_implementation']) && $rc2c_options['js_implementation']); ?> />
            <label class="description" for="rc2c_settings[js_implementation]"><?php echo esc_html__('Stuck on Checkout Fix (Javascript Workaround)', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></label>
            <p class="submit">
                <input type="submit" class="button-primary" value="<?php echo esc_html__('Save Changes', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?>" />
            </p>
        </form>
    </div>
    <hr />
    <h3><?php echo esc_html__('Send Direct SMS', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></h3>
    <?php

    $data = array();
    $data['phone'] = isset($_POST['mobile_test']) ? sanitize_text_field(wp_unslash($_POST['mobile_test'])) : '';
    $data['secret_key'] = isset($rc2c_options['secret_key']) ? $rc2c_options['secret_key'] : '';
    $data['message'] = isset($_POST['your_message']) ? sanitize_textarea_field(wp_unslash($_POST['your_message'])) : '';

    if (isset($_POST['submitted']) && sanitize_text_field(wp_unslash($_POST['submitted'])) == 'true') {
        $nonce = isset($_POST['rc2c_sms_nonce']) ? sanitize_text_field(wp_unslash($_POST['rc2c_sms_nonce'])) : '';

        if (!wp_verify_nonce($nonce, 'rc2c_sms_nonce_action')) {
            echo '<em style="color:#ff0000;">' . esc_html__('Security check failed', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications') . '</em>';
        } else {
            include(plugin_dir_path(__FILE__) . 'rc2c_send_sms.php');
            $warning = __('<em style="color:#00960C;">SMS has been sent to recipient</em><br/>', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications');
        }
    }

    ?>
    <form name="sendsmsdirect" method="post" action="">
        <table class="form-table">
            <tr valign="top">
                <th class="titledesc" scope="row"><?php echo esc_html__('Mobile Number', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></th>
                <td class="forminp">
                    <?php if (isset($warning)) { echo wp_kses_post($warning); } ?>
                    <input id="rc2c_settings[mobile_test]" placeholder="<?php echo esc_html('+18558233280'); ?>" name="mobile_test" type="text" required="required" value="<?php echo esc_attr($data['phone']); ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th class="titledesc" scope="row"><?php echo esc_html__('SMS Message', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></th>
                <td class="forminp">
                    <textarea style="width:40%; height: 65px;" id="your_message" required="required" maxlength="160" name="your_message"><?php echo esc_textarea($data['message']); ?></textarea><br/>
                    <div id="charcount"></div>
                </td>
            </tr>
            <tr valign="top">
                <th class="titledesc" scope="row"></th>
                <td class="forminp">
                    <input type="hidden" name="submitted" value="true" />
                    <?php wp_nonce_field('rc2c_sms_nonce_action', 'rc2c_sms_nonce'); ?> 
                    <input type="submit" id="submit" class="button" value="<?php echo esc_html__('Send', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?>" />
                </td>
            </tr>
        </table>
    </form>
    <hr />
    <p><?php echo wp_kses_post('If you have problems using this plugin, you can reach us through our Intercom <a href="https://ringcaptcha.com" target="_blank">here</a> or send us an email at <a href="mailto:support@ringcaptcha.com">support@ringcaptcha.com</a>', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></p>
    <?php
}

function rc2c_add_options_link_old() {
    global $rc2c_plugin_name;

    add_submenu_page('woocommerce', $rc2c_plugin_name . ' Options', 'RingCaptcha', 'manage_options', 'rc2c-options', 'rc2c_options_page_old');
}
add_action('admin_menu', 'rc2c_add_options_link_old');

function rc2c_register_settings_old() {
    // creates our settings in the options table
    register_setting('rc2c_settings_group', 'rc2c_settings');
}
add_action('admin_init', 'rc2c_register_settings_old');
