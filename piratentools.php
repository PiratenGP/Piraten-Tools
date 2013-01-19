<?php
/*
Plugin Name: Piraten-Tools
Plugin URI: https://github.com/stoppegp/Piraten-Tools
Description: Piraten-Tools
Version: 0.1
Author: @stoppegp
Author URI: https://twitter.com/stoppegp
License: GPL2
*/
?>
<?php
/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>

<?php
add_action( 'admin_menu', 'piratentools_main_menu' );

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