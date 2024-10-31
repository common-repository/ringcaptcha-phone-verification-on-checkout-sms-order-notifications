<?php
use Automattic\WooCommerce\Blocks\StoreApi\Schemas\CheckoutSchema;

class RingCaptcha_Extend_Store_Endpoint {
    private static $extend;

    const IDENTIFIER = 'ringcaptcha';

    public static function init() {
        self::$extend = Automattic\WooCommerce\StoreApi\StoreApi::container()->get(Automattic\WooCommerce\StoreApi\Schemas\ExtendSchema::class);
        self::extend_store();
    }

    public static function extend_store() {
        if (is_callable([self::$extend, 'register_endpoint_data'])) {
            self::$extend->register_endpoint_data([
                'endpoint' => CheckoutSchema::IDENTIFIER,
                'namespace' => self::IDENTIFIER,
                'schema_callback' => [self::class, 'extend_checkout_schema'],
                'schema_type' => ARRAY_A,
            ]);
        }
    }

    public static function extend_checkout_schema() {
        return [
            'phoneVerification' => [
                'description' => 'Phone verification data',
                'type' => 'string', // Ensure this matches the data type being sent
                'context' => ['view', 'edit'],
                'readonly' => true,
                'optional' => true,
                'arg_options' => [
                    'validate_callback' => function($value) {
                        return is_string($value); // Ensure this matches the data type being sent
                    },
                ],
            ],
            'phoneNumber' => ['type' => 'string'],
        ];
    }
}
