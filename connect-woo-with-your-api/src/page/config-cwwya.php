<?php
 if ( ! defined( 'ABSPATH' ) ) exit;
function CWWYA_create_menu() {

	//create new top-level menu
	add_menu_page('Connect Woocommerce with your api Settings', 'Connect Woocommerce with your api', 'administrator', "CWWYA_config", 'CWWYA_settings_page'  );

}
add_action('admin_menu', 'CWWYA_create_menu');


function CWWYA_settings_page() {

	$deleteApi = sanitize_text_field($_POST['delete-api']);

	if( $deleteApi!=null  &&  $deleteApi!='' ){
		CWWYA_deleteApi(intval($deleteApi));
	}

	CWWYA_saveConfigAndApis();

	$defaults = CWWYA_getConfigDefault();

	$configDefault = $defaults["configDefault"];

	$config = CWWYA_get_option("config");
	$apis = CWWYA_get_option("apis");
	$config = CWWYA_joinArrayObject($configDefault,$config);

	$URL = get_site_url() . "/?rest_route=/".CWWYA_RUTE."/";

    ?>

    <div id="page-config-CWWYA" class="wrap page-config-CWWYA" data-n-items-apis="<?php echo count($apis)?>">
		<form method="post">
			<div class="config">
				<h1>
					<strong>Connect Woocommerce with your api</strong>
				</h1>	
				<label class="active-plugin">
        			<h3 class="text">Active</h3>
					<input type="checkbox" <?php echo $config["active"]?"checked":""?> name="config[active]" id="config[active]"/>
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
				<li><?php echo esc_html($URL);?>orders/view</li>
				<li><?php echo esc_html($URL);?>orders/create</li>
				<li><?php echo esc_html($URL);?>orders/update</li>
				<li><?php echo esc_html($URL);?>orders/delete</li>
			</ul>
			<h3>Products</h3>
			<ul>
				<li><?php echo esc_html($URL);?>products/view</li>
				<li><?php echo esc_html($URL);?>products/create</li>
				<li><?php echo esc_html($URL);?>products/update</li>
				<li><?php echo esc_html($URL);?>products/delete</li>
			</ul>
			<h3>Users</h3>
			<ul>
				<li><?php echo esc_html($URL);?>users/view</li>
				<li><?php echo esc_html($URL);?>users/create</li>
				<li><?php echo esc_html($URL);?>users/update</li>
				<li><?php echo esc_html($URL);?>users/delete</li>
			</ul>
		</div>
    </div>
    <?php 

}