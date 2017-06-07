<?php

function stripe_settings_setup() {
	add_menu_page('MSR Fundraising Platform', 'MSR Fundraising Platform', 'manage_options', 'fundraising_settings', 'stripe_render_options_page', "dashicons-chart-line", 79);
}

add_action('admin_menu', 'stripe_settings_setup');

function stripe_render_options_page() {
	global $stripe_options;
	?>
	<div class="wrap">
		<h2><?php _e('Fundraising Platform Stripe Settings', 'stripe'); ?></h2>
	
			<?php
	            settings_fields('stripe_settings_group');
				do_settings_sections('stripe_settings_group'); 
				settings_errors();?>

				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e('Test Mode', 'stripe'); ?>
							</th>
							<td>
								<input id="stripe_settings[test_mode]" name="stripe_settings[test_mode]" type="checkbox" value="1" <?php checked(1, $stripe_options['test_mode']); ?> />
								<label class="description" for="stripe_settings[test_mode]"><?php _e('Check this to use the plugin in test mode.', 'stripe'); ?></label>
							</td>
						</tr>
					</tbody>
				</table>

				<h2 class="title"><?php _e('API Keys', 'stripe'); ?></h2>
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e('Live Secret', 'stripe'); ?>
							</th>
							<td>
								<input id="stripe_settings[live_secret_key]" name="stripe_settings[live_secret_key]" type="text" class="regular-text" value="<?php echo $stripe_options['live_secret_key']; ?>"/>
								<label class="description" for="stripe_settings[live_secret_key]"><?php _e('Paste your live secret key.', 'stripe'); ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e('Live Publishable', 'stripe'); ?>
							</th>
							<td>
								<input id="stripe_settings[live_publishable_key]" name="stripe_settings[live_publishable_key]" type="text" class="regular-text" value="<?php echo $stripe_options['live_publishable_key']; ?>"/>
								<label class="description" for="stripe_settings[live_publishable_key]"><?php _e('Paste your live publishable key.', 'stripe'); ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e('Test Secret', 'stripe'); ?>
							</th>
							<td>
								<input id="stripe_settings[test_secret_key]" name="stripe_settings[test_secret_key]" type="text" class="regular-text" value="<?php echo $stripe_options['test_secret_key']; ?>"/>
								<label class="description" for="stripe_settings[test_secret_key]"><?php _e('Paste your test secret key.', 'stripe'); ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e('Test Publishable', 'stripe'); ?>
							</th>
							<td>
								<input id="stripe_settings[test_publishable_key]" name="stripe_settings[test_publishable_key]" class="regular-text" type="text" value="<?php echo $stripe_options['test_publishable_key']; ?>"/>
								<label class="description" for="stripe_settings[test_publishable_key]"><?php _e('Paste your test publishable key.', 'stripe'); ?></label>
							</td>
						</tr>
					</tbody>
				</table>
	<?php
}


function stripe_register_settings() {
	// creates our settings in the options table
	register_setting('stripe_settings_group', 'stripe_settings');
}
add_action('admin_init', 'stripe_register_settings');
