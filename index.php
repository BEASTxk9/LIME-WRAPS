<?php
/**
 * LIME WRAPS
 *
 * @package   LIME WRAPS
 * @author    Shane Stevens.
 * @copyright Shane Stevens | @2023
 *
 * @wordpress-plugin 
 * Plugin Name: LIME WRAPS
 * Description: This plugin was created specifically to fit the needs of LIME WRAPS
 * Version: 1.0
 * Author: Shane Stevens.
 * License: Free
 */

 // _________________________________________
// IMPORT ALL FILES HERE !IMPORTANT HAS TO BE ONTOP OF THE PAGE BEFORE ANY OTHER CODE IS ADDED
// eg.  require_once plugin_dir_path(__FILE__) . './file.php';

// 1create
require_once plugin_dir_path(__FILE__) . './inclues/1create/Admin.php';

// 2read
require_once plugin_dir_path(__FILE__) . './inclues/2read/Admin.php';
require_once plugin_dir_path(__FILE__) . './inclues/2read/display.php';
require_once plugin_dir_path(__FILE__) . './inclues/2read/single-view.php';

// 3update
require_once plugin_dir_path(__FILE__) . './inclues/3update/Admin.php';

// 4delete
require_once plugin_dir_path(__FILE__) . './inclues/4delete/delete.php';


// _________________________________________
// CREATE DATABASE TABLES ON ACTIVATING PLUGIN
function create_table_on_activate()
{
    // connect to WordPress database
    global $wpdb;

    // set table names
	$admin = $wpdb->prefix . 'admin';

	   // mysql create tables query
	   $sql = "CREATE TABLE $admin (
		id INT(10) PRIMARY KEY AUTO_INCREMENT,
		client VARCHAR(255) NOT NULL,
		client_details VARCHAR(255) NOT NULL,
		client_vehicle VARCHAR(255) NOT NULL,
		category VARCHAR(255) NOT NULL,
		post_date DATETIME,
		job_status VARCHAR(255) NOT NULL,
		complete_date DATETIME,
		img_url VARCHAR(255) NOT NULL,
		price INT(10) NOT NULL,
		user_id INT(10) NOT NULL,
        user_name VARCHAR(255) NOT NULL
	) $charset_collate;";

    $charset_collate = $wpdb->get_charset_collate();



    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $result = dbDelta($sql);
    if (is_wp_error($result)) {
        echo 'There was an error creating the tables';
        return;
    }
}
register_activation_hook(__FILE__, 'create_table_on_activate');




// _________________________________________
// (!IMPORTANT DO NOT TOUCH)  CREATE PAGE FUNCTION  (!IMPORTANT DO NOT TOUCH)
function create_page($title_of_the_page, $content, $parent_id = NULL)
{
	$objPage = get_page_by_title($title_of_the_page, 'OBJECT', 'page');
	if (!empty($objPage)) {
		echo "Page already exists:" . $title_of_the_page . "<br/>";
		return $objPage->ID;
	}
	$page_id = wp_insert_post(
		array(
			'comment_status' => 'close',
			'ping_status' => 'close',
			'post_author' => 1,
			'post_title' => ucwords($title_of_the_page),
			'post_name' => strtolower(str_replace(' ', '-', trim($title_of_the_page))),
			'post_status' => 'publish',
			'post_content' => $content,
			'post_type' => 'page',
			'post_parent' => $parent_id //'id_of_the_parent_page_if_it_available'
		)
	);
	echo "Created page_id=" . $page_id . " for page '" . $title_of_the_page . "'<br/>";
	return $page_id;
}




// _________________________________________
// ACTIVATE PLUGIN
function on_activating_your_plugin()
{
    // _________________________________________
	//  CREATE WP PAGES AUTOMATICALLY ANLONG WITH SHORT CODE TO DISPLAY THE CONTENT
	// eg.  create_page('page-name', '[short-code]');
    // _________________________________________


	// 1create
	create_page('admin-create', '[admin-create]');

	// 2read
	create_page('admin', '[admin]');
	create_page('display', '[display]');
	create_page('admin_single', '[admin_single]');

	// 3update
	create_page('admin-update', '[admin-update]');

}
register_activation_hook(__FILE__, 'on_activating_your_plugin');





// _________________________________________
// DEACTIVATE PLUGIN
function on_deactivating_your_plugin()
{
    // _________________________________________
	//  DELETE WP PAGES AUTOMATICALLY ANLONG WITH SHORT CODE TO DISPLAY THE CONTENT
	// eg. 	
    // $page_name = get_page_by_path('page_name');
	// wp_delete_post($page_name->ID, true);
    // _________________________________________


	// 1create
	$admin_create = get_page_by_path('admin-create');
	wp_delete_post($admin_create->ID, true);

	// 2read
	$admin = get_page_by_path('admin');
	wp_delete_post($admin->ID, true);
	$display = get_page_by_path('display');
	wp_delete_post($display->ID, true);
	$admin_single = get_page_by_path('admin_single');
	wp_delete_post($admin_single->ID, true);

	// 3update
	$admin_update = get_page_by_path('admin-update');
	wp_delete_post($admin_update->ID, true);

}
register_deactivation_hook(__FILE__, 'on_deactivating_your_plugin');


?>