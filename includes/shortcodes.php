<?php
function stripe_payment_form() {
 
	global $stripe_options;
	$post_id = url_to_postid(get_permalink());

	ob_start();

	?>
	<h1>Contribute</h1>
	<div id="payment-form">
		<form action="process-payment.php" method="POST" id="stripe-payment-form">
			<h2><?php echo get_the_title($post_id); ?></h2>
			<div class="amount">
				<input data-validation="number" type="text" autocomplete="off" placeholder="$20" class="user-amount">
			</div>
			<!-- If left blank and must be greater than 1 and must only be numbers -->
			
			<div class="form-row">
				<label><?php _e('Email*', 'stripe'); ?></label>
				<input data-validation="email" type="text" size="20" autocomplete="off" class="email" value="<?php  
					if(is_user_logged_in()) {
						$user = get_userdata(get_current_user_id());
						echo $user->user_email;
					}
				?>"/>
			</div>
			<!-- Must be an email -->
			
			<div class="form-row">
				<label><?php _e('Name*', 'stripe'); ?></label>
				<input data-validation="alphanumeric" data-validation-allowing=" " type="text" size="20" autocomplete="off" class="name" value="<?php  
					if(is_user_logged_in()) {
						$user = get_userdata(get_current_user_id());
						$name = $user->first_name . " " . $user->last_name;
						echo $name;
					}
				?>"/>
			</div>
			<!-- Letters and spaces only -->
			
			<div class="form-row">
				<label><?php _e('Zipcode*', 'stripe'); ?></label>
				<input data-validation="number" type="text" size="20" autocomplete="off" class="zipcode"/>
			</div>
			<!-- Limit to 5 numbers only -->

			<div class="form-row">
				<label><?php _e('Card No*', 'stripe'); ?></label>
				<input type="text" size="20" autocomplete="off" class="card-number"/>
			</div>
			<!-- Limit to 16 numbers only -->

			<div class="form-row">
				
			</div>
			<!-- Limit to 3 numbers only -->

			<div class="form-row">
				<div class="expiration group">
					<label><?php _e('Expiration Date*', 'stripe'); ?></label>
					<input type="text" size="2" class="card-expiry-month" placeholder="MM"/>
					<!-- Limit to 2 numbers only -->
					<input type="text" size="4" class="card-expiry-year" placeholder="YYYY"/>
					<!-- Limit to 4 numbers only -->
				</div>
				<div class="ccv group">
					<label><?php _e('CVC*', 'stripe'); ?></label>
					<input type="text" size="4" autocomplete="off" class="card-cvc"/>
				</div>
			</div>
			<?php
				$id = url_to_postid(get_permalink());
				if(is_page($id)) {
					// Logic for general contributions ?>
					<input class="anon" type="hidden" name="display-name" value="anonymous" checked>
					<?php if(is_user_logged_in()) { ?>
						<div class="form-row">
							<input type="checkbox" id="recurring"/><span><?php _e('Recurring monthly payment', 'stripe'); ?></span>
						</div>
					<?php } else { ?>
						<div class="form-row">
							<label><?php _e('Sign in to set up recurring contribution.', 'stripe'); ?></label>
						</div>
					<?php }
				} else {
					// Logic for campaigns/fundraisers
					if(is_user_logged_in()) { ?>
						<div class="form-row">
							<input type="radio" name="display-name" value="regular-name"> <?php echo $name; ?>
							<input class="anon" type="radio" name="display-name" value="anonymous">Anonymous
						</div>
					<?php } ?>
			<?php } ?>
			<input type="hidden" class="action" value="stripe"/>
			<input type="hidden" class="post_id" value="<?php 
				$id = url_to_postid(get_permalink());
				if(is_page($id)) {
					echo "general";
				} else {
					echo $id;
				}
			?>"/>
			<input type="hidden" class="stripe_nonce" value="<?php echo wp_create_nonce('stripe-nonce'); ?>"/>
			<button type="submit" id="stripe-submit"><?php _e('Submit Payment', 'stripe'); ?></button>
		</form>
		<div class="payment-errors"></div>
	</div>
		<?php

	return ob_get_clean();

}  

function stripe_campaign_total() {
	ob_start();
	$total_amount = get_campaign_total();
	echo '$' . $total_amount;
	return ob_get_clean();
}

add_shortcode('payment', 'stripe_payment_form');
add_shortcode('campaign', 'stripe_campaign_total');