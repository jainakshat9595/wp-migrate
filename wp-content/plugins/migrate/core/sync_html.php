<?php 

    function sync_html() {

        set_time_limit(0);

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

            //echo "ftp_conn_login_result: ".$ftp_conn_login_result."<br>";

            $current_home_url = get_home_path();
            $current_home_url = explode("/",$current_home_url);
            $current_home_url = implode("\\",$current_home_url);

            $sourceDir = $current_home_url."wp-content\\themes\\".$current_theme;
            $destinationDir="wp-content/themes/".$current_theme_prod;
            
            recursiveSync($ftp_conn,$sourceDir,$destinationDir, $existing_settings);	

            $_SESSION['message'] = "HTML has been successfully synced, awesome!";
            wp_redirect(admin_url()."admin.php?page=migrate-home");

        } else {
            echo "The Themes don't match";
        }	

    }

    function recursiveSync($ftp_conn,$sourcepath,$destpath, $existing_settings) {
        $handle = opendir($sourcepath);
        while((($file = readdir($handle)) !== false)) {
            //echo "file: ".$file."<br />";
            if ($file != "." && $file != "..") {

                if(is_dir($sourcepath."\\".$file)) {
                    if(is_dir('ftp://'.$existing_settings['ftp_username'].':'.$existing_settings['ftp_password'].'@'.$existing_settings['db_host'].'/'.$destpath.'/'.$file)) {
                        //echo $destpath.'/'.$file." is a dir exist";
                    } else {
                        ftp_mkdir($ftp_conn, $destpath.'/'.$file);
                        //echo $destpath.'/'.$file." is a dir no exist";
                    }
                    recursiveSync($ftp_conn,$sourcepath."\\".$file,$destpath."/".$file, $existing_settings);
                } else {
                    if(ftp_size($ftp_conn, $destpath."/".$file) >= 0) {
                        if (ftp_delete($ftp_conn,$destpath."/".$file)) {
                            //echo $file." deleted successfully from server. <br />";
                        } else {
                            echo $file." could not be deleted from server. <br />";
                        }
                    }

                    if (ftp_put( $ftp_conn, $destpath.'/'.$file, $sourcepath."\\".$file, FTP_BINARY)) {
                        //echo $file." uploaded successfully to server. <br />";
                    } else {
                        echo $file." could not be uploaded to server. <br />";
                    }
                }
            }
        }
        closedir($handle);
    }

?>
