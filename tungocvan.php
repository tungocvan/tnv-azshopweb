<?php
/*
Plugin Name: Tu Ngoc Van
Plugin URI: https://tungocvan.com
Description: Simple Tu Ngoc Van PlugIn
Author: Từ Ngọc Vân
Version: 1.1.0
Author URI: https://tungocvan.com
Text Domain: tungocvan
*/

if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define('TUNGOCVAN_VERSION', '1.1.0');
define('TUNGOCVAN_MINIMUM_WP_VERSION', '1.0.0');
define('TUNGOCVAN_PLUGIN_URL', plugin_dir_url(__FILE__));
define('TUNGOCVAN_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TUNGOCVAN_VIEWS_DIR', TUNGOCVAN_PLUGIN_DIR .'views');
define('TUNGOCVAN_INCLUDES_DIR', TUNGOCVAN_PLUGIN_DIR .'includes');
define('TUNGOCVAN_JS_URL', TUNGOCVAN_PLUGIN_URL .'scripts/js');
define('TUNGOCVAN_CSS_URL', TUNGOCVAN_PLUGIN_URL .'scripts/css');

define( 'TNV_URI', get_template_directory_uri());
define( 'TNV_URI_PUBLIC', TNV_URI.'/public/');

require_once('lib/my-function.php');
// require_once('lib/my-action-json.php');

if(is_admin()){


}else{
 	
//  	require_once('json/tnv-json.php');
//     new JSON ();
 	
}