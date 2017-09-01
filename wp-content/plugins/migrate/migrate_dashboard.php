<?php

    function migrateDashboard() {

?>

        <div class="">
            <h1><u>Welcome To Migrate Plugin</u></h1>
            <p> Select the items you want to sync. </p>
<?php            
            if(isset($_SESSION)&&($_SESSION['message']!="")) {
?>                
                <br />
                <div class="updated notice">
                    <p><?php echo $_SESSION['message']; ?></p>
                </div>
<?php                
                $_SESSION['message'] = "";
            }
?>            
            <br />
            <form id="sync_form" method="post" action="<?php echo admin_url( 'admin.php' ); ?>">
                <input type="hidden" name="action" value="process_plugin_sync">
                <br />
                <input type="checkbox" name="sync_option" value="css"> CSS
                <br />
                <input type="checkbox" name="sync_option" value="html"> HTML
                <br />
                <input type="checkbox" name="sync_option" value="plugins"> PLUGINS
                <br />
                <input type="checkbox" name="sync_option" value="images"> IMAGES
                <br />
                <input type="checkbox" name="sync_option" value="posts"> POSTS
                <br /><br />
                <input type="submit" value="Start Sync" name="plugin_sync_submit" id="plugin_sync_submit">
            </form>
        </div>
        
       

<?php

    }

?>
