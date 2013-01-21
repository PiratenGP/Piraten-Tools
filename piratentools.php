<?php
/*
Plugin Name: Piraten-Tools
Plugin URI: https://github.com/stoppegp/Piraten-Tools
Description: Piraten-Tools
Version: 1.0
Author: @stoppegp
Author URI: https://github.com/stoppegp/Piraten-Tools
License: CC-BY-SA 3.0
*/
?>

<?php
add_action( 'admin_menu', 'piratentools_main_menu' );
wp_enqueue_style( "piratentools", plugin_dir_url(__FILE__)."style.css" );
function piratentools_main_menu() {
	//add_options_page( 'My Plugin Options', 'My Plugin', 'manage_options', 'my-unique-identifier', 'my_plugin_options' );
    add_menu_page( "Piraten-Tools", "Piraten-Tools", 0, "piratentools", "piratentools_main_options" );
	add_submenu_page( "piratentools", "Next Piratentreff", "Next Piratentreff", "manage_options", "pt_nextpiratentreff", array("PT_nextpiratentreff", "adminmenu") );
	add_submenu_page( "piratentools", "Wiki Import", "Wiki Import", "manage_options", "pt_wikiimport", array("PT_wikiimport", "adminmenu") );
}

function piratentools_main_options() {
	echo '<div class="wrap">';
	echo '<h2>Piraten-Tools</h2>';
	echo '</div>';
}
?>

<?php
require('nextpiratentreff/nextpiratentreff.php');
require('wikiimport/wikiimport.php');
?>