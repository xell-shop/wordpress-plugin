<?php
 if ( ! defined( 'ABSPATH' ) ) exit;
if(CWWYA_LOG){
    function add_CWWYA_LOG_option_page($admin_bar)
    {
        global $pagenow;
        $admin_bar->add_menu(
            array(
                'id' => 'CWWYA_LOG',
                'title' => 'CWWYA_LOG',
                'href' => get_site_url().'/wp-admin/options-general.php?page=CWWYA_LOG'
            )
        );
    }

    function CWWYA_LOG_option_page()
    {
        add_options_page(
            'Log CWWYA',
            'Log CWWYA',
            'manage_options',
            'CWWYA_LOG',
            'CWWYA_LOG_page'
        );
    }

    function CWWYA_LOG_page()
    {
        $log = CWWYA_get_option("log");
        $log = array_reverse($log);
        ?>
        <script>
            const log = <?=json_encode($log)?>;
        </script>
        <h1>
            Log de CWWYA
        </h1>
        <pre><?php var_dump($log);?></pre>
        <?php
    }
    add_action('admin_bar_menu', 'add_CWWYA_LOG_option_page', 100);

    add_action('admin_menu', 'CWWYA_LOG_option_page');

}

function addCWWYA_LOG($newLog)
{
    if(!CWWYA_LOG){
        return;
    }
    CWWYA_put_option("log",$newLog);
}