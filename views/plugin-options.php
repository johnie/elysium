<div class="wrap">

	<h2><?php _e("Medlemsregister", "elysium"); ?> <span style="float: right;"><?php _e("Antal medlemmar: ", "elysium"); elysium()->count_members(); ?></span></h2>

	<form id="elysium_settings" action="" method="post">

		<?php
			// Save plugin options on post.
			if ( urh_is_method( 'post' ) ) {
				_elysium_save_plugin_options();
			}
		?>

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="elysium_plugin_option_admin_email"><?php _e("Administratör", "elysium"); ?></label>
					</th>
					<td>
						<input type="email" name="elysium_plugin_option_admin_email" value="<?php echo elysium_get_plugin_option( 'admin_email', '' ); ?>" placeholder="<?php _e("namn@gmail.com", "elysium"); ?>" class="regular-text">
						<p class=description><?php _e("Denna mail får noteringar om nya medlemsregistreringar.", "elysium"); ?></p>
					</td>
				</tr>
			</tbody>
		</table>

		<?php submit_button(); ?>

	</form>

<div style="display:none;">
<input type="hidden" name="elysium_plugin_option_activation" value="off" />
<input type="checkbox" name="elysium_plugin_option_activation" <?php echo elysium_get_plugin_option( 'activation', "on" ) === "on" ? 'checked' : ''; ?> />
</div>
</div>
<!-- /.wrap -->
