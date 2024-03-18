<?php
 if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_showApi($api , $i =0){
    ?>
        <div class="api <?=$api["active"]?"active":""?> ">
            <label class="input-api">
                <h3 class="name-api">Name</h3>
                <input 
                    type="text" 
                    class="input-name-api" 
                    name="api[<?=$i?>][name]" 
                    id="api[<?=$i?>][name]"
                    value="<?=$api["name"]?>"
                >
            </label>
            <label class="input-api">
                <h3 class="url-api">Url</h3>
                <input 
                    type="text" 
                    class="input-url-api" 
                    name="api[<?=$i?>][url]" 
                    id="api[<?=$i?>][url]"
                    value="<?=$api["url"]?>"
                >
            </label>
            <label class="input-api">
                <h3 class="token-api">Token</h3>
                <input 
                    type="text" 
                    class="input-token-api" 
                    name="api[<?=$i?>][token]" 
                    id="api[<?=$i?>][token]"
                    value="<?=$api["token"]?>"
                >
            </label>
            <div class="permissions">
                <?php 
                    $permissionsDefault = array_keys(CWWYA_getConfigDefault()["permissionsDefault"]);
                    for ($j=0; $j < count($permissionsDefault); $j++) { 
                        $permission = $permissionsDefault[$j];
                        $isActive = $api["permission"][$permission] || false;
                        CWWYA_showApiPermission($permission ,$isActive, $i);
                    }
                ?>
            </div>
            <div class="contentDelete">
                <input type="submit" class="delete-api-submit" value="<?=$i?>" name="delete-api" id="delete-api"/>
                <button class="button delete delete-api">Delete</button>
            </div>
        </div>
    <?php
}