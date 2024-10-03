<?php

if ( ! defined( 'ABSPATH' ) ) exit;

wp_enqueue_script(
    'connect-woocommerce-with-your-api-page-config-js', 
    esc_attr(CWWYA_URL  ."src/js/page-config.js?v=". CWWYA_get_version())
);