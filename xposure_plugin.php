<?php
/*
Plugin Name: Xposure Creative Brand Marketing's Plugin
Plugin URI: http://www.creativebrandmarketing.co.uk/
Description: This plugin is your link into the world of xposure
Author: Thomas O'Brien
Author URI: http://www.creativebrandmarketing.co.uk/
License: GPL3
Version: 1.2

This is a plugin to display the latest blog posts from xposure creative brand marketing Copyright (C) 2012  Thomas O'Brien

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
?>
<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//xposure main page content
function xposure_plugin_settings() {
?>
<style type="text/css">
.blogbook #bookcover {
	transition: transform 2s;
	transition-timing-function: linear;
	-moz-transition: -moz-transform 2s; /* Firefox 4 */
	-moz-transition-timing-function: linear;
	-webkit-transition: -webkit-transform 2s; /* Safari and Chrome */
	-webkit-transition-timing-function: linear;
	-o-transition: -o-transform 2s; /* Opera */
	-o-transition-timing-function: linear;
	transform-origin: 0 0;
	-webkit-transform-origin: 0 0;
	-moz-transform-origin: 0 0;
	-o-transform-origin: 0 0;
	text-align: left;
	z-index: 2;
	width: 140px;
	height: 183px;
	position: absolute;
}
.blogbook:hover > #bookcover{
	transform: rotateY(180deg);
	-webkit-transform: rotateY(180deg); /* Safari and Chrome */
	-moz-transform: rotateY(180deg); /* Firefox */
	-o-transform: rotateY(180deg); /* Firefox */
	transform-origin: 0 0;
	-webkit-transform-origin: 0 0;
	-moz-transform-origin: 0 0;
	-o-transform-origin: 0 0;
	float: left;
}
#linktext {
	margin: 0;
}
#linktext a:hover {
	color: #AF292E;
	text-decoration: underline;
}
</style>
	<div>
		<?php $url = plugins_url(); ?>
		<div id="xposure_logo" style="text-align: center;">
			<a href="http://www.creativebrandmarketing.co.uk/"><img src="<?php echo $url; ?>/xposure-creative-brand-marketings-plugin/images/Logo.png" /></a>
		</div>
		<div style="text-align: center;">
			<img style="max-width: 100%;" src="<?php echo $url; ?>/xposure-creative-brand-marketings-plugin/images/top-band.png" />
		</div>
<?php
$phpv = phpversion();
if ( $phpv > 5 ) {
?>
		<ul style="max-width: 500px; margin: 0 auto;">
		<?php
		if (!ini_get('allow_url_fopen')) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://www.creativebrandmarketing.co.uk/external-rss-feed");
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$xml = curl_exec($ch);
			curl_close($ch);
			$xposurerss = simplexml_load_string($xml);
		} else {
			$xposurerss = simplexml_load_file('http://www.creativebrandmarketing.co.uk/external-rss-feed');
		}
		$i=1;
		foreach($xposurerss as $key => $value) {
		//channel
			foreach($value->item as $element) {
				echo '<li class="blogbook" style="float: left; width: 140px; height: 183px; position: reltaive; background: #ffffff; position: relative; z-index: 2; box-shadow: 1px 1px 3px #000000; margin: 10px;">';
				echo '<span id="bookcover">';
				echo '<a href="' . $element->link . '">';
				echo '<img src="'. $element->thumbnaillink .'">';
				echo '</a>';
				$i++;
				echo '</span>';
				echo '<p id="linktext" style="z-index: 0; text-align: center; width: 130px; height: 163px; top: 0; left: 0; position: absolute; font-family: lora, Georgia, serif; padding: 10px 5px;"><a href="' . $element->link . '">' . $element->title . '</a><br /><br /><span style="color: #AF292E;">By <a style="color: #AF292E;" href="'. $element->authorlink .'">' . $element->author . '</a></span></p>';
				echo '</li>';
				if($i == 7) { break; }
            }
        }
	    ?>
	    <div style="clear: both;"></div>
	    </ul>
<?php
} else {
	echo "<div style='text-align: center;'>The php version on you server is not high enough to use this plugin.</div>";
}
?>
	</div>
<?php
}
// Create the function use in the action hook

function example_add_dashboard_widgets() {
	wp_add_dashboard_widget('xposure_plugin_settings', 'Xposure Blog Feed', 'xposure_plugin_settings');
	// Global the $wp_meta_boxes variable (this will allow us to alter the array)
global $wp_meta_boxes;

// Then we make a backup of your widget
$my_widget = $wp_meta_boxes['dashboard']['normal']['core']['xposure_plugin_settings'];

// We then unset that part of the array
unset($wp_meta_boxes['dashboard']['normal']['core']['xposure_plugin_settings']);

// Now we just add your widget back in
$wp_meta_boxes['dashboard']['side']['core']['xposure_plugin_settings'] = $my_widget;

	// Get the regular dashboard widgets array
	// (which has our new widget already but at the end)

	$normal_dashboard = $wp_meta_boxes['dashboard']['side']['core'];
	
	// Backup and delete our new dashbaord widget from the end of the array

	$example_widget_backup = array('xposure_plugin_settings' => $normal_dashboard['xposure_plugin_settings']);
	unset($normal_dashboard['xposure_plugin_settings']);

	// Merge the two arrays together so our widget is at the beginning

	$sorted_dashboard = array_merge($example_widget_backup, $normal_dashboard);

	// Save the sorted array back into the original metaboxes 

	$wp_meta_boxes['dashboard']['side']['core'] = $sorted_dashboard;
}

// Hook into the 'wp_dashboard_setup' action to register our other functions

add_action('wp_dashboard_setup', 'example_add_dashboard_widgets' );
?>