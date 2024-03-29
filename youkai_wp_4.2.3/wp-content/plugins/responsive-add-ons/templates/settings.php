<?php
/**
 * Settings for plugin
 *
 * Uses Wordpress settings api to create plugin options
 *
 * Please do not edit this file. This file is part of the CyberChimps Framework and all modifications
 * should be made in a child theme.
 *
 * @category CyberChimps Framework
 * @package  Framework
 * @since    1.0
 * @author   CyberChimps
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     http://www.cyberchimps.com/
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
	<h2>Responsive Settings</h2>

	<form method="POST" action="options.php">
		<?php settings_fields( 'responsive_addons' );
		do_settings_sections( 'responsive_addons_options' );

		$options = get_option( 'responsive_addons_options' );
		?>
		<h3 class="subheading">Webmaster Settings</h3>
		<table class="form-table">
			<tr>
				<th><?php _e( 'Google Site Verification', 'responsive-addons' ) ?></th>
				<td><input id="<?php echo esc_attr( 'responsive_addons_options[google_site_verification]' ); ?>" class="regular-text" type="text" name="<?php echo esc_attr(
						'responsive_addons_options[google_site_verification]' ); ?>"
				           value="<?php echo ( !empty( $options['google_site_verification'] ) ) ? esc_html( $options['google_site_verification'] ) : ''; ?>"/>
					<label class="description" for="<?php echo esc_attr( 'responsive_addons_options[google_site_verification]' ); ?>"><?php _e( 'Enter your Google ID number only',
					                                                                                                                            'responsive-addons' ) ?></label></td>
			</tr>

			<tr>
				<th><?php _e( 'Bing Site Verification', 'responsive-addons' ) ?></th>
				<td><input id="<?php echo esc_attr( 'responsive_addons_options[bing_site_verification]' ); ?>" class="regular-text" type="text" name="<?php echo esc_attr(
						'responsive_addons_options[bing_site_verification]' ); ?>"
				           value="<?php echo ( !empty( $options['bing_site_verification'] ) ) ? esc_html( $options['bing_site_verification'] ) : ''; ?>"/>
					<label class="description" for="<?php echo esc_attr( 'responsive_addons_options[bing_site_verification]' ); ?>"><?php _e( 'Enter your Bing ID number only',
					                                                                                                                          'responsive-addons' ) ?></label></td>
			</tr>

			<tr>
				<th><?php _e( 'Yahoo Site Verification', 'responsive-addons' ) ?></th>
				<td><input id="<?php echo esc_attr( 'responsive_addons_options[yahoo_site_verification]' ); ?>" class="regular-text" type="text" name="<?php echo esc_attr(
						'responsive_addons_options[yahoo_site_verification]' ); ?>"
				           value="<?php echo ( !empty( $options['yahoo_site_verification'] ) ) ? esc_html( $options['yahoo_site_verification'] ) : ''; ?>"/>
					<label class="description" for="<?php echo esc_attr( 'responsive_addons_options[yahoo_site_verification]' ); ?>"><?php _e( 'Enter your Yahoo ID number only',
					                                                                                                                           'responsive-addons' ) ?></label></td>
			</tr>

			<tr>
				<th>
					<?php _e( 'Site Statistics Tracker', 'responsive-addons' ); ?>
					<br/><a style="margin:5px;" class="resp-addon-forum button" href="http://cyberchimps.com/forum/free/responsive/">Forum</a>
					<a style="margin:5px;" class="resp-addon-guide button" href="http://cyberchimps.com/guide/responsive-add-ons/">Guide</a>
				</th>
				<td><textarea id="<?php echo esc_attr( 'responsive_addons_options[site_statistics_tracker]' ); ?>" class="large-text" cols="50" rows="15" name="<?php echo esc_attr(
						'responsive_addons_options[site_statistics_tracker]' ); ?>"><?php echo ( !empty( $options['site_statistics_tracker'] ) ) ? esc_html( $options['site_statistics_tracker'] ) : ''; ?></textarea>
					<label class="description" for="<?php echo esc_attr( 'responsive_addons_options[site_statistics_tracker]' ); ?>">
						<?php _e( 'Google Analytics, StatCounter, any other or all of them.', 'responsive-addons' ) ?>
					</label></td>
			</tr>


		</table>
		<?php submit_button(); ?>
	</form>
</div>