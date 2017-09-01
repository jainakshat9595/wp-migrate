<?php 

    function sync_posts() {

        $existing_settings = get_existing_settings();

        $conn = mysqli_connect($existing_settings['db_host'], $existing_settings['db_username'], $existing_settings['db_password'], $existing_settings['db_name']);
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        $logged_queries = file_get_contents(WP_CONTENT_DIR . '/sql.log');
        $logged_query_array = explode("#@#", $logged_queries);
        foreach($logged_query_array as $key => $val) {
            mysqli_multi_query($conn, $val);
        }

        file_put_contents(WP_CONTENT_DIR . '/sql.log', "");

        echo "DONE!";

        mysqli_close($conn);

    }
?>




<?php 

 
            

           
            
     
            

?>