<?php 

    function sync_plugins() {

        set_time_limit(0);
        ini_set("memory_limit", "999999M");

        $existing_settings = get_existing_settings();

        $ftp_conn = ftp_connect($existing_settings['db_host'], 21);
        $ftp_conn_login_result = ftp_login($ftp_conn, $existing_settings['ftp_username'], $existing_settings['ftp_password']);
        ftp_pasv($ftp_conn, true);

        $sourceDir = WP_CONTENT_DIR . '/plugins';
        $destDir = 'wp-content/plugins';

        $server_plugin_content = ftp_nlist($ftp_conn, $destDir);

        $invalidFiles = array(
            '.',
            '..'
        );

        echo "ftp_conn_login_result: ".$ftp_conn_login_result."<br>";
            
        $handle = opendir($sourceDir);
        while (($file = readdir($handle)) !== false) {

            if (in_array($file, $invalidFiles)) continue;

            $sourcepath = $sourceDir . "/" . $file;
            $destpath = $destDir . "/" . $file;
            
            if (in_array($file, $server_plugin_content)) {
                continue;
            } else {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if($ext == "") {
                    if($file == "migrate") {
                        continue;
                    } else {
                        ftp_mkdir($ftp_conn,$destpath);
                        ftp_putAll($ftp_conn,$sourcepath,$destpath);
                    }
                } else {
                    if (ftp_put($ftp_conn, $destpath, $sourcepath, FTP_BINARY)) {
                        echo "Successfully uploaded $file.";
                    } else {
                        echo "Error uploading $file.";
                    }
                }
            }
        }

        closedir($handle);

        $local_plugin_content = scandir($sourceDir);
        //var_dump($local_plugin_content);
        foreach ($server_plugin_content as $item) {
            if(in_array($item, $local_plugin_content)) {
                continue;
            } else {
                ftp_deleteAll($ftp_conn, 'wp-content/plugins/'.$item, $existing_settings);
                //echo 'wp-content/plugins/'.$item;
                if(count(ftp_nlist($ftp_conn, 'wp-content/plugins/'.$item))==2) {
                    ftp_rmdir($ftp_conn, 'wp-content/plugins/'.$item);
                }
            }
        }

        $_SESSION['message'] = "Plugins has been successfully synced, awesome!";
        wp_redirect(admin_url()."admin.php?page=migrate-home");

    }   

    function ftp_putAll($ftp_conn,$sourcepath,$destpath) {
        $d = opendir($sourcepath);
        while($file = readdir($d)) {
            if ($file != "." && $file != "..") {
                            
                if(is_dir($sourcepath."/".$file)) {
                    ftp_mkdir($ftp_conn,$destpath."/".$file);
                    ftp_putAll($ftp_conn,$sourcepath."/".$file,$destpath."/".$file);
                } else {
                    ftp_put($ftp_conn,$destpath."/".$file,$sourcepath."/".$file,FTP_BINARY);
                }
            }
        }
        closedir($d);
    }

    function ftp_deleteAll($ftp_conn, $destpath, $existing_settings) {
        $items = ftp_nlist($ftp_conn, $destpath);
        foreach($items as $item) {
            if ($item != "." && $item != "..") {
                if(is_file('ftp://'.$existing_settings['ftp_username'].':'.$existing_settings['ftp_password'].'@'.$existing_settings['db_host'].'/'.$destpath.'/'.$item))    {
                    ftp_delete($ftp_conn, $destpath."/".$item);
                } else if((is_dir('ftp://'.$existing_settings['ftp_username'].':'.$existing_settings['ftp_password'].'@'.$existing_settings['db_host'].'/'.$destpath.'/'.$item))&&(count(ftp_nlist($ftp_conn, $destpath."/".$item))==2)) {
                    ftp_rmdir($ftp_conn, $destpath."/".$item);
                } else {
                    ftp_deleteAll($ftp_conn, $destpath."/".$item, $existing_settings);
                }
            }
        }
    }
?>
