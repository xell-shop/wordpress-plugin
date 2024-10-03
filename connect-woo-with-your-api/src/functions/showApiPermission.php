<?php
 if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_showApiPermission($permission ,$isActive, $i = 0 ){
    ?>
    <label class="input-api-permission">
        <input 
            type="checkbox" 
            class="input-permission-api" 
            name="api[<?php echo esc_html($i)?>][permission][<?php echo esc_html($permission)?>]" 
            id="api[<?php echo esc_html($i)?>][permission][<?php echo esc_html($permission)?>]" 
            <?php echo $isActive?"checked":""?>
        >
        <h3 class="permission-api"><?php echo esc_html($permission)?></h3>
    </label>
    <?php
}