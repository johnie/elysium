<div class="wrap">

	<h2><?php echo elysium()->name; ?></h2>

	<p><?php echo elysium()->description; ?></p>

	<form id="elysium_settings" action="" method="post">

		<?php
			// Save plugin options on post.
			if ( elysium_is_method( 'post' ) ) {
				_elysium_save_plugin_options();
			}
		?>

		<table class="form-table">
			<tbody>
				<tr>
					<th class="row">
						<label for="elysium_plugin_option_activation">Aktivera</label>
					</th>
					<td>
						<input type="hidden" name="elysium_plugin_option_activation" value="off" />
						<input type="checkbox" name="elysium_plugin_option_activation" <?php echo elysium_get_plugin_option( 'activation', "on" ) === "on" ? 'checked' : ''; ?> />
					</td>
				</tr>
			</tbody>
		</table>

		<?php submit_button(); ?>

	</form>
</div>
<!-- /.wrap -->
