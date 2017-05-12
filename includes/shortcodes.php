<?php
function pippin_stripe_payment_form() {
 
	global $stripe_options;
	$post_id = url_to_postid(get_permalink());
	$user_name = "";
	if(is_user_logged_in()) {
		$user = get_userdata(get_current_user_id());
		$user_name = $user->first_name . " " . $user->last_name;
	}

	ob_start();

	?>
	<h1>Contribute</h1>
	<div id="payment-form">
		<form action="process-payment.php" method="POST" id="stripe-payment-form">
			<h2><?php echo get_the_title($post_id); ?></h2>
			<div class="amount">
				<?php echo dollar_svg(); ?>
				<input data-validation="number" type="text" autocomplete="off" class="user-amount">
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
				<input data-validation="alphanumeric" data-validation-allowing=" " type="text" size="20" autocomplete="off" class="name" value="<?php echo $user_name; ?>"/>
			</div>
			<!-- Letters and spaces only -->
			
			<div class="form-row">
				<label><?php _e('Zipcode*', 'pippin_stripe'); ?></label>
				<input data-validation="number" type="text" size="20" autocomplete="off" class="zipcode"/>
			</div>
			<!-- Limit to 5 numbers only -->

			<div class="form-row">
				<label><?php _e('Card No*', 'pippin_stripe'); ?></label>
				<input type="text" size="20" autocomplete="off" class="card-number"/>
			</div>
			<!-- Limit to 16 numbers only -->

			<div class="form-row">
				
			</div>
			<!-- Limit to 3 numbers only -->

			<div class="form-row">
				<div class="expiration group">
					<label><?php _e('Expiration Date*', 'pippin_stripe'); ?></label>
					<input type="text" size="2" class="card-expiry-month" placeholder="MM"/>
					<!-- Limit to 2 numbers only -->
					<input type="text" size="4" class="card-expiry-year" placeholder="YYYY"/>
					<!-- Limit to 4 numbers only -->
				</div>
				<div class="ccv group">
					<label><?php _e('CVC*', 'pippin_stripe'); ?></label>
					<input type="text" size="4" autocomplete="off" class="card-cvc"/>
				</div>
			</div>

			<div class="appearance">
				<h2>Contribution Appearance</h2>
				<?php echo info_svg(); ?>
				<div class="name-input">
					<input type="radio" name="appearance" id="user-name" checked value="<?php echo get_current_user_id(); ?>"/>
					<label for="user-name"><input type="text" autocomplete="off" class="name" value="<?php echo $user_name; ?>"/></label>
				</div>
				<div class="anonymous">
					<input type="radio" id="anonym" name="appearance" value="anonymous"/><label for="anonym">Anonymous</label>
				</div>
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
			<input type="hidden" class="post_id" value="<?php echo $post_id; ?>"/>
			<input type="hidden" class="stripe_nonce" value="<?php echo wp_create_nonce('stripe-nonce'); ?>"/>
			<div class="buttons">
				<button type="submit" id="stripe-submit"><?php _e('Submit', 'pippin_stripe'); ?></button>
			</div>
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