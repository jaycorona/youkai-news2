<?php
/*
Plugin Name: WP Admin UI Customize
Description: An excellent plugin to customize the management screens.
Plugin URI: http://wpadminuicustomize.com/?utm_source=use_plugin&utm_medium=list&utm_content=wauc&utm_campaign=1_3_9_2
Version: 1.3.9.2
Author: gqevu6bsiz
Author URI: http://gqevu6bsiz.chicappa.jp/?utm_source=use_plugin&utm_medium=list&utm_content=wauc&utm_campaign=1_3_9_2
Text Domain: wauc
Domain Path: /languages
*/

/*  Copyright 2012 gqevu6bsiz (email : gqevu6bsiz@gmail.com)

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



class WP_Admin_UI_Customize
{

	var $Ver,
		$Name,
		$Dir,
		$Url,
		$Site,
		$AuthorUrl,
		$ltd,
		$Record,
		$PageSlug,
		$PluginSlug,
		$Nonces,
		$Schema,
		$UPFN,
		$DonateKey,
		$Menu,
		$SubMenu,
		$Admin_bar,
		$MsgQ,
		$Msg;


	function __construct() {
		$this->Ver = '1.3.9.2';
		$this->Name = 'WP Admin UI Customize';
		$this->Dir = plugin_dir_path( __FILE__ );
		$this->Url = plugin_dir_url( __FILE__ );
		$this->Site = 'http://wpadminuicustomize.com/';
		$this->AuthorUrl = 'http://gqevu6bsiz.chicappa.jp/';
		$this->ltd = 'wauc';
		$this->ltd_p = $this->ltd . '_plugin';
		$this->Record = array(
			"user_role" => $this->ltd . '_user_role_setting',
			"site" => $this->ltd . '_site_setting',
			"admin_general" => $this->ltd . '_admin_general_setting',
			"dashboard" => $this->ltd . '_dashboard_setting',
			"admin_bar_menu" => $this->ltd . '_admin_bar_menu_setting',
			"sidemenu" => $this->ltd . '_sidemenu_setting',
			"removemetabox" => $this->ltd . '_removemetabox_setting',
			"regist_metabox" => $this->ltd . '_regist_metabox',
			"post_add_edit" => $this->ltd . '_post_add_edit_setting',
			"appearance_menus" => $this->ltd . '_appearance_menus_setting',
			"loginscreen" => $this->ltd . '_loginscreen_setting',
			"donate" => $this->ltd . '_donated',
		);
		$this->PageSlug = 'wp_admin_ui_customize';
		$this->PluginSlug = dirname( plugin_basename( __FILE__ ) );
		$this->Nonces = array( "field" => $this->ltd . '_field' , "value" => $this->ltd . '_value' );
		$this->Schema = is_ssl() ? 'https://' : 'http://';
		$this->UPFN = 'Y';
		$this->DonateKey = 'd77aec9bc89d445fd54b4c988d090f03';
		$this->MsgQ = $this->ltd . '_msg';
		
		$this->PluginSetup();
		$this->FilterStart();
	}





	// PluginSetup
	function PluginSetup() {
		// load text domain
		load_plugin_textdomain( $this->ltd , false , $this->PluginSlug . '/languages' );
		load_plugin_textdomain( $this->ltd_p , false , $this->PluginSlug . '/languages' );

		// plugin links
		add_filter( 'plugin_action_links' , array( $this , 'plugin_action_links' ) , 10 , 2 );

		// plugin links
		add_filter( 'network_admin_plugin_action_links' , array( $this , 'network_admin_plugin_action_links' ) , 10 , 2 );

		// add menu
		add_action( 'admin_menu' , array( $this , 'admin_menu' ) , 2 );

		// setting check user role
		add_action( 'admin_notices' , array( $this , 'settingCheck' ) );

		// data update
		add_action( 'admin_init' , array( $this , 'dataUpdate') );
	}

	// PluginSetup
	function plugin_action_links( $links , $file ) {
		if( plugin_basename(__FILE__) == $file ) {
			$link = '<a href="' . self_admin_url( 'admin.php?page=' . $this->PageSlug ) . '">' . __( 'Settings' ) . '</a>';
			$support_link = '<a href="http://wordpress.org/support/plugin/' . $this->PluginSlug . '" target="_blank">' . __( 'Support Forums' ) . '</a>';
			$delete_userrole_link = '<a href="' . self_admin_url( 'admin.php?page=' . $this->PageSlug . '_reset_userrole' ) . '">' . __( 'Reset User Roles' , $this->ltd ) . '</a>';
			array_unshift( $links, $link , $delete_userrole_link , $support_link  );
		}
		return $links;
	}

	// PluginSetup
	function network_admin_plugin_action_links( $links , $file ) {
		if( plugin_basename(__FILE__) == $file ) {
			$support_link = '<a href="' . $this->Site . 'multisite_about/?utm_source=use_plugin&utm_medium=list&utm_content=' . $this->ltd . '&utm_campaign=' . str_replace( '.' , '_' , $this->Ver ) . '" target="_blank">Multisite Add-on</a>';
			array_unshift( $links, $support_link );
		}

		return $links;
	}

	// PluginSetup
	function admin_menu() {

		if( !empty( $_GET["page"] ) ) {
			$page = strip_tags( $_GET["page"] );
			if( $page == $this->PageSlug . '_admin_bar' ) {
				@header("X-XSS-Protection: 0");
			}
		}

		add_menu_page( $this->Name , $this->Name , 'administrator', $this->PageSlug , array( $this , 'setting_default') );
		add_submenu_page( $this->PageSlug , __( 'Site Settings' , $this->ltd ) , __( 'Site Settings' , $this->ltd ) , 'administrator' , $this->PageSlug . '_setting_site' , array( $this , 'setting_site' ) );
		add_submenu_page( $this->PageSlug , __( 'General Screen Settings' , $this->ltd ) , __( 'General Screen Settings' , $this->ltd ) , 'administrator' , $this->PageSlug . '_admin_general_setting' , array( $this , 'setting_admin_general' ) );
		add_submenu_page( $this->PageSlug , __( 'Dashboard' ) , __( 'Dashboard' ) , 'administrator' , $this->PageSlug . '_dashboard' , array( $this , 'setting_dashboard' ) );
		add_submenu_page( $this->PageSlug , __( 'Admin Bar Menu' , $this->ltd ) , __( 'Admin Bar Menu' , $this->ltd ) , 'administrator' , $this->PageSlug . '_admin_bar' , array( $this , 'setting_admin_bar_menu' ) );
		add_submenu_page( $this->PageSlug , __( 'Side Menu' , $this->ltd ) , __( 'Side Menu' , $this->ltd ) , 'administrator' , $this->PageSlug . '_sidemenu' , array( $this , 'setting_sidemenu' ) );
		add_submenu_page( $this->PageSlug , __( 'Remove meta box' , $this->ltd ) , __( 'Remove meta box' , $this->ltd ) , 'administrator' , $this->PageSlug . '_removemtabox' , array( $this , 'setting_removemtabox' ) );
		add_submenu_page( $this->PageSlug , __( 'Add New Post and Edit Post Screen Setting' , $this->ltd ) , __( 'Add New Post and Edit Post Screen Setting' , $this->ltd ) , 'administrator' , $this->PageSlug . '_post_add_edit_screen' , array( $this , 'setting_post_add_edit' ) );
		add_submenu_page( $this->PageSlug , __( 'Appearance Menus Screen Setting' , $this->ltd ) , __( 'Appearance Menus Screen Setting' , $this->ltd ) , 'administrator' , $this->PageSlug . '_appearance_menus' , array( $this , 'setting_appearance_menus' ) );
		add_submenu_page( $this->PageSlug , __( 'Login Screen' , $this->ltd ) , __( 'Login Screen' , $this->ltd ) , 'administrator' , $this->PageSlug . '_loginscreen' , array( $this , 'setting_loginscreen' ) );
		add_submenu_page( $this->PageSlug , __( 'Reset User Roles' , $this->ltd ) , '<div style="display: none";>' . __( 'Reset User Roles' , $this->ltd ) . '</div>' , 'administrator' , $this->PageSlug . '_reset_userrole' , array( $this , 'reset_userrole' ) );
	}





	// SettingPage
	function setting_default() {
		$this->display_msg();
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		include_once 'inc/setting_default.php';
	}

	// SettingPage
	function setting_site() {
		$this->display_msg();
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->DisplayDonation();
		include_once 'inc/setting_site.php';
	}

	// SettingPage
	function setting_admin_general() {
		$this->display_msg();
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->DisplayDonation();
		include_once 'inc/setting_admin_general.php';
	}

	// SettingPage
	function setting_dashboard() {
		$this->display_msg();
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->DisplayDonation();
		include_once 'inc/setting_dashboard.php';
	}

	// SettingPage
	function setting_admin_bar_menu() {
		$this->display_msg();
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->DisplayDonation();
		include_once 'inc/setting_admin_bar_menu.php';
	}

	// SettingPage
	function setting_sidemenu() {
		$this->display_msg();
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->DisplayDonation();
		include_once 'inc/setting_sidemenu.php';
	}

	// SettingPage
	function setting_removemtabox() {
		$this->display_msg();
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->DisplayDonation();
		include_once 'inc/setting_removemtabox.php';
	}

	// SettingPage
	function setting_post_add_edit() {
		$this->display_msg();
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->DisplayDonation();
		include_once 'inc/setting_post_add_edit.php';
	}

	// SettingPage
	function setting_appearance_menus() {
		$this->display_msg();
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->DisplayDonation();
		include_once 'inc/setting_appearance_menus.php';
	}

	// SettingPage
	function setting_loginscreen() {
		$this->display_msg();
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->DisplayDonation();
		include_once 'inc/setting_loginscreen.php';
	}

	// SettingPage
	function reset_userrole() {
		$this->display_msg();
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		include_once 'inc/reset_userrole.php';
	}





	// GetData
	function get_data( $record ) {
		$GetData = get_option( $this->Record[$record] );
		$GetData = apply_filters( 'wauc_pre_get_data' , $GetData , $record );

		$Data = array();
		if( !empty( $GetData ) && !empty( $GetData["UPFN"] ) && $GetData["UPFN"] == $this->UPFN ) {
			$Data = $GetData;
		}

		return $Data;
	}

	// GetData
	function get_flit_data( $record ) {
		$GetData = get_option( $this->Record[$record] );
		$GetData = apply_filters( 'wauc_pre_get_filt_data' , $GetData , $record );

		$Data = array();
		if( !empty( $GetData ) && !empty( $GetData["UPFN"] ) && $GetData["UPFN"] == $this->UPFN ) {
			$Data = $GetData;
		}

		return $Data;
	}



	// Settingcheck
	function settingCheck() {
		global $current_screen;

		$Data = $this->get_data( 'user_role' );
		if( !empty( $Data["UPFN"] ) ) {
			unset( $Data["UPFN"] );
		}
		if( empty( $Data ) ) {
			if( $current_screen->parent_base == $this->PageSlug && $current_screen->id != 'toplevel_page_' . $this->PageSlug ) {
				echo '<div class="error"><p><strong>' . sprintf( __( 'Authority to apply the setting is not selected. <a href="%s">From here</a>, please select the permissions you want to set.' , $this->ltd ) , admin_url( 'admin.php?page=' . $this->PageSlug ) ) . '</strong></p></div>';
			}
		}
	}





	// SetList
	function get_user_role() {
		$editable_roles = get_editable_roles();
		foreach ( $editable_roles as $role => $details ) {
			$editable_roles[$role]["label"] = translate_user_role( $details['name'] );
		}

		return $editable_roles;
	}

	// SetList
	function get_apply_roles() {

		$apply_user_roles = $this->get_data( 'user_role' );
		unset( $apply_user_roles["UPFN"] );
		
		$Contents =  __( 'Apply user roles' , $this->ltd ) . ' : ';
		
		if( !empty( $apply_user_roles ) ) {
			$UserRoles = $this->get_user_role();
			foreach( $apply_user_roles as $role => $v ) {
				$Contents .= '[ ' . $UserRoles[$role]["label"] . ' ]';
			}
		} else {
			$Contents .= __( 'None' );
		}

		$Contents = apply_filters( 'wauc_get_apply_roles' , $Contents );

		return $Contents;

	}

	// SetList
	function sidemenu_default_load() {
		global $menu , $submenu;

		$this->Menu = $menu;
		$this->SubMenu = $submenu;
	}

	// SetList
	function admin_bar_default_load( $wp_admin_bar ) {
		global $wp_admin_bar;

		$this->Admin_bar = $wp_admin_bar->get_nodes();
	}

	// SetList
	function admin_bar_filter_load() {
		$Default_bar = $this->Admin_bar;
		
		$Delete_bar = array( "user-actions" , "wp-logo-external" , "top-secondary" , "my-sites-super-admin" , "my-sites-list" );
		foreach( $Delete_bar as $del_name ) {
			if( !empty( $Default_bar[$del_name] ) ) {
				unset( $Default_bar[$del_name] );
			}
		}
		if( !empty( $Default_bar ) ) {
			foreach( $Default_bar as $node_id => $node ) {
				if( preg_match( "/blog-[0-9]/" , $node->parent ) ) {
					unset( $Default_bar[$node_id] );
				}
			}
		}

		// front 
		$Default_bar["dashboard"] = (object) array( "id" => "dashboard" , "title" => __( 'Dashboard' ) , "parent" => "site-name" , "href" => admin_url() );
		
		foreach( $Default_bar as $node_id => $node ) {
			if( $node->id == 'my-account' ) {
				$Default_bar[$node_id]->title = sprintf( __( 'Howdy, %1$s' ) , '[user_name]' );
			} elseif( $node->id == 'user-info' ) {
				$Default_bar[$node_id]->title = '<span class="display-name">[user_name]</span>';
			} elseif( $node->id == 'logout' ) {
				$Default_bar[$node_id]->href = preg_replace( '/&amp(.*)/' , '' , $node->href );
			} elseif( $node->id == 'site-name' ) {
				$Default_bar[$node_id]->title = '[blog_name]';
			} elseif( $node->id == 'updates' ) {
				$Default_bar[$node_id]->title = '[update_total]';
			} elseif( $node->id == 'comments' ) {
				$Default_bar[$node_id]->title = '[comment_count]';
			}
		}

		$Filter_bar = array();
		$MainMenuIDs = array();

		foreach( $Default_bar as $node_id => $node ) {
			if( empty( $node->parent ) ) {
				$Filter_bar["left"]["main"][$node_id] = $node;
				$MainMenuIDs[$node_id] = "left";
				unset( $Default_bar[$node_id] );
			} elseif( $node->parent == 'top-secondary' ) {
				$Filter_bar["right"]["main"][$node_id] = $node;
				$MainMenuIDs[$node_id] = "right";
				unset( $Default_bar[$node_id] );
			}
		}
		
		foreach( $Default_bar as $node_id => $node ) {
			if( $node->parent == 'wp-logo-external' ) {
				$Default_bar[$node_id]->parent = 'wp-logo';
			} elseif( $node->parent == 'user-actions' ) {
				$Default_bar[$node_id]->parent = 'my-account';
			} elseif( $node->parent == 'my-sites-list' ) {
				$Default_bar[$node_id]->parent = 'my-sites';
			} else{
				if( !array_keys( $MainMenuIDs , $node->parent ) ) {
					if( !empty( $Default_bar[$node->parent] ) ) {
						$Default_bar[$node_id]->parent = $Default_bar[$node->parent]->parent;
					}
				}
			}
		}

		// meta field add
		foreach( $Default_bar as $node_id => $node ) {
			if( !isset( $node->meta ) ) {
				$Default_bar[$node_id]->meta = array();
			}
		}
		
		foreach( $MainMenuIDs as $parent_id => $menu_type ) {

			foreach( $Default_bar as $node_id => $node ) {
				if( $node->parent == $parent_id ) {
					$Filter_bar[$menu_type]["sub"][$node_id] = $node;
					unset( $Default_bar[$node_id] );
				}
			}
		}
		
		return $Filter_bar;
	}

	// SetList
	function post_meta_boxes_load() {
		global $current_screen;

		$UserRole = $this->current_user_role_group();

		if( $current_screen->base == 'post' && $current_screen->action != 'add' && $UserRole == 'administrator' ) {
			global $wp_meta_boxes;

			$GetData = $this->get_data( "regist_metabox" );
			$post_type = $current_screen->post_type;
			$Metaboxes = $wp_meta_boxes[$post_type];
				
			$Update = array();
			if( empty( $GetData ) ) {

				$Update["UPFN"] = $this->UPFN;
				foreach( $Metaboxes as $context => $meta_box ) {
					foreach( $meta_box as $priority => $box ) {
						foreach( $box as $metabox_id => $b ) {
							$Update["metaboxes"][$post_type][$context][$priority][$b["id"]] = strip_tags( $b["title"] );
						}
					}
				}
					
			} else {
					
				$Update = $GetData;
				if( !empty( $Metaboxes ) ) {
					foreach( $Metaboxes as $context => $meta_box ) {
						foreach( $meta_box as $priority => $box ) {
							if( is_array( $box ) ) {
								foreach( $box as $metabox_id => $b ) {
									if( !empty( $GetData["metaboxes"][$post_type][$context][$priority][$b["id"]] ) ) {
										$Update["metaboxes"][$post_type][$context][$priority][$b["id"]] = strip_tags( $b["title"] );
									} else {
										$Update["metaboxes"][$post_type][$context][$priority][$b["id"]] = strip_tags( $b["title"] );
									}
								}
							}
						}
					}
				}
				
			}

			if( !empty( $Update ) ) {
				update_option( $this->Record["regist_metabox"] , $Update );
			}
		}

	}


	// SetList
	function sidebar_menu_widget( $menu_widget ) {
		$UserRoles = $this->get_user_role();
		$new_widget = '';
		if( !empty( $menu_widget["new"] ) ) {
			$new_widget = 'new';
		}
?>
		<div class="widget <?php echo $menu_widget["slug"]; ?> <?php echo $new_widget; ?>">

			<div class="widget-top">
				<div class="widget-title-action">
					<a class="widget-action" href="#available"></a>
				</div>
				<div class="widget-title">
					<h4>
						<?php echo $menu_widget["title"]; ?>
						: <span class="in-widget-title"><?php echo $menu_widget["slug"]; ?></span>
					</h4>
				</div>
			</div>

			<div class="widget-inside">
				<div class="settings">
					<p class="description">
						<?php if( $menu_widget["slug"] == 'custom_menu' ) : ?>
							<?php _e( 'Url' ); ?>:
							<input type="text" class="slugtext" value="" name="data[][slug]">
						<?php else : ?>
							<?php _e( 'Slug' ); ?>: <?php echo $menu_widget["slug"]; ?>
							<input type="hidden" class="slugtext" value="<?php echo $menu_widget["slug"]; ?>" name="data[][slug]">
						<?php endif; ?>
					</p>
					<?php _e( 'User Roles' ); ?> : 
					<ul class="display_roles">
						<?php foreach( $UserRoles as $role_name => $val ) : ?>
							<?php $has_cap = false; ?>
							<?php if( !empty( $val["capabilities"][$menu_widget["cap"]] ) or $role_name == $menu_widget["cap"] ) : ?>
								<?php $has_cap = 'has_cap'; ?>
							<?php endif; ?>
							<li class="<?php echo $role_name; ?> <?php echo $has_cap; ?>"><?php echo $val["label"]; ?></li>
						<?php endforeach ;?>
					</ul>
					<label>
						<?php _e( 'Title' ); ?> : <input type="text" class="regular-text titletext" value="<?php echo esc_attr( $menu_widget["title"] ); ?>" name="data[][title]">
					</label>
					<input type="hidden" class="parent_slugtext" value="<?php echo $menu_widget["parent_slug"]; ?>" name="data[][parent_slug]">
				</div>

				<?php if( $menu_widget["slug"] != 'separator' ) : ?>
					<div class="submenu">
						<p class="description"><?php _e( 'Sub Menus' , $this->ltd ); ?></p>
						<?php if( empty( $menu_widget["new"] ) && !empty( $menu_widget["submenu"] ) ) : ?>
							<?php foreach( $menu_widget["submenu"] as $sm ) : ?>
								<?php $sepalator_widget = ''; ?>
								<?php if( $sm["slug"] == 'separator' ) : $sepalator_widget = $sm["slug"]; endif; ?>

								<div class="widget <?php echo $sepalator_widget; ?>">

									<div class="widget-top">
										<div class="widget-title-action">
											<a class="widget-action" href="#available"></a>
										</div>
										<div class="widget-title">
											<h4>
												<?php echo $sm["title"]; ?>
												: <span class="in-widget-title"><?php echo $sm["slug"]; ?></span>
											</h4>
										</div>
									</div>

									<div class="widget-inside">
										<div class="settings">
											<p class="description">
												<?php _e( 'Slug' ); ?>: <?php echo $sm["slug"]; ?>
												<input type="hidden" class="slugtext" value="<?php echo $sm["slug"]; ?>" name="data[][slug]">
											</p>
											<?php _e( 'User Roles' ); ?> : 
											<ul class="display_roles">
												<?php foreach( $UserRoles as $role_name => $val ) : ?>
													<?php $has_cap = false; ?>
													<?php if( !empty( $val["capabilities"][$sm["cap"]] ) or $role_name == $sm["cap"] ) : ?>
														<?php $has_cap = 'has_cap'; ?>
													<?php endif; ?>
													<li class="<?php echo $role_name; ?> <?php echo $has_cap; ?>"><?php echo $val["label"]; ?></li>
												<?php endforeach ;?>
											</ul>
											<label>
												<?php _e( 'Title' ); ?> : <input type="text" class="regular-text titletext" value="<?php echo esc_attr( $sm["title"] ); ?>" name="data[][title]">
											</label>
											<input type="hidden" class="parent_slugtext" value="<?php echo $sm["parent_slug"]; ?>" name="data[][parent_slug]">
										</div>
										<div class="widget-control-actions">
											<div class="alignleft">
												<a href="#remove"><?php _e( 'Remove' ); ?></a>
											</div>
											<div class="clear"></div>
										</div>
									</div>
								</div>

							<?php endforeach; ?>
						<?php endif; ?>
					</div>
					<div class="widget-control-actions">
						<div class="alignleft">
							<a href="#remove"><?php _e( 'Remove' ); ?></a>
						</div>
						<div class="clear"></div>
					</div>

				<?php endif; ?>
			</div>

		</div>
<?php
	}

	// SetList
	function admin_bar_menu_widget( $menu_widget ) {
		$new_widget = '';
		if( !empty( $menu_widget["new"] ) ) {
			$new_widget = 'new';
		}
?>
		<div class="widget <?php echo $new_widget; ?> <?php echo $menu_widget["id"]; ?>">

			<div class="widget-top">
				<div class="widget-title-action">
					<a class="widget-action" href="#available"></a>
				</div>
				<div class="widget-title">
					<h4>
						<?php if( preg_match( '/\<form/' , $menu_widget["title"] ) ) : ?>
							<?php echo $menu_widget["id"]; ?>
						<?php else: ?>
							<?php echo $menu_widget["title"]; ?>
							: <span class="in-widget-title"><?php echo $menu_widget["id"]; ?></span>
						<?php endif; ?>
					</h4>
				</div>
			</div>

			<div class="widget-inside">
				<div class="settings">
					<p class="field-url description">
						<input type="hidden" class="idtext" value="<?php echo $menu_widget["id"]; ?>" name="data[][id]" />
						<?php if( $menu_widget["id"] == 'custom_node' ) : ?>
							URL: <input type="text" class="regular-text linktext" value="" name="data[][href]" placeholder="http://" />
						<?php else:  ?>
							<a href="<?php echo $menu_widget["href"]; ?>" target="_blank"><?php echo $menu_widget["id"]; ?></a>
							<input type="hidden" class="linktext" value="<?php echo $menu_widget["href"]; ?>" name="data[][href]" />
						<?php endif; ?>
					</p>
					<p class="field-title description">
						<label>
							<?php _e( 'Title' ); ?> : <input type="text" class="regular-text titletext" value="<?php echo esc_html( $menu_widget["title"] ); ?>" name="data[][title]" />
						</label>
					</p>
					<p class="field-meta description">
						<label class="description">
							<?php $checked = ""; ?>
							<?php if( !empty( $menu_widget["meta"]["target"] ) ) : ?>
								<?php $checked = checked( $menu_widget["meta"]["target"] , '_blank' , 0 ); ?>
							<?php endif; ?>
							<input type="checkbox" class="meta_target" value="_blank" name="data[][meta][target]" <?php echo $checked; ?> />
							<?php _e( 'Open link in a new window/tab' ); ?>
						</label>
					</p>
					<input type="hidden" class="parent" value="<?php echo $menu_widget["parent"]; ?>" name="data[][parent]" />
				</div>

				<div class="submenu">
					<p class="description"><?php _e( 'Sub Menus' , $this->ltd ); ?></p>
					<?php if( empty( $menu_widget["new"] ) && !empty( $menu_widget["subnode"] ) ) : ?>
						<?php foreach( $menu_widget["subnode"] as $sm ) : ?>

							<div class="widget">

								<div class="widget-top">
									<div class="widget-title-action">
										<a class="widget-action" href="#available"></a>
									</div>
									<div class="widget-title">
										<h4>
											<?php if( preg_match( '/\<form/' , $sm["title"] ) ) : ?>
												<?php echo $sm["id"]; ?>
											<?php else: ?>
												<?php echo $sm["title"]; ?>
												: <span class="in-widget-title"><?php echo $sm["id"]; ?></span>
											<?php endif; ?>
										</h4>
									</div>
								</div>

								<div class="widget-inside">
									<div class="settings">
										<p class="field-url description">
											<input type="hidden" class="idtext" value="<?php echo $sm["id"]; ?>" name="data[][id]" />
											<a href="<?php echo $sm["href"]; ?>" target="_blank"><?php echo $sm["id"]; ?></a>
											<input type="hidden" class="linktext" value="<?php echo $sm["href"]; ?>" name="data[][href]" />
										</p>
										<p class="field-title description">
											<label>
												<?php _e( 'Title' ); ?> : <input type="text" class="regular-text titletext" value="<?php echo esc_html( $sm["title"] ); ?>" name="data[][title]" />
											</label>
										</p>
										<p class="field-meta description">
											<label class="description">
												<?php $checked = ""; ?>
												<?php if( !empty( $sm["meta"]["target"] ) ) : ?>
													<?php $checked = checked( $sm["meta"]["target"] , '_blank' , 0 ); ?>
												<?php endif; ?>
												<input type="checkbox" class="meta_target" value="_blank" name="data[][meta][target]" <?php echo $checked; ?> />
												<?php _e( 'Open link in a new window/tab' ); ?>
											</label>
										</p>
										<input type="hidden" class="parent" value="<?php echo $sm["parent"]; ?>" name="data[][parent]" />
									</div>
									<div class="widget-control-actions">
										<div class="alignleft">
											<a href="#remove"><?php _e( 'Remove' ); ?></a>
										</div>
										<div class="clear"></div>
									</div>
								</div>
							</div>

						<?php endforeach; ?>
					<?php endif; ?>
				</div>
				<div class="widget-control-actions">
					<div class="alignleft">
						<a href="#remove"><?php _e( 'Remove' ); ?></a>
					</div>
					<div class="clear"></div>
				</div>
			</div>

		</div>
<?php
	}

	// SetList
	function get_custom_posts() {
		$args = array( );
		$all_custom_posts = get_post_types( $args , 'objects' );
		
		$exclusion = array( "post" , "page" , "attachment" , "revision" , "nav_menu_item");
		$custom_posts = array();
		foreach($all_custom_posts as $post_type => $cpt) {
			if( !in_array( $post_type , $exclusion ) ) {
				if( !empty( $cpt->show_ui ) ) {
					$custom_posts[$post_type] = $cpt;
				}
			}
		}
		
		return $custom_posts;
	}
			

	// SetList
	function val_replace( $str ) {
		
		if( !empty( $str ) ) {

			$update_data = wp_get_update_data();
			$awaiting_mod = wp_count_comments();
			$awaiting_mod = $awaiting_mod->moderated;
			$current_user = wp_get_current_user();
			if( is_multisite() ) {
				$current_site = get_current_site();
			}

			if( strstr( $str , '[blog_url]') ) {
				$str = str_replace( '[blog_url]' , get_bloginfo( 'url' ) , $str );
			}
			if( strstr( $str , '[template_directory_uri]') ) {
				$str = str_replace( '[template_directory_uri]' , get_bloginfo( 'template_directory' ) , $str );
			}
			if( strstr( $str , '[stylesheet_directory_uri]') ) {
				$str = str_replace( '[stylesheet_directory_uri]' , get_stylesheet_directory_uri() , $str );
			}
			if( strstr( $str , '[blog_name]') ) {
				$str = str_replace( '[blog_name]' , get_bloginfo( 'name' ) , $str );
			}
			if( strstr( $str , '[update_total]') ) {
				$str = str_replace( '[update_total]' , $update_data["counts"]["total"] , $str );
			}
			if( strstr( $str , '[update_total_format]') ) {
				$str = str_replace( '[update_total_format]' , number_format_i18n( $update_data["counts"]["total"] ) , $str );
			}
			if( strstr( $str , '[update_plugins]') ) {
				$str = str_replace( '[update_plugins]' , $update_data["counts"]["plugins"] , $str );
			}
			if( strstr( $str , '[update_plugins_format]') ) {
				$str = str_replace( '[update_plugins_format]' , number_format_i18n( $update_data["counts"]["plugins"] ) , $str );
			}
			if( strstr( $str , '[update_themes]') ) {
				$str = str_replace( '[update_themes]' , $update_data["counts"]["themes"] , $str );
			}
			if( strstr( $str , '[update_themes_format]') ) {
				$str = str_replace( '[update_themes_format]' , number_format_i18n( $update_data["counts"]["themes"] ) , $str );
			}
			if( strstr( $str , '[comment_count]') ) {
				$str = str_replace( '[comment_count]' , $awaiting_mod , $str );
			}
			if( strstr( $str , '[comment_count_format]') ) {
				$str = str_replace( '[comment_count_format]' , number_format_i18n( $awaiting_mod ) , $str );
			}
			if( strstr( $str , '[user_name]') ) {
				$str = str_replace( '[user_name]' , '<span class="display-name">' . $current_user->display_name . '</span>' , $str );
			}

			if( is_multisite() ) {
				if( strstr( $str , '[site_name]') ) {
					$str = str_replace( '[site_name]' , esc_attr( $current_site->site_name ) , $str );
				}
				if( strstr( $str , '[site_url]') ) {
					$str = str_replace( '[site_url]' , $this->Schema . esc_attr( $current_site->domain ) , $str );
				}
			}

		}

		return $str;
	}

	// SetList
	function current_user_role_group() {
		$UserRole = '';
		$User = wp_get_current_user();
		if( !empty( $User->roles ) ) {
			foreach( $User->roles as $role ) {
				$UserRole = $role;
				break;
			}
		}
		return $UserRole;
	}



	// DataUpdate
	function dataUpdate() {

		$RecordField = false;
		
		if( !empty( $_POST["record_field"] ) ) {
			$RecordField = strip_tags( $_POST["record_field"] );
		}
		
		if( !empty( $RecordField ) && !empty( $_POST["update"] ) ) {
			if( $RecordField == 'user_role' ) {
				$this->update_userrole();
			} elseif( $RecordField == 'site' ) {
				$this->update_site();
			} elseif( $RecordField == 'admin_general' ) {
				$this->update_admin_general();
			} elseif( $RecordField == 'dashboard' ) {
				$this->update_dashboard();
			} elseif( $RecordField == 'admin_bar_menu' ) {
				$this->update_admin_bar_menu();
			} elseif( $RecordField == 'sidemenu' ) {
				$this->update_sidemenu();
			} elseif( $RecordField == 'removemetabox' ) {
				$this->update_removemetabox();
			} elseif( $RecordField == 'post_add_edit' ) {
				$this->update_post_add_edit();
			} elseif( $RecordField == 'appearance_menus' ) {
				$this->update_appearance_menus();
			} elseif( $RecordField == 'loginscreen' ) {
				$this->update_loginscreen();
			}
		}

		if( !empty( $RecordField ) && !empty( $_POST["reset"] ) ) {
			if( $RecordField == 'removemetabox' ) {
				delete_option( $this->Record["regist_metabox"] );
			}
			$this->update_reset( $RecordField );
		}

		if( !empty( $_POST["donate_key"] ) && !empty( $_POST["update"] ) ) {
			$this->DonatingCheck();
		}

	}

	// DataUpdate
	function update_validate() {
		$Update = array();

		if( !empty( $_POST[$this->UPFN] ) ) {
			$UPFN = strip_tags( $_POST[$this->UPFN] );
			if( $UPFN == $this->UPFN ) {
				$Update["UPFN"] = strip_tags( $_POST[$this->UPFN] );
			}
		}

		return $Update;
	}

	// DataUpdate
	function update_reset( $record ) {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {
			$record = apply_filters( 'wauc_pre_delete' , $this->Record[$record] );
			delete_option( $record );
			wp_redirect( add_query_arg( $this->MsgQ , 'delete' , stripslashes( $_POST["_wp_http_referer"] ) ) );
			exit;
		}
	}

	// DataUpdate
	function DonatingCheck() {
		$Update = $this->update_validate();

		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {
			if( !empty( $_POST["donate_key"] ) ) {
				$SubmitKey = md5( strip_tags( $_POST["donate_key"] ) );
				if( $this->DonateKey == $SubmitKey ) {
					update_option( $this->Record["donate"] , $SubmitKey );
					wp_redirect( add_query_arg( $this->MsgQ , 'donated' , stripslashes( $_POST["_wp_http_referer"] ) ) );
					exit;
				}
			}
		}

	}

	// DataUpdate
	function update_userrole() {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {

			if( !empty( $_POST["data"]["user_role"] ) ) {
				foreach($_POST["data"]["user_role"] as $key => $val) {
					$tmpK = strip_tags( $key );
					$tmpV = strip_tags ( $val );
					$Update[$tmpK] = $tmpV;
				}
			}

			update_option( $this->Record["user_role"] , $Update );
			wp_redirect( add_query_arg( $this->MsgQ , 'update' , stripslashes( $_POST["_wp_http_referer"] ) ) );
			exit;
		}
	}

	// DataUpdate
	function update_site() {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {

			if( !empty( $_POST["data"] ) ) {
				foreach($_POST["data"] as $key => $val) {
					$tmpK = strip_tags( $key );
					$tmpV = strip_tags ( $val );
					$Update[$tmpK] = $tmpV;
				}
			}

			$Record = apply_filters( 'wauc_pre_update' , $this->Record["site"] );
			update_option( $Record , $Update );
			wp_redirect( add_query_arg( $this->MsgQ , 'update' , stripslashes( $_POST["_wp_http_referer"] ) ) );
			exit;
		}
	}

	// DataUpdate
	function update_admin_general() {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {

			if( !empty( $_POST["data"] ) ) {
				foreach($_POST["data"] as $key => $val) {
					$tmpK = strip_tags( $key );
					$tmpV = $val;
					$Update[$tmpK] = $tmpV;
				}
			}

			$Record = apply_filters( 'wauc_pre_update' , $this->Record["admin_general"] );
			update_option( $Record , $Update );
			wp_redirect( add_query_arg( $this->MsgQ , 'update' , stripslashes( $_POST["_wp_http_referer"] ) ) );
			exit;
		}
	}

	// DataUpdate
	function update_dashboard() {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {

			if( !empty( $_POST["data"] ) ) {
				foreach($_POST["data"] as $key => $val) {
					$tmpK = strip_tags( $key );
					$tmpV = $val;
					$Update[$tmpK] = $tmpV;
				}
			}

			$Record = apply_filters( 'wauc_pre_update' , $this->Record["dashboard"] );
			update_option( $Record , $Update );
			wp_redirect( add_query_arg( $this->MsgQ , 'update' , stripslashes( $_POST["_wp_http_referer"] ) ) );
			exit;
		}
	}

	// DataUpdate
	function update_admin_bar_menu() {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {

			if( !empty( $_POST["data"] ) ) {
				foreach($_POST["data"] as $boxtype => $nodes) {
					if( $boxtype === 'left' or $boxtype === 'right' ) {
						foreach($nodes as $key => $node) {
							$id = "";
							if( !empty( $node["id"] ) ) {
								$id = strip_tags( $node["id"] );
							}
							$title = "";
							if( !empty( $node["title"] ) ) {
								$title = stripslashes( $node["title"] );
							}
							$href = "";
							if( !empty( $node["href"] ) ) {
								$href = strip_tags( $node["href"] );
							}
							$parent = "";
							$depth = "main";
							if( !empty( $node["parent"] ) ) {
								$parent = strip_tags( $node["parent"] );
								$depth = 'sub';
							}
							$meta = array();
							if( !empty( $node["meta"] ) ) {
								foreach( $node["meta"] as $mk => $mv ) {
									if( !empty( $mv ) ) {
										$meta[strip_tags($mk)] = strip_tags($mv);
									}
								}
							}

							$Update[$boxtype][$depth][] = array( "id" => $id , "title" => $title , "href" => $href , "parent" => $parent , "meta" => $meta );
						}
					}
				}
			}

			$Record = apply_filters( 'wauc_pre_update' , $this->Record["admin_bar_menu"] );
			update_option( $Record , $Update );
			wp_redirect( add_query_arg( $this->MsgQ , 'update' , stripslashes( $_POST["_wp_http_referer"] ) ) );
			exit;
		}
	}

	// DataUpdate
	function update_sidemenu() {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {

			if( !empty( $_POST["data"] ) ) {
				foreach($_POST["data"] as $menu) {
					if( !empty( $menu["title"] ) && !empty( $menu["slug"] ) ) {
						$slug = htmlspecialchars( $menu["slug"] );
						$title = stripslashes( $menu["title"] );
						$parent_slug = '';
						$depth = 'main';

						if( !empty( $menu["parent_slug"] ) ) {
							$parent_slug = strip_tags( $menu["parent_slug"] );
							$depth = 'sub';
						}

						$Update[$depth][] = array( "slug" => $slug , "title" => $title , "parent_slug" => $parent_slug );
					}
				}
			}

			$Record = apply_filters( 'wauc_pre_update' , $this->Record["sidemenu"] );
			update_option( $Record , $Update );
			wp_redirect( add_query_arg( $this->MsgQ , 'update' , stripslashes( $_POST["_wp_http_referer"] ) ) );
			exit;
		}
	}

	// DataUpdate
	function update_removemetabox() {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {

			if( !empty( $_POST["data"] ) ) {
				foreach($_POST["data"] as $post_type => $val) {
					$post_type = strip_tags( $post_type );
					if( is_array( $val ) ) {
						foreach($val as $id => $v) {
							$tmpK = strip_tags( $id );
							$tmpV = strip_tags ( $v );
							$Update[$post_type][$tmpK] = $tmpV;
						}
					}
				}
			}

			$Record = apply_filters( 'wauc_pre_update' , $this->Record["removemetabox"] );
			update_option( $Record , $Update );
			wp_redirect( add_query_arg( $this->MsgQ , 'update' , stripslashes( $_POST["_wp_http_referer"] ) ) );
			exit;
		}
	}

	// DataUpdate
	function update_post_add_edit() {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {

			if( !empty( $_POST["data"] ) ) {
				foreach($_POST["data"] as $edited => $val) {
					$tmpK = strip_tags( $edited );
					$tmpV = strip_tags ( $val );
					$Update[$tmpK] = $tmpV;
				}
			}

			$Record = apply_filters( 'wauc_pre_update' , $this->Record["post_add_edit"] );
			update_option( $Record , $Update );
			wp_redirect( add_query_arg( $this->MsgQ , 'update' , stripslashes( $_POST["_wp_http_referer"] ) ) );
			exit;
		}
	}

	// DataUpdate
	function update_appearance_menus() {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {

			if( !empty( $_POST["data"] ) ) {
				foreach($_POST["data"] as $edited => $val) {
					$tmpK = strip_tags( $edited );
					$tmpV = strip_tags ( $val );
					$Update[$tmpK] = $tmpV;
				}
			}

			$Record = apply_filters( 'wauc_pre_update' , $this->Record["appearance_menus"] );
			update_option( $Record , $Update );
			wp_redirect( add_query_arg( $this->MsgQ , 'update' , stripslashes( $_POST["_wp_http_referer"] ) ) );
			exit;
		}
	}

	// DataUpdate
	function update_loginscreen() {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {

			if( !empty( $_POST["data"] ) ) {
				foreach($_POST["data"] as $key => $val) {
					$tmpK = strip_tags( $key );
					$tmpV = $val;
					$Update[$tmpK] = $tmpV;
				}
			}

			$Record = apply_filters( 'wauc_pre_update' , $this->Record["loginscreen"] );
			update_option( $Record , $Update );
			wp_redirect( add_query_arg( $this->MsgQ , 'update' , stripslashes( $_POST["_wp_http_referer"] ) ) );
			exit;

		}
	}





	// FilterStart
	function FilterStart() {

		// site
		if( !is_admin() ) {
			add_action( 'wp_loaded' , array( $this , 'remove_action_front' ) ) ;
			add_filter( 'login_headerurl' , array( $this , 'login_headerurl' ) );
			add_filter( 'login_headertitle' , array( $this , 'login_headertitle' ) );
			add_action( 'login_head' , array( $this , 'login_head' ) );
			add_action( 'login_footer' , array( $this , 'login_footer' ) );

			// front init
			add_action( 'wp_loaded' , array( $this , 'front_init' ) );
		}

		// admin UI
		if( is_admin() && !is_network_admin () ) {
			// default side menu load.
			add_action( 'admin_menu' , array( $this , 'sidemenu_default_load' ) , 10000 );

			// default admin bar menu load.
			add_action( 'wp_before_admin_bar_render' , array( $this , 'admin_bar_default_load' ) , 1 );

			// default post metabox load.
			add_action( 'admin_head' , array( $this , 'post_meta_boxes_load' ) , 10 );

			// admin init
			add_action( 'wp_loaded' , array( $this , 'admin_init' ) );
		}
	}
	
	// FilterStart
	function admin_init() {

		$SettingRole = $this->get_data( 'user_role' );
		$SettingRole = apply_filters( 'wauc_pre_setting_roles' , $SettingRole );

		if( !empty( $SettingRole ) ) {
			unset($SettingRole["UPFN"]);

			$UserRole = $this->current_user_role_group();

			if( !is_network_admin() && !empty( $UserRole ) ) {
				if( array_key_exists( $UserRole , $SettingRole ) ) {
					add_action( 'wp_before_admin_bar_render' , array( $this , 'admin_bar_menu') , 25 );
					add_action( 'wp_loaded' , array( $this , 'notice_dismiss' ) , 2 );
					add_action( 'admin_head' , array( $this , 'remove_tab' ) );
					add_filter( 'admin_footer_text' , array( $this , 'admin_footer_text' ) );
					add_action( 'admin_print_styles' , array( $this , 'load_css' ) );
					add_action( 'wp_dashboard_setup' , array( $this , 'wp_dashboard_setup' ) );
					add_action( 'admin_head' , array( $this , 'removemetabox' ) , 11 );
					add_filter( 'admin_head', array( $this , 'sidemenu' ) );
					add_filter( 'get_sample_permalink_html' , array( $this , 'add_edit_post_change_permalink' ) );
					add_filter( 'edit_form_after_title' , array( $this , 'allow_comments' ) );
					add_action( 'admin_print_styles-nav-menus.php', array( $this , 'nav_menus' ) );
					add_filter( 'admin_title', array( $this, 'admin_title' ) );
				}
			}
		}
	}

	// FilterStart
	function front_init() {

		$SettingRole = $this->get_flit_data( 'user_role' );
		$SettingRole = apply_filters( 'wauc_pre_setting_roles' , $SettingRole );

		if( !empty( $SettingRole ) ) {
			unset($SettingRole["UPFN"]);

			$UserRole = $this->current_user_role_group();

			if( !is_network_admin() && !empty( $UserRole ) ) {
				if( array_key_exists( $UserRole , $SettingRole ) ) {

					$GetData = $this->get_flit_data( 'site' );
					
					if( !empty( $GetData["admin_bar"] ) ) {
						if( $GetData["admin_bar"] == "hide" ) {
							add_filter( 'show_admin_bar' , '__return_false' );
						} elseif( $GetData["admin_bar"] == "front" ) {
							add_action( 'init' , array( $this , 'notice_dismiss' ) , 2 );
							add_action( 'wp_before_admin_bar_render' , array( $this , 'admin_bar_menu') , 25 );
						}
					}
				}
			}
		}
	}

	// FilterStart
	function remove_action_front() {
		$GetData = get_option( $this->Record['site'] );
		
		if( !empty( $GetData["UPFN"] ) ) {
			unset( $GetData["UPFN"] );
			foreach($GetData as $key => $val) {
				if( $key == 'feed_links' ) {
					remove_action( 'wp_head', $key , 2 );
				} elseif( $key == 'feed_links_extra' ) {
					remove_action( 'wp_head', $key , 3 );
				} else {
					remove_action( 'wp_head', $key );
				}
			}
		}
	}

	// FilterStart
	function login_headerurl() {
		$GetData = get_option( $this->Record["loginscreen"] );

		$url = __( 'http://wordpress.org/' );
		if( !empty( $GetData["UPFN"] ) ) {
			unset( $GetData["UPFN"] );

			if( !empty( $GetData["login_headerurl"] ) ) {
				$url = strip_tags( $GetData["login_headerurl"] );
				$url = $this->val_replace( $url );
			}
		}

		return $url;
	}

	// FilterStart
	function login_headertitle() {
		$GetData = get_option( $this->Record["loginscreen"] );

		$title = __( 'Powered by WordPress' );
		if( !empty( $GetData["UPFN"] ) ) {
			unset( $GetData["UPFN"] );

			if( !empty( $GetData["login_headertitle"] ) ) {
				$title = strip_tags( $GetData["login_headertitle"] );
				$title = $this->val_replace( $title );
			}
		}

		return $title;
	}

	// FilterStart
	function login_head() {
		$GetData = get_option( $this->Record["loginscreen"] );

		if( !empty( $GetData["UPFN"] ) ) {
			unset( $GetData["UPFN"] );

			if( !empty( $GetData["login_headerlogo"] ) ) {
				$logo = strip_tags( $GetData["login_headerlogo"] );
				$logo = $this->val_replace( $logo );

				echo '<style type="text/css">.login h1 a { background-image: url(' . $logo . '); }</style>';
			}

			if( !empty( $GetData["login_css"] ) ) {
				$css = strip_tags( $GetData["login_css"] );
				$css = $this->val_replace( $css );

				wp_enqueue_style( $this->PageSlug , $css , array() , $this->Ver );
			}

		}

	}

	// FilterStart
	function login_footer() {
		$GetData = get_option( $this->Record["loginscreen"] );

		if( !empty( $GetData["UPFN"] ) ) {
			unset( $GetData["UPFN"] );

			if( !empty( $GetData["login_footer"] ) ) {
				$text = $this->val_replace( stripslashes( $GetData["login_footer"] ) );

				echo $text;
			}

		}
	}

	// FilterStart
	function admin_bar_menu() {
		global $wp_admin_bar;
		
		$GetData = $this->get_flit_data( 'admin_bar_menu' );
		
		if( !empty( $GetData["UPFN"] ) ) {
			unset( $GetData["UPFN"] );
			if( is_array( $GetData ) ) {

				$update_data = wp_get_update_data();

				// remove all nodes
				$All_Nodes = $wp_admin_bar->get_nodes();
				foreach( $All_Nodes as $node ) {
					if( $node->id != 'top-secondary' ) {
						$wp_admin_bar->remove_node( $node->id );
					}
				}

				// add nodes
				foreach($GetData as $Boxtype => $allnodes) {
					foreach($allnodes as $depth => $nodes) {
						foreach($nodes as $node) {
							$args = array( "id" => $node["id"] , "title" => stripslashes( $node["title"] ) , "href" => $node["href"] , "parent" => "" , "meta" => array() );
							if( strstr( $node["id"] , 'custom_node' ) ) {
								$args["href"] = $this->val_replace( $node["href"] );
							}
							if( $depth == 'sub' ) {
								$args["parent"] = $node["parent"];
							}
							if( $Boxtype == 'right' && $depth == 'main' ) {
								$args["parent"] = "top-secondary";
							}
							if( !empty( $node["meta"] ) ) {
								$args["meta"] = $node["meta"];
							}
							if( strstr( $args["title"] , '[comment_count]') ) {
								if ( !current_user_can('edit_posts') ) {
									continue;
								} else {
									$args["title"] = str_replace( '[comment_count]' , '<span class="ab-icon"></span><span id="ab-awaiting-mod" class="ab-label awaiting-mod pending-count count-[comment_count]">[comment_count_format]</span>' , $args["title"] );
								}
							}
							if( strstr( $args["title"] , '[update_total]') ) {
								if ( !$update_data['counts']['total'] ) {
									continue;
								} else {
									$args["title"] = str_replace( '[update_total]' , '<span class="ab-icon"></span><span class="ab-label">[update_total_format]</span>' , $args["title"] );
								}
							}
							if( strstr( $args["title"] , '[update_plugins]') ) {
								if ( !$update_data['counts']['plugins'] ) {
									continue;
								} else {
									$args["title"] = str_replace( '[update_plugins]' , '[update_plugins_format]' , $args["title"] );
								}
							}
							if( strstr( $args["title"] , '[update_themes]') ) {
								if ( !$update_data['counts']['themes'] ) {
									continue;
								} else {
									$args["title"] = str_replace( '[update_themes]' , '[update_themes_format]' , $args["title"] );
								}
							}
							$args["title"] = $this->val_replace( $args["title"] );
							
							if( $args["id"] == 'logout' ) {
								$args["href"] = wp_logout_url();
							}
							$wp_admin_bar->add_menu( $args );
						}
					}
				}
			}
		}
	}

	// FilterStart
	function notice_dismiss() {
		$GetData = $this->get_flit_data( 'admin_general' );

		if( !empty( $GetData["UPFN"] ) ) {

			if( !empty( $GetData["notice_update_core"] ) ) {
				add_filter( 'update_footer' , '__return_false' , 20) ;
				add_filter( 'site_transient_update_core' , array( $this , 'notice_update_core' ) );
			}

			if( !empty( $GetData["notice_update_plugin"] ) ) {
				add_filter( 'site_transient_update_plugins' , array( $this , 'notice_update_plugin' ) );
			}
			if( !empty( $GetData["notice_update_theme"] ) ) {
				add_filter( 'site_transient_update_themes' , array( $this , 'notice_update_theme' ) );
			}

		}

	}

	// FilterStart
	function notice_update_core( $site_transient_update_core ) {
		$site_transient_update_core->updates[0]->response = 'latest';
		
		return $site_transient_update_core;
	}

	// FilterStart
	function notice_update_plugin( $site_transient_update_plugins ) {
		unset( $site_transient_update_plugins->response );
		
		return $site_transient_update_plugins;
	}

	// FilterStart
	function notice_update_theme( $site_transient_update_themes ) {
		unset( $site_transient_update_themes->response );
		
		return $site_transient_update_themes;
	}

	// FilterStart
	function remove_tab() {
		$GetData = $this->get_flit_data( 'admin_general' );

		if( !empty( $GetData["UPFN"] ) ) {
			unset( $GetData["UPFN"] );

			if( !empty( $GetData["help_tab"] ) ) {
				$screen = get_current_screen();
				$screen->remove_help_tabs();
			}
	
			if( !empty( $GetData["screen_option_tab"] ) ) {
				add_filter( 'screen_options_show_screen' , '__return_false' );
			}
		}

	}

	// FilterStart
	function admin_footer_text( $text ) {
		$GetData = $this->get_flit_data( 'admin_general' );

		$footer_text = $text;
		if( !empty( $GetData["UPFN"] ) ) {
			unset( $GetData["UPFN"] );

			$footer_text = $this->val_replace( stripslashes( $GetData["footer_text"] ) );
		}

		return $footer_text;
	}

	// FilterStart
	function load_css() {
		$GetData = $this->get_flit_data( 'admin_general' );

		if( !empty( $GetData["UPFN"] ) ) {
			unset( $GetData["UPFN"] );

			if( !empty( $GetData["css"] ) ) {

				$css = strip_tags( $GetData["css"] );
				$css = $this->val_replace( $css );

				wp_enqueue_style( $this->PageSlug , strip_tags( $css ) , array() , $this->Ver );
			}
	
		}

	}

	// FilterStart
	function wp_dashboard_setup() {
		global $wp_meta_boxes;

		$GetData = $this->get_flit_data( 'dashboard' );

		if( !empty( $GetData ) && is_array( $GetData ) ) {
			unset($GetData["UPFN"]);
			$dashboard_widgets = array();
			foreach($wp_meta_boxes["dashboard"] as $ns => $core) {
				if( !empty( $core["core"] ) ) {
					foreach($core["core"] as $id => $val) {
						$dashboard_widgets[$id] = $ns;
					}
				}
			}

			foreach($GetData as $id => $val) {
				if( $id == 'show_welcome_panel' ) {
					$user_id = get_current_user_id();
					if( get_user_meta( $user_id , 'show_welcome_panel' , true ) == true ) {
						update_user_meta( $user_id , 'show_welcome_panel' , 0 );
					}
				} elseif( array_key_exists( $id , $dashboard_widgets ) ){
					remove_meta_box( $id , 'dashboard' , $dashboard_widgets[$id] );
				} elseif( $id == 'metabox_move' ) {
					wp_enqueue_script( 'not-move' , $this->Url . 'js/dashboard/not_move.js' , array( 'jquery' , 'jquery-ui-sortable' , 'dashboard' ) , $this->Ver , true );
				}
			}
		}

	}

	// FilterStart
	function removemetabox() {
		global $wp_meta_boxes;
		global $current_screen;

		$GetData = $this->get_flit_data( 'removemetabox' );
		
		if( !empty( $GetData["UPFN"] ) ) {
			unset( $GetData["UPFN"] );

			if( !empty( $GetData ) && is_array( $GetData ) ) {

				if( $current_screen->base == 'post' ) {

					global $post_type;

					if( !empty( $GetData[$post_type] ) ) {

						$Metaboxes = $wp_meta_boxes[$post_type];
						$Data = $GetData[$post_type];
	
						$Remove_metaboxes = array();
						foreach( $Metaboxes as $context => $meta_box ) {
							foreach( $meta_box as $priority => $box ) {
								foreach( $box as $metabox_id => $b ) {
									if( array_key_exists( $metabox_id , $Data ) ) {
										$Remove_metaboxes[$metabox_id] = array( "context" => $context , "priority" => $priority );
									}
								}
							}
						}

					}

					if( !empty( $Remove_metaboxes ) ) {
						foreach( $Remove_metaboxes as $metabox_id => $box ) {
							remove_meta_box( $metabox_id , $post_type , $box["context"] );
						}
					}
						
				}
			}
		}

	}

	// FilterStart
	function sidemenu() {
		global $menu;
		global $submenu;

		$GetData = $this->get_flit_data( 'sidemenu' );
		$General = $this->get_flit_data( 'admin_general' );

		if( !empty( $GetData["UPFN"] ) ) {
			unset( $GetData["UPFN"] );

			if( !empty( $GetData ) && is_array( $GetData ) && !empty( $GetData["main"] ) ) {
				$SetMain_menu = array();
				$SetMain_submenu = array();
				
				$separator_menu = array();
				foreach( $menu as $key => $ms ) {
					if(  strstr( $ms[2] , 'separator' ) ) {
						$separator_menu = $ms;
						break;
					}
				}
				
				foreach($GetData["main"] as $mm_pos => $mm) {
					if($mm["slug"] == 'separator') {
						$SetMain_menu[] = $separator_menu;
					} else {
						$gm_search = false;
						foreach($menu as $gm_pos => $gm) {
							if($mm["slug"] == $gm[2]) {
								if( strstr( $mm["title"] , '[comment_count]') ) {
									$mm["title"] = str_replace( '[comment_count]' , '<span class="update-plugins count-[comment_count]"><span class="theme-count">[comment_count_format]</span></span>' , $mm["title"] );
								}
								if( strstr( $mm["title"] , '[update_total]') ) {
									$mm["title"] = str_replace( '[update_total]' , '<span class="update-plugins count-[update_total]"><span class="update-count">[update_total_format]</span></span>' , $mm["title"] );
								}
								if( strstr( $mm["title"] , '[update_plugins]') ) {
									$mm["title"] = str_replace( '[update_plugins]' , '<span class="update-plugins count-[update_plugins]"><span class="plugin-count">[update_plugins_format]</span></span>' , $mm["title"] );
								}
								if( strstr( $mm["title"] , '[update_themes]') ) {
									$mm["title"] = str_replace( '[update_themes]' , '<span class="update-plugins count-[update_themes]"><span class="theme-count">[update_themes_format]</span></span>' , $mm["title"] );
								}
								$menu[$gm_pos][0] = $this->val_replace( $mm["title"] );
								$SetMain_menu[] = $menu[$gm_pos];
								$gm_search = true;
								break;
							}
						}
						if( empty( $gm_search ) ) {
							foreach($submenu as $gsm_parent_slug => $v) {
								foreach($v as $gsm_pos => $gsm) {
									if($mm["slug"] == $gsm[2]) {
										
										foreach($menu as $tmp_m) {
											if( $tmp_m[2] == $gsm_parent_slug) {
												$submenu[$gsm_parent_slug][$gsm_pos][4] = $tmp_m[4];
												break;
											}
										}
										if( strstr( $mm["title"] , '[comment_count]') ) {
											$mm["title"] = str_replace( '[comment_count]' , '<span class="update-plugins count-[comment_count]"><span class="theme-count">[comment_count_format]</span></span>' , $mm["title"] );
										}
										if( strstr( $mm["title"] , '[update_total]') ) {
											$mm["title"] = str_replace( '[update_total]' , '<span class="update-plugins count-[update_total]"><span class="update-count">[update_total_format]</span></span>' , $mm["title"] );
										}
										if( strstr( $mm["title"] , '[update_plugins]') ) {
											$mm["title"] = str_replace( '[update_plugins]' , '<span class="update-plugins count-[update_plugins]"><span class="plugin-count">[update_plugins_format]</span></span>' , $mm["title"] );
										}
										if( strstr( $mm["title"] , '[update_themes]') ) {
											$mm["title"] = str_replace( '[update_themes]' , '<span class="update-plugins count-[update_themes]"><span class="theme-count">[update_themes_format]</span></span>' , $mm["title"] );
										}
										$submenu[$gsm_parent_slug][$gsm_pos][0] = $this->val_replace( $mm["title"] );
										$SetMain_menu[] = $submenu[$gsm_parent_slug][$gsm_pos];

									}
								}
							}
						}
					}
				}

				if( !empty( $GetData["sub"] ) ) {
					foreach($GetData["sub"] as $sm_pos => $sm) {
						if($sm["slug"] == 'separator') {
							$SetMain_submenu[$sm["parent_slug"]][] = $separator_menu;
						} else {
							$gm_search = false;
							foreach($menu as $gm_pos => $gm) {
								if($sm["slug"] == $gm[2]) {
									if( strstr( $sm["title"] , '[comment_count]') ) {
										$sm["title"] = str_replace( '[comment_count]' , '<span class="update-plugins count-[comment_count]"><span class="theme-count">[comment_count_format]</span></span>' , $sm["title"] );
									}
									if( strstr( $sm["title"] , '[update_total]') ) {
										$sm["title"] = str_replace( '[update_total]' , '<span class="update-plugins count-[update_total]"><span class="update-count">[update_total_format]</span></span>' , $sm["title"] );
									}
									if( strstr( $sm["title"] , '[update_plugins]') ) {
										$sm["title"] = str_replace( '[update_plugins]' , '<span class="update-plugins count-[update_plugins]"><span class="plugin-count">[update_plugins_format]</span></span>' , $sm["title"] );
									}
									if( strstr( $sm["title"] , '[update_themes]') ) {
										$sm["title"] = str_replace( '[update_themes]' , '<span class="update-plugins count-[update_themes]"><span class="theme-count">[update_themes_format]</span></span>' , $sm["title"] );
									}
									$menu[$gm_pos][0] = $this->val_replace( $sm["title"] );
									$SetMain_submenu[$sm["parent_slug"]][] = $menu[$gm_pos];
									$gm_search = true;
									break;
								}
							}
							if( empty( $gm_search ) ) {
								foreach($submenu as $gsm_parent_slug => $v) {
									foreach($v as $gsm_pos => $gsm) {
										if($sm["slug"] == $gsm[2]) {
	
											if( strstr( $sm["title"] , '[comment_count]') ) {
												$sm["title"] = str_replace( '[comment_count]' , '<span class="update-plugins count-[comment_count]"><span class="theme-count">[comment_count_format]</span></span>' , $sm["title"] );
											}
											if( strstr( $sm["title"] , '[update_total]') ) {
												$sm["title"] = str_replace( '[update_total]' , '<span class="update-plugins count-[update_total]"><span class="update-count">[update_total_format]</span></span>' , $sm["title"] );
											}
											if( strstr( $sm["title"] , '[update_plugins]') ) {
												$sm["title"] = str_replace( '[update_plugins]' , '<span class="update-plugins count-[update_plugins]"><span class="plugin-count">[update_plugins_format]</span></span>' , $sm["title"] );
											}
											if( strstr( $sm["title"] , '[update_themes]') ) {
												$sm["title"] = str_replace( '[update_themes]' , '<span class="update-plugins count-[update_themes]"><span class="theme-count">[update_themes_format]</span></span>' , $sm["title"] );
											}
											$submenu[$gsm_parent_slug][$gsm_pos][0] = $this->val_replace( $sm["title"] );
											$SetMain_submenu[$sm["parent_slug"]][] = $submenu[$gsm_parent_slug][$gsm_pos];
										}
									}
								}
							}
						}
					}
				}

				$menu = $SetMain_menu;
				$submenu = $SetMain_submenu;
				
			} else {
				// empty menu
				$menu = array();
			}
		}
	}

	// FilterStart
	function add_edit_post_change_permalink( $permalink_html ) {
		$GetData = $this->get_flit_data( 'post_add_edit' );

		if( !empty( $GetData["UPFN"] ) ) {
			unset( $GetData["UPFN"] );

			if( !empty( $GetData ) && is_array( $GetData ) ) {
				if( !empty( $GetData["default_permalink"] ) ) {
					if( strpos( $permalink_html , 'change-permalinks' ) ) {

						$permalink_html = preg_replace( "/<span id=\"change-permalinks\">(.*)<\/span>/" , "" , $permalink_html );

					}
				}
			}
		}
		
		return $permalink_html;
	}

	// FilterStart
	function allow_comments() {
		global $current_screen;
		
		$PostAddEdit = $this->get_flit_data( 'post_add_edit' );
		$RemoveMetaBox = $this->get_flit_data( 'removemetabox' );
		
		if( !empty( $PostAddEdit["UPFN"] ) && !empty( $RemoveMetaBox["UPFN"] ) ) {
			if( $current_screen->action == 'add' ) {
				if( !empty( $RemoveMetaBox[$current_screen->id]["commentstatusdiv"] ) && !empty( $PostAddEdit["allow_comments"] ) ) {
					$comment_status = get_option( 'default_comment_status' );
					$comment_status = apply_filters( 'wauc_pre_get_comment_status' , $comment_status );
					if( $comment_status == 'open' ) {
						echo '<input name="comment_status" type="hidden" id="comment_status" value="open" />';
					}
				}
			}
			
		}

	}

	// FilterStart
	function admin_title( $title ) {
		$GetData = $this->get_flit_data( 'admin_general' );

		if( !empty( $GetData["UPFN"] ) ) {
			unset( $GetData["UPFN"] );

			if( !empty( $GetData["title_tag"] ) ) {
				if( strpos( $title , ' WordPress' ) ) {
					$title = str_replace( " &#8212; WordPress" , "" , $title );
				}
			}
		}
		
		return $title;
	}

	// FilterStart
	function nav_menus() {
		$GetData = $this->get_flit_data( 'appearance_menus' );
		if( !empty( $GetData["UPFN"] ) ) {
			unset( $GetData["UPFN"] );

			if( !empty( $GetData["add_new_menu"] ) ) {
				global $wp_version;
				if ( version_compare( $wp_version , '3.6' , '>=' ) ) {
					echo '<style>.wrap > .manage-menus, .locations-row-links .locations-add-menu-link { display: none; }</style>';
				} else {
					echo '<style>.nav-tabs .menu-add-new { display: none; }</style>';
				}
			}
			if( !empty( $GetData["delete_menu"] ) ) {
				echo '<style>.major-publishing-actions .delete-action { display: none; }</style>';
			}

		}
	}

	// FilterStart
	function display_msg() {
		if( !empty( $_GET[$this->MsgQ] ) ) {
			$msg = strip_tags(  $_GET[$this->MsgQ] );
			if( $msg == 'update' or $msg == 'delete' ) {
				$this->Msg .= '<div class="updated"><p><strong>' . __( 'Settings saved.' ) . '</strong></p></div>';
			} elseif( $msg == 'donated' ) {
				$this->Msg .= '<div class="updated"><p><strong>' . __( 'Thank you for your donation.' , $this->ltd_p ) . '</strong></p></div>';
			}
		}
	}

	// FilterStart
	function layout_footer( $text ) {
		$text = '<img src="' . $this->Schema . 'www.gravatar.com/avatar/7e05137c5a859aa987a809190b979ed4?s=18" width="18" /> Plugin developer : <a href="' . $this->AuthorUrl . '?utm_source=use_plugin&utm_medium=footer&utm_content=' . $this->ltd . '&utm_campaign=' . str_replace( '.' , '_' , $this->Ver ) . '" target="_blank">gqevu6bsiz</a>';
		return $text;
	}

	// FilterStart
	function DisplayDonation() {
		$donation = get_option( $this->Record["donate"] );
		if( $this->DonateKey != $donation ) {
			$this->Msg .= '<div class="error"><p><strong>' . __( 'Thank you for considering donate.' , $this->ltd_p ) . '</strong> <a href="' . self_admin_url( 'admin.php?page=' . $this->PageSlug ) . '">' . __( 'Donate' , $this->ltd_p ) . '</a></p></div>';
		}
	}

}
$wauc = new WP_Admin_UI_Customize();


