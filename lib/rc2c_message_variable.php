<?php
/******************************
* SMS Variables
******************************/

if ( ! defined( 'ABSPATH' ) ) exit;

$replacements = array(
    '{name}'         => ucfirst( sanitize_text_field( $order->get_billing_first_name() ) ),
    '{shop_name}'    => get_bloginfo('name'),
    '{order_id}'     => $order->get_order_number(),
    '{order_amount}' => $order->get_total()
);
