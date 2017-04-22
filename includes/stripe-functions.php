<?php

function get_total() {

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

	$customer_id = get_user_meta( get_current_user_id(), '_stripe_customer_id', true);
	$charges = \Stripe\Charge::all(array('customer' => $customer_id, 'limit' => 50));

	$total = 0;
	if($charges) {
		foreach($charges['data'] as $data) {
			$total += $data['amount'];
		}
	}

	return $total;
}