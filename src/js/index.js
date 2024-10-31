/**
 * External dependencies
 */
import { registerPlugin } from '@wordpress/plugins';

const render = () => {};

registerPlugin( 'ringcaptcha-phone-verification-on-checkout-sms-order-notifications', {
	render,
	scope: 'woocommerce-checkout',
} );
