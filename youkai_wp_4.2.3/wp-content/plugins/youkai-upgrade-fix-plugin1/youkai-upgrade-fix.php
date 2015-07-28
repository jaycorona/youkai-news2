<?php
/*
Plugin Name: Youkai Upgrade Fix
Description: A wordpress plug-in which will add fix to broken features
Author: TimeRiverDesign, Inc.
Version: 1.0
Author URI: http://timeriver.biz
*/
 
	function inject_admin_footer(){
		echo "
			<style type='text/css'>
				.mce-ico,.ab-icon {
					font-family: dashicons !important;
				}
			</style>
		";
	}
	add_action("admin_footer","inject_admin_footer");
 
 
 
