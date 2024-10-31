<?php

use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;

class RingCaptcha_Blocks_Integration implements IntegrationInterface {
    public function get_name() {
        return 'ringcaptcha';
    }

    public function initialize() {
        $this->register_ringcaptcha_block_frontend_scripts();
        $this->register_ringcaptcha_block_editor_scripts();
        $this->register_main_integration();
    }

    private function register_main_integration() {
        $script_path = '/build/index.js';
        $script_url = plugins_url($script_path, __DIR__);
        $script_asset_path = plugin_dir_path(__DIR__) . 'build/index.asset.php';
        $script_asset = file_exists($script_asset_path) ? require $script_asset_path : ['dependencies' => [], 'version' => RINGCAPTCHA_VERSION];

        wp_register_script('ringcaptcha-blocks-integration', $script_url, $script_asset['dependencies'], $script_asset['version'], true);

        // Fetch the options
        $rc2c_options = get_option('rc2c_settings', []);
        wp_localize_script('ringcaptcha-blocks-integration', 'rc_options', array(
            'app_key' => $rc2c_options['app_key'] ?? '',
            'js_implementation' => $rc2c_options['js_implementation'] ?? '',
            'gdpr_implementation' => $rc2c_options['gdpr_implementation'] ?? '',
            'gdpr_consent_message' => $rc2c_options['gdpr_consent_message'] ?? '',
        ));
        wp_enqueue_script('api-ringcaptcha', 'https://cdn.ringcaptcha.com/widget/v2/bundle.min.js', array(), '1.0', true);
        wp_set_script_translations('ringcaptcha-blocks-integration', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications', plugin_dir_path(__DIR__) . 'languages');
    }

    private function register_ringcaptcha_override_scripts() {
        $script_path = '/build/override-checkout.js';
        $script_url = plugins_url($script_path, __DIR__);
        $script_asset_path = plugin_dir_path(__DIR__) . 'build/override-checkout.asset.php';
        $script_asset = file_exists($script_asset_path) ? require $script_asset_path : ['dependencies' => [], 'version' => RINGCAPTCHA_VERSION];

        wp_register_script('ringcaptcha-override-checkout', $script_url, $script_asset['dependencies'], $script_asset['version'], true);
        wp_enqueue_script('ringcaptcha-override-checkout');
    }

    public function get_script_handles() {
        return ['ringcaptcha-blocks-integration', 'ringcaptcha-block-frontend'];
    }

    public function get_editor_script_handles() {
        return ['ringcaptcha-blocks-integration', 'ringcaptcha-block-editor'];
    }

    public function get_script_data() {
        return ['ringcaptcha-active' => true];
    }

    public function register_ringcaptcha_block_editor_scripts() {
        $script_path = '/build/ringcaptcha-block.js';
        $script_url = plugins_url($script_path, __DIR__);
        $script_asset_path = plugin_dir_path(__DIR__) . 'build/ringcaptcha-block.asset.php';
        $script_asset = file_exists($script_asset_path) ? require $script_asset_path : ['dependencies' => [], 'version' => RINGCAPTCHA_VERSION];

        wp_register_script('ringcaptcha-block-editor', $script_url, $script_asset['dependencies'], $script_asset['version'], true);
        wp_set_script_translations('ringcaptcha-block-editor', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications', plugin_dir_path(__DIR__) . 'languages');
    }

    public function register_ringcaptcha_block_frontend_scripts() {
        $script_path = '/build/ringcaptcha-block-frontend.js';
        $script_url = plugins_url($script_path, __DIR__);
        $script_asset_path = plugin_dir_path(__DIR__) . 'build/ringcaptcha-block-frontend.asset.php';
        $script_asset = file_exists($script_asset_path) ? require $script_asset_path : ['dependencies' => [], 'version' => RINGCAPTCHA_VERSION];

        wp_register_script('ringcaptcha-block-frontend', $script_url, $script_asset['dependencies'], $script_asset['version'], true);
        wp_set_script_translations('ringcaptcha-block-frontend', 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications', plugin_dir_path(__DIR__) . 'languages');
    }
}
