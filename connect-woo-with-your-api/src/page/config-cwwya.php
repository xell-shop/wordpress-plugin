<?php
 if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_create_menu() {

	//create new top-level menu
	add_menu_page('Connect Woocommerce with your api Settings', 'Connect Woocommerce with your api', 'administrator', "CWWYA_config", 'CWWYA_settings_page'  );

	//call register settings function
	add_action( 'admin_init', 'register_CWWYA_settings' );
}
add_action('admin_menu', 'CWWYA_create_menu');


function register_CWWYA_settings() {
	//register our settings
	register_setting( 'CWWYA-settings-group', 'new_option_name' );
	register_setting( 'CWWYA-settings-group', 'some_other_option' );
	register_setting( 'CWWYA-settings-group', 'option_etc' );
}

function CWWYA_settings_page() {

	if($_POST['delete-api']!=null){
		CWWYA_deleteApi(intval($_POST['delete-api']));
	}

	CWWYA_saveConfigAndApis($_POST);

	$defaults = CWWYA_getConfigDefault();

	$configDefault = $defaults["configDefault"];

	$config = CWWYA_get_option("config");
	$apis = CWWYA_get_option("apis");
	$config = CWWYA_joinArrayObject($configDefault,$config);
    ?>

    <div id="page-config-CWWYA" class="wrap page-config-CWWYA" data-n-items-apis="<?=count($apis)?>">
		<form method="post">
			<div class="config">
				<h1>
					<strong>Connect Woocommerce with your api</strong>
				</h1>	
				<label class="active-plugin">
        			<h3 class="text">Active</h3>
					<input type="checkbox" <?=$config["active"]?"checked":""?> name="config[active]" id="config[active]"/>
				</label>
			</div>
			<div class="separadorSecctions"></div>
			<div class="">
				<h1>
					<strong>Your Apis</strong>
				</h1>
				<div id="contentApis" class="apis">
					<?php
					for ($i=0; $i < count($apis); $i++) { 
						CWWYA_showApi($apis[$i],$i);
					}
					?>
				</div>
				<div class="contentBtns">
					<button id="addNewApi" class="button">Add new Api</button>
					<input type="submit" class="button action" value="Save" name="save" id="save"/>
				</div>
			</div>
		</form>
		<div class="separadorSecctions"></div>
		<div class="contentUrls">
			<h1>
				<strong>Url for Connect</strong>
			</h1>
			<h3>Orders</h3>
			<ul>
				<li><?=CWWYA_URL?>src/routes/orders/view.php</li>
				<li><?=CWWYA_URL?>src/routes/orders/create.php</li>
				<li><?=CWWYA_URL?>src/routes/orders/update.php</li>
				<li><?=CWWYA_URL?>src/routes/orders/delete.php</li>
			</ul>
			<h3>Products</h3>
			<ul>
				<li><?=CWWYA_URL?>src/routes/products/view.php</li>
				<li><?=CWWYA_URL?>src/routes/products/create.php</li>
				<li><?=CWWYA_URL?>src/routes/products/update.php</li>
				<li><?=CWWYA_URL?>src/routes/products/delete.php</li>
			</ul>
			<h3>Users</h3>
			<ul>
				<li><?=CWWYA_URL?>src/routes/users/view.php</li>
				<li><?=CWWYA_URL?>src/routes/users/create.php</li>
				<li><?=CWWYA_URL?>src/routes/users/update.php</li>
				<li><?=CWWYA_URL?>src/routes/users/delete.php</li>
			</ul>
		</div>
    </div>
    <?php 

}