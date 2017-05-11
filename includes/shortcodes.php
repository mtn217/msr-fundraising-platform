<?php
function pippin_stripe_payment_form() {
 
	global $stripe_options;

	ob_start();

	?>
	<div id="payment-form">
		<form action="process-payment.php" method="POST" id="stripe-payment-form">
			<div class="form-row">
				<label><?php _e('Amount*', 'pippin_stripe'); ?></label>
				<input data-validation="number" type="text" size="20" autocomplete="off" placeholder="$20" class="user-amount">
			</div>
			<!-- If left blank and must be greater than 1 and must only be numbers -->
			
			<div class="form-row">
				<label><?php _e('Email*', 'pippin_stripe'); ?></label>
				<input data-validation="email" type="text" size="20" autocomplete="off" class="email" value="<?php  
					if(is_user_logged_in()) {
						$user = get_userdata(get_current_user_id());
						echo $user->user_email;
					}
				?>"/>
			</div>
			<!-- Must be an email -->
			
			<div class="form-row">
				<label><?php _e('Name*', 'pippin_stripe'); ?></label>
				<input data-validation="alphanumeric" data-validation-allowing=" " type="text" size="20" autocomplete="off" class="name" value="<?php  
					if(is_user_logged_in()) {
						$user = get_userdata(get_current_user_id());
						echo $user->first_name . " " . $user->last_name;
					}
				?>"/>
			</div>
			<!-- Letters and spaces only -->
			
			<div class="form-row">
				<label><?php _e('Street', 'pippin_stripe'); ?></label>
				<input type="text" size="20" autocomplete="off" class="address"/>
			</div>

			<div class="form-row">
				<label><?php _e('City', 'pippin_stripe'); ?></label>
				<input type="text" size="20" autocomplete="off" class="city"/>
			</div>

			<div class="form-row">
				<label><?php _e('Zipcode*', 'pippin_stripe'); ?></label>
				<input data-validation="number" type="text" size="20" autocomplete="off" class="zipcode"/>
			</div>
			<!-- Limit to 5 numbers only -->

			<div class="form-row">
				<label><?php _e('Card Number*', 'pippin_stripe'); ?></label>
				<input type="text" size="20" autocomplete="off" class="card-number"/>
			</div>
			<!-- Limit to 16 numbers only -->

			<div class="form-row">
				<label><?php _e('CVC*', 'pippin_stripe'); ?></label>
				<input type="text" size="4" autocomplete="off" class="card-cvc"/>
			</div>
			<!-- Limit to 3 numbers only -->

			<div class="form-row">
				<label><?php _e('Expiration (MM/YYYY)*', 'pippin_stripe'); ?></label>
				<input type="text" size="2" class="card-expiry-month"/>
				<!-- Limit to 2 numbers only -->
				<span> / </span>
				<input type="text" size="4" class="card-expiry-year"/>
				<!-- Limit to 4 numbers only -->
			</div>

			<?php if(is_user_logged_in() && get_post_type() == "campaign") { ?>
			<div class="form-row">
				<input type="checkbox" id="recurring"/><span><?php _e('Recurring monthly payment', 'pippin_stripe'); ?></span>
			</div>
			<?php }  elseif(get_post_type() == "campaign") { ?>
 			<div class="form-row">
				<label><?php _e('Sign In if you would like to set up recurring contribution (create link here)', 'pippin_stripe'); ?></label>
			</div> 
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
			<button type="submit" id="stripe-submit"><?php _e('Submit Payment', 'pippin_stripe'); ?></button>
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

add_shortcode('payment', 'pippin_stripe_payment_form');
add_shortcode('campaign', 'stripe_campaign_total');