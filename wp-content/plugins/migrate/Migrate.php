<?php
/*
Plugin Name: Migrate
*/

session_start();

require_once("migrate_dashboard.php");
require_once("migrate_settings.php");
require_once("core/process_settings.php");
require_once("core/start_sync.php");

// creating a table user_data on activation of a plugin
register_activation_hook(__FILE__, 'createSettingsTable');
function createSettingsTable()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'migrate_settings';
	$query = "CREATE TABLE IF NOT EXISTS ".$table_name." 
				(
					uid INT  AUTO_INCREMENT PRIMARY KEY, 
					db_host VARCHAR(30) NOT NULL,
					db_username VARCHAR(30) NOT NULL,
					db_password VARCHAR(50) NOT NULL,
					db_name VARCHAR(50) NOT NULL,
					ftp_username VARCHAR(50) NOT NULL,
					ftp_password VARCHAR(50) NOT NULL
				)";

	require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

	dbDelta($query);
}

// creating log files containing insert, update and delete queries
add_filter('query',
function ($query)
{
	if (FALSE !== stripos($query, 'UPDATE ') || FALSE !== stripos($query, 'INSERT ') || FALSE !== stripos($query, 'DELETE ')) {
		$log_file_path = WP_CONTENT_DIR . '/sql.log';
		$log_file = fopen($log_file_path, 'a');
		if ($log_file && is_writeable($log_file_path)) file_put_contents($log_file_path, $query . "#@#" . PHP_EOL, FILE_APPEND | LOCK_EX);
	}

	return $query;
}, PHP_INT_MAX);


add_action( 'admin_init', 'pluginInit' );
function pluginInit() {
    wp_register_style( 'migrateStyle', plugins_url( 'style.css', __FILE__ ) );
}


// creating an admin menu
// adding menus and submenus in dashboard
add_action('admin_menu', 'addToMenu');
function addToMenu()
{
	add_menu_page("Example Options", "Migrate", 4, "migrate-home", "migrateDashboard", "dashicons-tickets");
	$page = add_submenu_page("migrate-home", "Option 1", "Settings", 4, "migrate-settings", "migrateSettings");
	add_action( "admin_print_styles-{$page}", 'enquePluginStyle' );
}
function enquePluginStyle() {
    wp_enqueue_style( 'migrateStyle' );
}


// Add styles.css file
add_action( 'wp_enqueue_style', 'loadPluginStyles' );
function loadPluginStyles() {
    $plugin_url = plugin_dir_url( __FILE__ );
	echo "aks: ".$plugin_url;
    wp_enqueue_style( 'style', $plugin_url . '/style.css' );
}

?>
