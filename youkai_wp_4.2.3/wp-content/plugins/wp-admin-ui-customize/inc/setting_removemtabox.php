<?php

$Data = $this->get_data( 'removemetabox' );
$Metaboxes = $this->get_data( "regist_metabox" );
$CustomPosts = $this->get_custom_posts();

// include js css
$ReadedJs = array( 'jquery' , 'jquery-ui-sortable' );
wp_enqueue_script( $this->PageSlug ,  $this->Url . $this->PluginSlug . '.js', $ReadedJs , $this->Ver );
wp_enqueue_style( $this->PageSlug , $this->Url . $this->PluginSlug . '.css', array() , $this->Ver );

?>

<div class="wrap">
	<div class="icon32" id="icon-tools"></div>
	<?php echo $this->Msg; ?>
	<h2><?php _e( 'Remove meta box' , $this->ltd ); ?></h2>
	<p><?php _e( 'Please update or add a "post" and a "page" to load the available meta boxes.' , $this->ltd ); ?></p>

	<h3 id="wauc-apply-user-roles"><?php echo $this->get_apply_roles(); ?></h3>

	<form id="wauc_setting_removemtabox" class="wauc_form" method="post" action="<?php echo remove_query_arg( 'wauc_msg' , add_query_arg( array( 'page' => $this->PageSlug ) ) ); ?>">
		<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y" />
		<?php wp_nonce_field( $this->Nonces["value"] , $this->Nonces["field"] ); ?>
		<input type="hidden" name="record_field" value="removemetabox" />

		<div id="poststuff">

			<div id="post-body" class="metabox-holder columns-1">

				<div id="postbox-container-1" class="postbox-container">
					<div id="built_in">

						<div class="postbox">
							<div class="handlediv" title="Click to toggle"><br></div>
							<h3 class="hndle"><span><?php _e( 'Post' ); ?></span></h3>
							<div class="inside">
			
								<?php if( empty( $Metaboxes["metaboxes"]["post"] ) ) : ?>
			
									<p><?php _e( 'Could not read the meta box.' , $this->ltd ); ?></p>
									<p><?php echo sprintf( __( 'Meta boxes will be loaded automatically when you Edit %s.' , $this->ltd ) , __( 'Post' ) ); ?></p>
								
								<?php else: ?>
								
									<table class="form-table">
										<tbody>
											<?php foreach( $Metaboxes["metaboxes"]["post"] as $context => $meta_box ) : ?>
												<?php foreach( $meta_box as $priority => $box ) : ?>
													<?php foreach( $box as $metabox_id => $metabox_title ) : ?>
														<?php if( $metabox_id != 'submitdiv' ) : ?>
															<tr>
																<th><?php echo $metabox_title; ?></th>
																<td>
																	<?php $Checked = ''; ?>
																	<?php if( !empty( $Data["post"][$metabox_id] ) ) : $Checked = 'checked="checked"'; endif; ?>
																	<label><input type="checkbox" name="data[post][<?php echo $metabox_id; ?>]" value="1" <?php echo $Checked; ?> /> <?php _e ( 'Hide' ); ?></label>
																	<?php if( $metabox_id == 'commentstatusdiv' ) : ?>
																		<p class="description"><?php _e( 'Notice: If hide the Discussion on metabox, comments does not display of Add New Post on apply user role.' , $this->ltd ); ?></p>
																		<p><img src="<?php echo $this->Url; ?>images/discussion_allow_comments.png" /></p>
																		<p><a href="<?php echo admin_url( 'admin.php?page=' . $this->PageSlug . '_post_add_edit_screen' ); ?>"><?php _e( 'Please set from here if you want to view the comments on screen.' , $this->ltd ); ?></a></p>
																	<?php endif; ?>
																</td>
															</tr>
														<?php endif; ?>
													<?php endforeach; ?>
												<?php endforeach; ?>
											<?php endforeach; ?>
										</tbody>
									</table>
			
			
								<?php endif; ?>
							</div>
						</div>

						<div class="postbox">
							<div class="handlediv" title="Click to toggle"><br></div>
							<h3 class="hndle"><span><?php _e( 'Page' ); ?></span></h3>
							<div class="inside">
			
								<?php if( empty( $Metaboxes["metaboxes"]["page"] ) ) : ?>
			
									<p><?php _e( 'Could not read the meta box.' , $this->ltd ); ?></p>
									<p><?php echo sprintf( __( 'Meta boxes will be loaded automatically when you Edit %s.' , $this->ltd ) , __( 'Page' ) ); ?></p>
								
								<?php else: ?>
			
									<table class="form-table">
										<tbody>
											<?php foreach( $Metaboxes["metaboxes"]["page"] as $context => $meta_box ) : ?>
												<?php foreach( $meta_box as $priority => $box ) : ?>
													<?php foreach( $box as $metabox_id => $metabox_title ) : ?>
														<?php if( $metabox_id != 'submitdiv' ) : ?>
															<tr>
																<th><?php echo $metabox_title; ?></th>
																<td>
																	<?php $Checked = ''; ?>
																	<?php if( !empty( $Data["page"][$metabox_id] ) ) : $Checked = 'checked="checked"'; endif; ?>
																	<label><input type="checkbox" name="data[page][<?php echo $metabox_id; ?>]" value="1" <?php echo $Checked; ?> /> <?php _e ( 'Hide' ); ?></label>
																	<?php if( $metabox_id == 'commentstatusdiv' ) : ?>
																		<p class="description"><?php _e( 'Notice: If hide the Discussion on metabox, comments does not display of Add New Post on apply user role.' , $this->ltd ); ?></p>
																		<p><img src="<?php echo $this->Url; ?>images/discussion_allow_comments.png" /></p>
																		<p><a href="<?php echo admin_url( 'admin.php?page=' . $this->PageSlug . '_post_add_edit_screen' ); ?>"><?php _e( 'Please set from here if you want to view the comments on screen.' , $this->ltd ); ?></a></p>
																	<?php endif; ?>
																</td>
															</tr>
														<?php endif; ?>
													<?php endforeach; ?>
												<?php endforeach; ?>
											<?php endforeach; ?>
										</tbody>
									</table>
			
								<?php endif; ?>
			
							</div>
						</div>

					</div>
				</div>
				
				<?php if ( !empty( $CustomPosts ) ) : ?>
				
				<div id="postbox-container-2" class="postbox-container">
					<div id="custom_post">
						
						<?php foreach( $CustomPosts as $post_name => $cpt ) : ?>
						<div class="postbox">
							<div class="handlediv" title="Click to toggle"><br></div>
							<h3 class="hndle"><span><?php echo strip_tags( $cpt->labels->name ); ?></span></h3>
							<div class="inside">
			
								<?php if( empty( $Metaboxes["metaboxes"][$post_name] ) ) : ?>
			
									<p><?php _e( 'Could not read the meta box.' , $this->ltd ); ?></p>
									<p><?php echo sprintf( __( 'Meta boxes will be loaded automatically when you Edit %s.' , $this->ltd ) , strip_tags( $cpt->labels->name ) ); ?></p>
								
								<?php else: ?>
			
									<table class="form-table">
										<tbody>
											<?php foreach( $Metaboxes["metaboxes"][$post_name] as $context => $meta_box ) : ?>
												<?php foreach( $meta_box as $priority => $box ) : ?>
													<?php foreach( $box as $metabox_id => $metabox_title ) : ?>
														<?php if( !empty( $metabox_id ) && $metabox_id != 'submitdiv' ) : ?>
															<tr>
																<th><?php echo $metabox_title; ?></th>
																<td>
																	<?php $Checked = ''; ?>
																	<?php if( !empty( $Data[$post_name][$metabox_id] ) ) : $Checked = 'checked="checked"'; endif; ?>
																	<label><input type="checkbox" name="data[<?php echo $post_name; ?>][<?php echo $metabox_id; ?>]" value="1" <?php echo $Checked; ?> /> <?php _e ( 'Hide' ); ?></label>
																</td>
															</tr>
														<?php endif; ?>
													<?php endforeach; ?>
												<?php endforeach; ?>
											<?php endforeach; ?>
										</tbody>
									</table>
			
								<?php endif; ?>
			
							</div>
						</div>
						<?php endforeach; ?>

					</div>
				</div>
				
				<?php endif; ?>
				
				<br class="clear">

			</div>

		</div>

		<p class="submit">
			<input type="submit" class="button-primary" name="update" value="<?php _e( 'Save' ); ?>" />
		</p>

		<p class="submit reset">
			<span class="description"><?php _e( 'Reset all settings?' , $this->ltd ); ?></span>
			<input type="submit" class="button-secondary" name="reset" value="<?php _e('Reset'); ?>" />
		</p>

	</form>

</div>
