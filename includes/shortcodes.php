<?php
function pippin_stripe_payment_form($atts, $content = null) {
 
	// extract( shortcode_atts( array(
	// 	'amount' => ''
	// ), $atts ) );
 
	global $stripe_options;
 
	if(isset($_GET['payment']) && $_GET['payment'] == 'paid') {
		echo '<p class="success">' . __('Thank you for your payment. Please check your email for your receipt.', 'pippin_stripe') . '</p>';
	} else { ?>
		<form action="" method="POST" id="stripe-payment-form">
			<div class="form-row">
				<label><?php _e('Amount*', 'pippin_stripe'); ?></label>
				<input type="text" size="20" autocomplete="off" placeholder="$20" name="user-amount"/>
			</div>

			<div class="form-row">
				<label><?php _e('Email*', 'pippin_stripe'); ?></label>
				<input type="text" size="20" autocomplete="off" name="email"/>
			</div>

			<div class="form-row">
				<label><?php _e('Name*', 'pippin_stripe'); ?></label>
				<input type="text" size="20" autocomplete="off" name="name"/>
			</div>

			<div class="form-row">
				<label><?php _e('Street*', 'pippin_stripe'); ?></label>
				<input type="text" size="20" autocomplete="off" name="address"/>
			</div>

			<div class="form-row">
				<label><?php _e('City*', 'pippin_stripe'); ?></label>
				<input type="text" size="20" autocomplete="off" name="city"/>
			</div>

			<div class="form-row">
				<label><?php _e('Zipcode*', 'pippin_stripe'); ?></label>
				<input type="text" size="20" autocomplete="off" name="zipcode"/>
			</div>

			<div class="form-row">
				<label><?php _e('Country*', 'pippin_stripe'); ?></label>
				<input type="text" size="20" autocomplete="off" name="country"/>
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
			<?php if(isset($stripe_options['recurring'])) { ?>
			<div class="form-row">
				<label><?php _e('Payment Type:', 'pippin_stripe'); ?></label>
				<input type="radio" name="recurring" value="no" checked="checked"/><span><?php _e('One time payment', 'pippin_stripe'); ?></span>
				<input type="radio" name="recurring" value="yes"/><span><?php _e('Recurring monthly payment', 'pippin_stripe'); ?></span>
			</div>
			<?php } ?>
			<input type="hidden" name="action" value="stripe"/>
			<input type="hidden" name="redirect" value="<?php echo get_permalink(); ?>"/>
			<input type="hidden" name="amount" value="<?php echo $_POST['user-amount']; ?>"/>
			<input type="hidden" name="stripe_nonce" value="<?php echo wp_create_nonce('stripe-nonce'); ?>"/>
			<button type="submit" id="stripe-submit"><?php _e('Submit Payment', 'pippin_stripe'); ?></button>
		</form>
		<div class="payment-errors"></div>
		<?php
	}
}

add_shortcode('payment', 'pippin_stripe_payment_form');
