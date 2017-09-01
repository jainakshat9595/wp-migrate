<?php

    function migrateSettings() {

        $existing_settings = get_existing_settings();

?>
        <div class="settings-left">
            <!-- creating form which takes database details of any other system -->
            <h1><u>Migrate Plugin Settings</u></h1>
            <p> Enter the details of the production instance. </p>
            <br />
            <form id="settings_form" method="post" action="<?php echo admin_url( 'admin.php' ); ?>">
                <input type="hidden" name="action" value="process_plugin_settings"><br />
                DB HOST:<br />
                <input type="text" name="db_host" value="<?php echo $existing_settings['db_host']; ?>"><br />
                DB NAME:<br />
                <input type="text" name="db_name" value="<?php echo $existing_settings['db_name']; ?>"><br />
                DB USERNAME:<br />
                <input type="text" name="db_username" value="<?php echo $existing_settings['db_username']; ?>"><br />
                DB PASSWORD:<br />
                <input type="text" name="db_password" value="<?php echo $existing_settings['db_password']; ?>"><br />
                FTP USERNAME:<br />
                <input type="text" name="ftp_username" value="<?php echo $existing_settings['ftp_username']; ?>"><br />
                FTP PASSWORD:<br />
                <input type="text" name="ftp_password" value="<?php echo $existing_settings['ftp_password']; ?>"><br />
                <br />
                <input type="submit" name="plugin_settings_submit" id="plugin_settings_submit" value="<?php if(!$existing_settings) echo "Save"; else echo "Update"; ?>">
            </form>
        </div>

<?php

    }

    function get_existing_settings() {

        $existing_settings = array();

        // Required for current Database Credentials 
        require (ABSPATH . '/wp-config.php');
        
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'migrate_settings';

        $query = "Select * from ".$table_name;
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_assoc($result)) {
                return $row;
            }
        }
        else {
            return false;
        }
    }

?>