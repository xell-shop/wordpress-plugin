<?php

if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_createTags($tags)
{
    return CWWYA_addTerm($tags,"product_tag");
}