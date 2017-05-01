<?php
function pippin_stripe_payment_form($atts, $content = null) {
 
	global $stripe_options;

	ob_start();

	?>
	<div id="payment-form">
		<form action="process-payment.php" method="POST" id="stripe-payment-form">
			<div class="form-row">
				<label><?php _e('Amount*', 'pippin_stripe'); ?></label>
				<input type="text" size="20" autocomplete="off" placeholder="$20" id="user-amount"/>
			</div>

			<div class="form-row">
				<label><?php _e('Email*', 'pippin_stripe'); ?></label>
				<input type="text" size="20" autocomplete="off" class="email"/>
			</div>

			<div class="form-row">
				<label><?php _e('Name*', 'pippin_stripe'); ?></label>
				<input type="text" size="20" autocomplete="off" name="name"/>
			</div>

			<div class="form-row">
				<label><?php _e('Street', 'pippin_stripe'); ?></label>
				<input type="text" size="20" autocomplete="off" name="address"/>
			</div>

			<div class="form-row">
				<label><?php _e('City', 'pippin_stripe'); ?></label>
				<input type="text" size="20" autocomplete="off" name="city"/>
			</div>

			<div class="form-row">
				<label><?php _e('Zipcode*', 'pippin_stripe'); ?></label>
				<input type="text" size="20" autocomplete="off" name="zipcode"/>
			</div>

			<div class="form-row">
				<label><?php _e('Card Number*', 'pippin_stripe'); ?></label>
				<input type="text" size="20" autocomplete="off" class="card-number"/>
			</div>
			<div class="form-row">
				<label><?php _e('CVC*', 'pippin_stripe'); ?></label>
				<input type="text" size="4" autocomplete="off" class="card-cvc"/>
			</div>
			<div class="form-row">
				<label><?php _e('Expiration (MM/YYYY)*', 'pippin_stripe'); ?></label>
				<input type="text" size="2" class="card-expiry-month"/>
				<span> / </span>
				<input type="text" size="4" class="card-expiry-year"/>
			</div>
			<?php if(is_user_logged_in()) { ?>
			<div class="form-row">
				<input type="checkbox" id="recurring" value="recurring"/><span><?php _e('Recurring monthly payment', 'pippin_stripe'); ?></span>
			</div>
			<?php }  else { ?>
			<div class="form-row">
				<label><?php _e('Sign In if you would like to set up recurring contribution (create link here)', 'pippin_stripe'); ?></label>
			</div>
			<?php } ?>
			<input type="hidden" name="action" value="stripe"/>
			<input type="hidden" id="post_id" value="<?php echo url_to_postid(get_permalink()); ?>"/>
			<input type="hidden" name="amount" value="<?php echo $_POST['user-amount']; ?>"/>
			<input type="hidden" name="stripe_nonce" value="<?php echo wp_create_nonce('stripe-nonce'); ?>"/>
			<button type="submit" id="stripe-submit"><?php _e('Submit Payment', 'pippin_stripe'); ?></button>
		</form>
		<div class="payment-errors"></div>
	</div>
		<?php

	return ob_get_clean();

}  

function stripe_customer() {
	ob_start();
	$total_amount = get_total();
	echo '$' . $total_amount;
	return ob_get_clean();
}


function test_form() { ?>
	<div id="form-test">
		<form action="" method="POST" id="test-form">
			<label><?php _e('Amount*', 'pippin_stripe'); ?></label>
			<input type="text" size="20" autocomplete="off" placeholder="$20" name="user1"/>
			<br>
			<label><?php _e('Email*', 'pippin_stripe'); ?></label>
			<input type="text" size="20" autocomplete="off" name="user2"/>
			<br>
			<button type="submit"><?php _e('Submit Payment', 'pippin_stripe'); ?></button>
		</form>	
	</div>
<?php
}

add_shortcode('payment', 'pippin_stripe_payment_form');
add_shortcode('customer', 'stripe_customer');