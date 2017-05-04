<?php

function pippin_load_stripe_scripts() {

	global $stripe_options;

	// check to see if we are in test mode
	if(isset($stripe_options['test_mode']) && $stripe_options['test_mode']) {
		$publishable = $stripe_options['test_publishable_key'];
	} else {
		$publishable = $stripe_options['live_publishable_key'];
	}

	wp_enqueue_script('jquery');
	wp_enqueue_script('stripe', 'https://js.stripe.com/v2/');
	wp_enqueue_script('stripe-processing', STRIPE_BASE_URL . 'includes/js/stripe-processing.js');
	wp_enqueue_script('jquery-validate', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js');
	wp_localize_script('stripe-processing', 'stripe_vars', array(
			'publishable_key' => $publishable,
		)
	);
}
add_action('wp_enqueue_scripts', 'pippin_load_stripe_scripts');
