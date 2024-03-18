<?php

if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_createCategories($categories)
{
    return CWWYA_addTerm($categories,"product_cat");
}