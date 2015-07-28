<?php

$Data = $this->get_data( 'user_role' );
$UserRoles = $this->get_user_role();

// include js css
$ReadedJs = array( 'jquery' , 'jquery-ui-sortable' );
wp_enqueue_script( $this->PageSlug ,  $this->Url . $this->PluginSlug . '.js', $ReadedJs , $this->Ver );
wp_enqueue_style( $this->PageSlug , $this->Url . $this->PluginSlug . '.css', array() , $this->Ver );

?>
<div class="wrap">
	<div class="icon32" id="icon-tools"></div>
	<?php echo $this->Msg; ?>
	<h2><?php _e( 'Reset User Roles' , $this->ltd ); ?></h2>
	<p>&nbsp;</p>

	<form id="wauc_reset_userrole" class="wauc_form" method="post" action="<?php echo remove_query_arg( 'wauc_msg' , add_query_arg( array( 'page' => $this->PageSlug ) ) ); ?>">
		<input type="hidden" name="<?php echo $this->UPFN; ?>" value="Y" />
		<?php wp_nonce_field( $this->Nonces["value"] , $this->Nonces["field"] ); ?>
		<input type="hidden" name="record_field" value="user_role" />

		<h3><?php _e( 'Applied user roles' , $this->ltd ); ?></h3>
		<ul>
			<?php foreach( $Data as $key => $val ) : ?>
				<?php if( !empty( $UserRoles[$key] ) ): ?>
					<li><?php echo $UserRoles[$key]["label"]; ?></li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
		<br />

		<p><?php _e( 'You want to reset the user roles?' , $this->ltd ); ?></p>
		<p class="submit">
			<input type="submit" class="button-secondary" name="reset" value="<?php _e('Reset'); ?>" />
		</p>

	</form>

</div>
