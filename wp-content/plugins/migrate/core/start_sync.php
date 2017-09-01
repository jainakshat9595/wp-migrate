<?php

    require_once("sync_css.php");
    require_once("sync_html.php");
    require_once("sync_plugins.php");
    //require_once("sync_images.php");
    require_once("sync_posts.php");

    add_action('admin_action_process_plugin_sync', 'process_plugin_sync' );

    function process_plugin_sync() {

        if(isset($_POST) && isset($_POST['plugin_sync_submit'])) {

            $sync_option = $_POST['sync_option'];
            
            if (
                !isset($sync_option) || $sync_option == ""
            ) {
                die("Invalid Argumanets");
            }

            switch($_POST['sync_option']) {
                case 'css':
                    sync_css();
                    break;
                case 'html':
                    sync_html();
                    break;
                case 'plugins':
                    sync_plugins();
                    break;
                case 'images':
                    sync_images();
                    break;
                case 'posts':
                    sync_posts();
                    break;
            }
        }
    }
?>