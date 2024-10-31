<?php

if (!defined('ABSPATH')) exit;

class RingCaptcha_Admin {
    private $options;

    public function __construct() {
        $this->options = get_option('rc2c_settings');
        
        add_action('admin_menu', [$this, 'add_options_page']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function add_options_page() {
        add_submenu_page('woocommerce', 'RingCaptcha Options', 'RingCaptcha', 'manage_options', 'rc2c-options', [$this, 'render_options_page']);
    }

    public function register_settings() {
        register_setting('rc2c_settings_group', 'rc2c_settings');
    }

    public function render_options_page() {
        ?>
        <div class="wrap">
            <h2><?php echo esc_html__('RingCaptcha Options', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></h2>
            <form method="post" action="options.php">
                <?php
                settings_fields('rc2c_settings_group');
                wp_nonce_field('rc2c_options_nonce_action', 'rc2c_options_nonce');
                ?>
                <h3><?php echo esc_html__('RingCaptcha Settings', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></h3>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php echo esc_html__('Enable RingCaptcha2wooCommerce', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></th>
                        <td><input type="checkbox" name="rc2c_settings[enable]" value="1" <?php checked(1, isset($this->options['enable']) ? $this->options['enable'] : 0); ?> /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php echo esc_html__('App Key', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></th>
                        <td><input type="text" name="rc2c_settings[app_key]" value="<?php echo esc_attr($this->options['app_key'] ?? ''); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php echo esc_html__('Secret Key', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></th>
                        <td><input type="text" name="rc2c_settings[secret_key]" value="<?php echo esc_attr($this->options['secret_key'] ?? ''); ?>" /></td>
                    </tr>
                </table>
                <hr />
                <h3><?php echo esc_html__('Admin SMS Order Notifications', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></h3>
                <p style="color:#ff0000;">
                    <?php echo wp_kses_post('*Note: Please contact us at <a href="mailto:support@ringcaptcha.com?subject=Request%20for%20SMS%20Activation&body=Hi%20Ringcaptcha,%20we%20are%20requesting%20to%20activate%20the%20SMS%20notification%20from%20WooCommerce%20Phone%20Verification%20by%20RingCaptcha">support@ringcaptcha.com</a> if you want to use SMS notifications. <br/>&emsp;&emsp;&emsp;&nbsp;You could also reach us through our Intercom (the chat pop-up) <a href="https://ringcaptcha.com" target="_blank">here.</a>', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?>
                </p>
                <table class="form-table">
                    <tr valign="top">
                        <td class="forminp">
                            <fieldset>
                                <input type="checkbox" name="rc2c_settings[enable_admin_message]" value="1" <?php checked(1, isset($this->options['enable_admin_message']) ? $this->options['enable_admin_message'] : 0); ?> />
                                <label class="description" for="rc2c_settings[enable_admin_message]"><?php echo esc_html__('Enable SMS notifications', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php echo esc_html__('Admin Mobile Number', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></th>
                        <td><input type="text" name="rc2c_settings[admin_mobile_number]" placeholder="<?php echo esc_html('+18558233280'); ?>" value="<?php echo esc_attr($this->options['admin_mobile_number'] ?? ''); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php echo esc_html__('SMS Message', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></th>
                        <td><textarea name="rc2c_settings[admin_sms_message]" style="width:40%; height: 65px;" placeholder="<?php esc_attr_e('Hi {shop_name}, you have a new order ({order_id}) with total amount of ${order_amount}.', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?>"><?php echo esc_textarea($this->options['admin_sms_message'] ?? ''); ?></textarea></td>
                    </tr>
                </table>
                <hr />
                <h3><?php echo esc_html__('Customer SMS Order Notifications', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></h3>
                <table class="form-table">
                    <tr valign="top">
                        <td class="forminp">
                            <fieldset>
                                <input type="checkbox" name="rc2c_settings[enable_text_notification]" value="1" <?php checked(1, isset($this->options['enable_text_notification']) ? $this->options['enable_text_notification'] : 0); ?> />
                                <label class="description" for="rc2c_settings[enable_text_notification]"><?php echo esc_html__('Enable SMS notifications', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php echo esc_html__('Enable which order statuses you want your customers to be notified', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></th>
                        <td class="forminp">
                            <?php
                            $statuses = [
                                'pending' => 'Pending',
                                'on_hold' => 'On-Hold',
                                'processing' => 'Processing',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                                'refunded' => 'Refunded',
                                'failed' => 'Failed'
                            ];
                            foreach ($statuses as $status => $label) {
                                ?>
                                <fieldset>
                                    <input type="checkbox" name="rc2c_settings[enable_<?php echo esc_attr($status); ?>]" value="1" <?php checked(1, isset($this->options["enable_$status"]) ? $this->options["enable_$status"] : 0); ?> />
                                    <label class="description" for="rc2c_settings[enable_<?php echo esc_attr($status); ?>]"><?php echo esc_html($label); ?></label>
                                </fieldset>
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php echo esc_html__('Message Variables', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></th>
                        <td class="forminp">
                            <?php echo wp_kses_post('<ul>
                                <li><code>{name}</code> &ndash; Customer first name</li>
                                <li><code>{shop_name}</code> &ndash; Your shop name</li>
                                <li><code>{order_id}</code> &ndash; Order ID</li>
                                <li><code>{order_amount}</code> &ndash; The total amount of the order</li>
                            </ul>'); ?>
                        </td>
                    </tr>
                    <?php
                    foreach ($statuses as $status => $label) {
                        ?>
                        <tr valign="top">
                            <th scope="row">
                                <?php
                                    // translators: %s: order status
                                    echo esc_html(sprintf(__('SMS Message for %s', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'), $label)); 
                                ?>
                            </th>
                            <td><textarea name="rc2c_settings[<?php echo esc_attr($status); ?>_message]"
                             style="width:40%; height: 65px;" 
                             placeholder="<?php
                                // translators: %s: order status
                                echo esc_attr(sprintf(__('Hi {name}, your order ({order_id}) is now %s.', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'), $label)); ?>"><?php echo esc_textarea($this->options["{$status}_message"] ?? ''); ?></textarea>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <hr />
                <h3><?php echo esc_html__('GDPR', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></h3>
                <p><?php echo wp_kses_post(__('<b>*Note:</b> Need to get consent from users in order to be GDPR compliant? No problem!<br/>RingCaptcha now has a GDPR-compliant version of the phone verification widget.', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications')); ?></p>
                <input type="checkbox" name="rc2c_settings[gdpr_implementation]" value="1" <?php checked(1, isset($this->options['gdpr_implementation']) ? $this->options['gdpr_implementation'] : 0); ?> />
                <label class="description" for="rc2c_settings[gdpr_implementation]"><?php echo esc_html__('Check if you need to get consent from users for GDPR compliance.', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></label>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php echo esc_html__('GDPR Consent Message', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></th>
                        <td><textarea name="rc2c_settings[gdpr_consent_message]" style="width:40%; height: 65px;" placeholder="<?php esc_attr_e('I would like to receive discount updates and promotions in accordance with GDPR standards.', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?>"><?php echo esc_textarea($this->options['gdpr_consent_message'] ?? ''); ?></textarea></td>
                    </tr>
                </table>
                <hr />
                <h3><?php echo esc_html__('Troubleshooting', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></h3>
                <p><?php echo wp_kses_post(__('<b>*Note:</b> Some hosting providers do not support calling HTTP requests making it unable for the plugin to verify through <br/> our API if the phone number has successfully verified. This causes a \'stuck on checkout page\' problem. Check this <br/> fix to use a Javascript workaround.', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications')); ?></p>
                <input type="checkbox" name="rc2c_settings[js_implementation]" value="1" <?php checked(1, isset($this->options['js_implementation']) ? $this->options['js_implementation'] : 0); ?> />
                <label class="description" for="rc2c_settings[js_implementation]"><?php echo esc_html__('Stuck on Checkout Fix (Javascript Workaround)', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></label>
                <p class="submit">
                    <input type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?>" />
                </p>
            </form>
        </div>
        <hr />
        <h3><?php echo esc_html__('Send Direct SMS', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications'); ?></h3>
        <?php

        $data = [
            'phone' => isset($_POST['mobile_test']) ? sanitize_text_field(wp_unslash($_POST['mobile_test'])) : '',
            'secret_key' => $this->options['secret_key'] ?? '',
            'message' => isset($_POST['your_message']) ? sanitize_textarea_field(wp_unslash($_POST['your_message'])) : ''
        ];

        if (isset($_POST['submitted']) && sanitize_text_field(wp_unslash($_POST['submitted'])) == 'true') {
            $nonce = isset($_POST['rc2c_sms_nonce']) ? sanitize_text_field(wp_unslash($_POST['rc2c_sms_nonce'])) : '';

            if (!wp_verify_nonce($nonce, 'rc2c_sms_nonce_action')) {
                echo '<em style="color:#ff0000;">' . esc_html__('Security check failed', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications') . '</em>';
            } else {
                include(plugin_dir_path(__FILE__) . '../lib/rc2c_send_sms.php');
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
}

new RingCaptcha_Admin();