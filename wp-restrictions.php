<?php
/*
Plugin Name: WordPress Restrictions
Plugin URI: http://sonicedges.com/plugins/wordpress-restrictions/
Description: With WordPress Restrictions, you can specify when and what content may be edited/deleted by Editors and/or Authors.
Version: 0.2
Author: Brandon Smith
Author URI: http://sonicedges.com/
*/

define('WP_REST_VERSION', '0.2'); // Define WP Restrictions Version
define('WP_REST_CURR_DAY', date("j")); // Define Current Day of Month
define('WP_REST_CURR_MONTH', date("n")); // Define Current Month (Numeric)
define('WP_REST_CURR_YEAR', date("Y")); // Define Current Year (YYYY)
define('WP_REST_URL', plugin_dir_url(__FILE__)); // Define WP Plugin URL
define('WP_REST_PATH', plugin_dir_path(__FILE__)); // Define WP Plugin Path

require WP_REST_PATH.'inc/define.class.php'; // Load Define Class
require WP_REST_PATH.'inc/excluded.class.php'; // Load Excluded Class
require WP_REST_PATH.'inc/restrictions.class.php'; // Load Restrictions Class
require WP_REST_PATH.'inc/admin.class.php'; // Load Restrictions Admin

add_action('admin_init', 'wp_restrictions::load'); // Load Admin Actions
add_action('admin_menu', 'wp_rest_admin::menu'); // Load Restrictions Menu
add_filter('map_meta_cap', 'wp_restrictions::mmc', 10, 4); // Filter MMC

?>
