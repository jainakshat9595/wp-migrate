<?php

    add_action('admin_action_process_plugin_settings', 'process_plugin_settings' );

    function process_plugin_settings() {
        
        // Called when save is clicked from settings page
        if(isset($_POST) && isset($_POST['plugin_settings_submit'])) {

            $db_host = $_POST['db_host'];
            $db_name = $_POST['db_name'];
            $db_username = $_POST['db_username'];
            $db_password = $_POST['db_password'];
            $ftp_username = $_POST['ftp_username'];
            $ftp_password = $_POST['ftp_password'];

            if (
                !isset($db_host) || $db_host == "" || 
                !isset($db_name) || $db_name == "" || 
                !isset($db_username) || $db_username == "" || 
                !isset($db_password) || $db_password == "" || 
                !isset($ftp_username) || $ftp_username == "" || 
                !isset($ftp_password) || $ftp_password == ""
            ) {
                die("Invalid Argumanets");
            }

            $db_host_ip = gethostbyname($db_host);
            
            // Required for current Database Credentials 
            require (ABSPATH . '/wp-config.php');
            
            $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            if (mysqli_connect_errno()) {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
            }

            global $wpdb;
            $table_name = $wpdb->prefix . 'migrate_settings';
            
            $query1 = "Select * from ".$table_name;
            if ($result1 = mysqli_query($conn, $query1)) {
                $rowcount = mysqli_num_rows($result1);
                $row = mysqli_fetch_assoc($result1);
                if ($rowcount < 1) {
                    if ($stmt = mysqli_prepare($conn, "INSERT INTO ".$table_name."(db_host,db_username,db_password,db_name, ftp_username,ftp_password) VALUES (?,?,?,?,?,?)")) {
                        mysqli_stmt_bind_param($stmt, "ssssss", $db_host_ip, $db_username, $db_password, $db_name, $ftp_username, $ftp_password);
                        mysqli_stmt_execute($stmt);
                    }
                }
                else if ($rowcount >= 1) {
                    if ($stmt1 = mysqli_prepare($conn, "UPDATE ".$table_name." SET db_host=?,db_username=?,db_password=?,db_name=?,ftp_username=?,ftp_password=? WHERE uid=?")) {
                        mysqli_stmt_bind_param($stmt1, "ssssssi", $db_host_ip, $db_username, $db_password, $db_name, $ftp_username, $ftp_password, $row['uid']);
                        mysqli_stmt_execute($stmt1);
                    }
                }
            } else {
                die("Error executing Query: ".$query1);
            }

            $query2 = "Select * from ".$table_name;
            $result2 = mysqli_query($conn, $query2);
            if (mysqli_num_rows($result2) > 0) {
                wp_redirect(admin_url()."admin.php?page=migrate-settings");
            }
            else {
                echo "Nothing Found in the Database";
            }
            
        } else {
            die("Server Error");
        }

    }
?>