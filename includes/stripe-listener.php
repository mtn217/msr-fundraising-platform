<?php
 
function pippin_stripe_event_listener() {
	if(isset($_GET['stripe-listener']) && $_GET['stripe-listener'] == 'stripe') {
		global $stripe_options;

		require_once(STRIPE_BASE_DIR . '/lib/latest/init.php'); 
		
		if(isset($stripe_options['test_mode']) && $stripe_options['test_mode']) {
			$secret_key = $stripe_options['test_secret_key'];
		} else {
			$secret_key = $stripe_options['live_secret_key'];
		}
 
		\Stripe\Stripe::setApiKey($secret_key);

		// retrieve the request's body and parse it as JSON
		$body = @file_get_contents('php://input');
 
		// grab the event information
		$event_json = json_decode($body);
 
		// this will be used to retrieve the event from Stripe
		// for extra security, retrieve from the Stripe API
		$event_id = $event_json->id;
 		var_dump($event_id);
		if(isset($event_json->id)) {
			var_dump($event_id);
			try {
 
				// to verify this is a real event, we re-retrieve the event from Stripe 
				$event = \Stripe\Event::retrieve($event_id);
 				
 				$invoice = $event->data->object;
 
				// successful payment, both one time and recurring payments
				if($event->type == 'charge.succeeded') {
					echo "Successful Event";
					// retrieve the payer's information
					$customer = \Stripe\Customer::retrieve($invoice->customer);
					$email = $customer->email;
 
					$amount = $invoice->amount / 100; // amount comes in as amount in cents, so we need to convert to dollars
 
					$subject = __('Payment Receipt', 'pippin_stripe');
					$headers = 'From: "' . html_entity_decode(get_bloginfo('name')) . '" <' . get_bloginfo('admin_email') . '>';
					$message = "Hello " . $customer_name . "\n\n";
					$message .= "You have successfully made a payment of " . $amount . "\n\n";
					$message .= "Thank you.";
 
					wp_mail($email, $subject, $message, $headers);
					echo "Email Sent";
				}
 
			} catch (Exception $e) {
				// something failed, perhaps log a notice or email the site admin
			}
		}

	}
}
add_action('init', 'pippin_stripe_event_listener');