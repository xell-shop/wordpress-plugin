<?php

if ( ! defined( 'ABSPATH' ) ) exit;

wp_enqueue_style(
    'xell-page-config-styles', 
    esc_attr(XELL_URL  ."src/css/page-config.css?v=". XELL_get_version())
);