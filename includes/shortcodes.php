<?php
function stripe_payment_form() {
 
	global $stripe_options;
	$post_id = url_to_postid(get_permalink());
	$user_name = "";
	if(is_user_logged_in()) {
		$user = get_userdata(get_current_user_id());
		$user_name = $user->first_name . " " . $user->last_name;
	}
	$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	ob_start();

	?>
	<h1>Contribute</h1>
	<div id="payment-form">
		<form action="process-payment.php" method="POST" id="stripe-payment-form">
			<h2><?php 
				$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				$id = url_to_postid($actual_link);
				if(strpos($actual_link, '/contribute/')) {
					echo 'General Contribution to MSR Global Health';
				} else {
					echo get_the_title($id);
				}?></h2>
			<div class="amount">
				<?php echo dollar_svg(); ?>
				<input data-validation="number" type="text" autocomplete="off" class="user-amount">
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
				<label><?php _e('Cardholder Name*', 'stripe'); ?></label>
				<input data-validation="alphanumeric" data-validation-allowing=" " type="text" size="20" autocomplete="off" class="name" value="<?php echo $user_name; ?>"/>
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

			<?php if(!strpos($actual_link, '/contribute/')) { ?>
				<div class="appearance">
					<h2>Contribution Appearance</h2>
					<?php echo info_svg(); ?>
					<div class="name-input">
						<input type="radio" name="appearance" id="user-name" checked value="display-name"/>
						<label for="display-name"><input type="text" name="display-name" class="display-name" autocomplete="off" class="name" value="<?php echo $user_name; ?>"/></label>
					</div>
					<div class="anonymous">
						<input type="radio" id="anonym" name="appearance" value="anonymous"/><label for="anonym">Anonymous</label>
					</div>
				</div>
			<?php } ?>

			<?php if(is_user_logged_in() && strpos($actual_link, '/contribute/')) { ?>
			<div class="form-row">
				<input class="r-input-check" type="checkbox" id="recurring"/>Recurring monthly payment
			</div>
			<?php }  elseif(strpos($actual_link, '/contribute/')) { ?>
 			<div class="form-row">
				<label>Sign In if you would like to set up recurring contribution<br><a id="log-in" href="/#wow-modal-id-3">Log in</a></label>
			</div> 
			<?php } ?>
			<input type="hidden" class="action" value="stripe"/>
			<input type="hidden" class="post_id" value="<?php 
				$id = url_to_postid($actual_link);
				if(strpos($actual_link, '/contribute/')) {
					echo 'general';
				} else {
					echo $id;
				}
			?>"/>
			<input type="hidden" class="stripe_nonce" value="<?php echo wp_create_nonce('stripe-nonce'); ?>"/>
			<div class="buttons">
				<button type="submit" id="stripe-submit"><?php _e('Submit', 'stripe'); ?></button>
			</div>
		</form>
		<div class="payment-errors"></div>
	</div>

	<script type="text/javascript"> 
		$('#log-in').click(function() {
			document.getElementById('wow-modal-overlay-1').style.display = "none";
		});
	</script>
		<?php
	return ob_get_clean();

}  

function stripe_campaign_total() {
	ob_start();
	$total_amount = get_campaign_total();
	echo '$' . $total_amount;
	return ob_get_clean();
}

function default_images() {
	// put this in a modal that pops up on click of choose button and closes once they click an image
	$args = array(
        'post_type' => 'attachment',
        'post_mime_type' =>'image',
        's' => 'featured-image',
        'post_status' => 'inherit'
    );
    $query_images = new WP_Query( $args );
    $images = array();
    foreach ( $query_images->posts as $image) {
        $images[]= $image->guid;
        echo '<button class="default" id="' . $image->ID . '"><img src="' . $image->guid . '" /></button>';

    }
}

add_shortcode('payment', 'stripe_payment_form');
add_shortcode('campaign', 'stripe_campaign_total');
add_shortcode('default_images', 'default_images');