<?php

if ( ! defined( 'ABSPATH' ) ) exit;

wp_enqueue_style(
    'connect-woocommerce-with-your-api-page-config-styles', 
    esc_attr(CWWYA_URL  ."src/css/page-config.css?v=". CWWYA_get_version())
);