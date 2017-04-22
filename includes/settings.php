<?php

function stripe_settings_setup() {
	add_menu_page('MSR Donation Platform', 'MSR Donation Platform', 'manage_options', 'fundraising_settings', 'stripe_render_options_page', "dashicons-chart-line", 79);
	//add_options_page('Stripe Settings', 'Stripe Settings', 'manage_options', 'stripe-settings', 'pippin_stripe_render_options_page');
}

add_action('admin_menu', 'stripe_settings_setup');

function stripe_render_options_page() {
	global $stripe_options;
	?>
	<div class="wrap">
<!-- 		<h2><?php _e('Stripe Settings', 'stripe'); ?></h2>  -->		
		<h2><?php _e('Fundraising Platform Settings', 'stripe'); ?></h2>

 		<?php
		    $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'dashboard';
		?>
		<h2 class="nav-tab-wrapper">
			<a href="?page=fundraising_settings&tab=dashboard" class="nav-tab <?php echo $active_tab == 'dashboard' ? 'nav-tab-active' : ''; ?>">Dashboard</a>
		    <a href="?page=fundraising_settings&tab=fund_management" class="nav-tab <?php echo $active_tab == 'fund_management' ? 'nav-tab-active' : ''; ?>">Fundraiser Management</a>
		    <a href="?page=fundraising_settings&tab=stripe_settings" class="nav-tab <?php echo $active_tab == 'stripe_settings' ? 'nav-tab-active' : ''; ?>">Stripe Settings</a>
		</h2>



		<form method="post" action="options.php">
			<?php
	        if( $active_tab == 'stripe_settings' ) {
	            settings_fields('stripe_settings_group');
				do_settings_sections('stripe_settings_group'); 
				settings_errors();?>

				<h1 class="title"><?php _e('Stripe Settings', 'stripe'); ?></h1>
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

				<table class="form-table">
					<tbody>
						<tr valign="top">	
							<th scope="row" valign="top">
								<?php _e('Allow Recurring', 'stripe'); ?>
							</th>
							<td>
								<input id="stripe_settings[recurring]" name="stripe_settings[recurring]" type="checkbox" value="1" <?php checked(1, $stripe_options['recurring']); ?> />
								<label class="description" for="stripe_settings[recurring]"><?php _e('Check this to allow users to setup recurring payments.', 'stripe'); ?></label>
							</td>
						</tr>
					</tbody>

					<tr valign="top">	
						<th scope="row" valign="top">
							<?php _e('Plan ID', 'stripe'); ?>
						</th>
						<td>
							<input id="stripe_settings[plan_id]" name="stripe_settings[plan_id]" class="regular-text" type="text" value="<?php echo $stripe_options['plan_id']; ?>"/>
							<label class="description" for="stripe_settings[plan_id]"><?php _e('Enter the ID of the recurring plan you have created in Stripe', 'stripe'); ?></label>
						</td>
					</tr>	
				</table>
				
		<!-- 		<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Save Options', 'mfwp_domain'); ?>" />
				</p> -->

	        <?php } else if ($active_tab == 'fund_management') { 
	        	settings_fields('fund_management_group');
				do_settings_sections('fund_management_group'); 
				settings_errors(); ?>

	        	<h1 class="title"><?php _e('Fundraising Management', 'management'); ?></h1>
	        	<p>***Still in progress.***</p>
	            <p>Include:</p>
            	<ul>
					<li>Table filled with pending fundraisers request to be approved or rejected</li>
				  	<li>List of people who have contributed to campaigns/fundraisers and their contact information</li>
				</ul>

	        <?php } else { 
	        	settings_fields('dashboard_group');
				do_settings_sections('dashboard_group'); 
				settings_errors(); ?>


	            <h1 class="title"><?php _e('Dashboard', 'dashboard'); ?></h1>
	            <p>***Still in progress.***</p>
	            <p>Include:</p>
            	<ul>
					<li>Amber's data analysis on donations made so far that can be organized into year, month, day</li>
				  	<li>Current Campaigns and their progress (amount raised, page visits, etc.)</li>
				</ul>
	        <?php }
	        submit_button();
			         
		    ?>

		</form>


	<?php
}


function stripe_register_settings() {
	// creates our settings in the options table
	register_setting('stripe_settings_group', 'stripe_settings');
}
add_action('admin_init', 'stripe_register_settings');
