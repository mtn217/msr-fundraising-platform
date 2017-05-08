<?php

function get_campaign_total() {
	global $stripe_options;

	// load the stripe libraries
	require_once(STRIPE_BASE_DIR . '/lib/latest/init.php');	

	// check if we are using test mode
	if(isset($stripe_options['test_mode']) && $stripe_options['test_mode']) {
		$secret_key = $stripe_options['test_secret_key'];
	} else {
		$secret_key = $stripe_options['live_secret_key'];
	}

	\Stripe\Stripe::setApiKey($secret_key);

	$campaign_funds = 0;

	$all_charges = \Stripe\Charge::all(array('limit' => 100));

	$campaign_id = get_the_ID();
	if($all_charges) {
		foreach ($all_charges['data'] as $charge) {
			if($charge['description'] == $campaign_id) {
				$campaign_funds += $charge['amount'];
			} else {
				$fundraiser_id = $charge['description'];
				$the_campaign = get_post_meta($fundraiser_id, 'fundraiser-campaign', true);
				if($the_campaign == $campaign_id) {
					$campaign_funds += $charge['amount'];
				}
			}
		}
	}

	$campaign_funds = $campaign_funds / 100;
	return $campaign_funds;
}