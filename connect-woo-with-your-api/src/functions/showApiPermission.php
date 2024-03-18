<?php
 if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_showApiPermission($permission ,$isActive, $i = 0 ){
    ?>
    <label class="input-api-permission">
        <input 
            type="checkbox" 
            class="input-permission-api" 
            name="api[<?=$i?>][permission][<?=$permission?>]" 
            id="api[<?=$i?>][permission][<?=$permission?>]" 
            <?=$isActive?"checked":""?>
        >
        <h3 class="permission-api"><?=$permission?></h3>
    </label>
    <?php
}