<?php 

    function sync_aimages() {

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





<?php 

    $host = $_SESSION['POST']['t1'];
            $ip=gethostbyname($host);
            $ftplogin = $_SESSION['POST']['t5'];
            $ftppassword = $_SESSION['POST']['t6']; 
            // print_r($_SESSION['POST']);
            
            $ftp_conn = ftp_connect($ip, 21);
            $ftp_conn_login_result = ftp_login($ftp_conn, $ftplogin, $ftppassword);
            echo "ftp_conn_login_result: ".$ftp_conn_login_result."<br>";
            $sourceDir = WP_CONTENT_DIR.'/uploads/2017/07';
            $destDir = 'wp-content/uploads/2017/07';
            $destnew = 'wp-content/uploads';
            $invalidFiles = array(
                '.',
                '..'
            );
            $fyear=date("Y");
            $fmonth=date("m");
            if (ftp_mkdir($ftp_conn, $destnew))
            {
            echo "Successfully created $destnew";
            }
            else
            {
            echo "Error while creating $destnew";
            }
            
            $destnew1=$destnew."/".$fyear;
            if (ftp_mkdir($ftp_conn, $destnew1))
            {
            echo "Successfully created $destnew1";
            }
            else
            {
            echo "Error while creating $destnew1";
            }
            $destnew2=$destnew1."/".$fmonth;
            if (ftp_mkdir($ftp_conn, $destnew2))
            {
            echo "Successfully created $destnew2";
            }
            else
            {
            echo "Error while creating $destnew2";
            }
            
            ftp_pasv($ftp_conn, true);
            $server_plugin_content = ftp_nlist($ftp_conn, $destDir);
            print_r($server_plugin_content);
            function ftp_putAll($ftp_conn,$sourcepath,$destpath)
                        {
                        //echo $sourcepath."<br />";
                        $d=opendir($sourcepath);
                        while($file = readdir($d))
                        {
                            //echo $file."<br />";
                            if ($file != "." && $file != "..") 
                            {
                                            
                                if(is_dir($sourcepath."/".$file))
                                {
                                    ftp_mkdir($ftp_conn,$destpath."/".$file);
                                    ftp_putAll($ftp_conn,$sourcepath."/".$file,$destpath."/".$file);
                                }
                                else
                                {
                                    ftp_put($ftp_conn,$destpath."/".$file,$sourcepath."/".$file,FTP_BINARY);
                                }
                            }
                        }
                        closedir($d);
                        }		
            $handle = opendir($sourceDir);
            
            while (($file = readdir($handle)) !== false) 
            {
                if (in_array($file, $invalidFiles)) continue;
                echo "file: ".$file."<br>";

                $sourcepath = $sourceDir . "/" . $file;
                //$sourcepath = $sourceDir . DIRECTORY_SEPARATOR . $file;
                //$destpath = $destDir . DIRECTORY_SEPARATOR . $file;
                $destpath = $destDir . "/" . $file;
                //echo "sourcepath: ".$sourcepath."<br>";
                //echo "destpath: ".$destpath."<br>";
                
                if (in_array($file, $server_plugin_content)) 
                {
                    
                    continue;
                }
                else 
                {
                    echo "s"."<br />";
                    $ext = pathinfo($file, PATHINFO_EXTENSION);
                    //echo "<br />".$destpath;
                    //echo "<br />".$sourcepath;
                    if($ext=="")
                    {
                        ftp_mkdir($ftp_conn,$destpath);
                        //echo $file."<br />";
                        echo $destpath."<br />";
                        echo $sourcepath."<br />";
                        
                        
                        ftp_putAll($ftp_conn,$sourcepath,$destpath);
                        
                        
                        
                    }
                    else
                    {
                        if (ftp_put($ftp_conn, $destpath, $sourcepath, FTP_BINARY))
                        {
                        echo "Successfully uploaded $file.";
                        }
                        else
                        {
                        echo "Error uploading $file.";
                        }
                    }
                }
            }

            closedir($handle);
            
            
            

?>