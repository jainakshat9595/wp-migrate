<?php 

    function sync_css() {

        $current_theme = wp_get_theme();
        $current_theme = str_replace(' ', '', $current_theme);
        $current_theme = strtolower($current_theme);

        $existing_settings = get_existing_settings();

        $conn = mysqli_connect($existing_settings['db_host'], $existing_settings['db_username'], $existing_settings['db_password'], $existing_settings['db_name']);
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        
        $query1 = "Select option_value from wp_options where option_name = 'template'";
        $result1 = mysqli_query($conn, $query1);
        
        $current_theme_prod = '';
        if (mysqli_num_rows($result1) > 0) {
            while($row = mysqli_fetch_assoc($result1)) {
                $current_theme_prod = $row["option_value"];
            }
        }

        $current_theme_prod = str_replace(' ', '', $current_theme_prod);
        $current_theme_prod = strtolower($current_theme_prod);

        if($current_theme_prod == $current_theme) {

            $ftp_conn = ftp_connect($existing_settings['db_host'], 21);
            $ftp_conn_login_result = ftp_login($ftp_conn, $existing_settings['ftp_username'], $existing_settings['ftp_password']);
            ftp_pasv($ftp_conn, true);

            echo "ftp_conn_login_result: ".$ftp_conn_login_result."<br>";
       
            $sourceFile = WP_CONTENT_DIR."\\themes\\".$current_theme."\\style.css";
            $destFile = "wp-content/themes/".$current_theme_prod."/style.css";
                
            if (ftp_delete($ftp_conn,$destFile)) {
                echo "deleted successful\n";
            } else {
                echo "could not delete \n";
            }

            if (ftp_put($ftp_conn, $destFile, $sourceFile, FTP_ASCII)) {
                //echo "Successfully uploaded";
                $_SESSION['message'] = "CSS has been successfully synced, awesome!";
                wp_redirect(admin_url()."admin.php?page=migrate-home");
            } else {
                echo "Error uploading";
            }
        
        } else {
            echo "The Themes don't match";
        }	
    }
?>