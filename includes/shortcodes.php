<?php
function stripe_payment_form() {
 
	global $stripe_options;
	$post_id = url_to_postid(get_permalink());
	$user_first = "";
	$user_last = "";
	if(is_user_logged_in()) {
		$user = get_userdata(get_current_user_id());
		$user_first = $user->first_name;
		$user_last = $user->last_name;
	}
	$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	ob_start(); ?>

	<div id="payment-form" class="<?php if (!is_user_logged_in()) { echo 'hide'; } ?>">
		<form action="" method="POST" id="stripe-payment-form">
			<div class="left">
				<h2><?php 
					$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
					$id = url_to_postid($actual_link);
					if(strpos($actual_link, '/general-contribution/')) {
						echo 'General Contribution to MSR Global Health';
					} else {
						echo get_the_title($id);
					}?>
				</h2>
				<div class="default-amounts">
					<?php foreach (["25", "50", "100", "500"] as $amount) { ?>
						<button class="default-amount" id="<?php echo $amount; ?>">$<?php echo $amount; ?></button>
					<?php } ?>
				</div>
				<div class="form-row amount">
					<p>Your contribution:</p>
					<p class="subtext">&nbsp;</p>
					<div>
						<?php echo dollar_svg(); ?>
						<input data-validation="number" type="text" autocomplete="off" class="user-amount">
					</div>
				</div>
				<h4>Your Information</h4>
				<div class="form-row split-row">
					<div class="first">
						<label><?php _e('First Name <span class="red">*</span>', 'stripe'); ?></label>
						<input data-validation="alphanumeric" data-validation-allowing=" " type="text" autocomplete="off" class="first-name" value="<?php echo $user_first; ?>"/>
					</div>
					<div class="second">
						<label><?php _e('Last Name <span class="red">*</span>', 'stripe'); ?></label>
						<input data-validation="alphanumeric" data-validation-allowing=" " type="text" autocomplete="off" class="last-name" value="<?php echo $user_last; ?>"/>
					</div>
				</div>
				<?php if (!strpos($actual_link, '/contribute/')) { ?>
					<div class="form-row check-input">
						<input type="checkbox" id="anonymous-contribution" name="anonymous" /><label for="anonymous-contribution"><?php _e('Make contribution anonymous', 'stripe'); ?></label>
					</div>
				<?php } ?>
				<div class="form-row">
					<label><?php _e('Email <span class="red">*</span>', 'stripe'); ?></label>
					<input data-validation="email" type="text" size="20" autocomplete="off" class="email" value="<?php  
						if(is_user_logged_in()) {
							$user = get_userdata(get_current_user_id());
							echo $user->user_email;
						}
					?>"/>
					<p class="helper-text">Your reciept will be emailed here.</p>
				</div>
				<?php if(!is_user_logged_in()) { ?>
					<div class="form-row check-input">
						<input type="checkbox" name="subscribe" class="subscribe" checked /><label><?php _e('Subscribe to MSR Global Health updates', 'stripe'); ?></label>
					</div>
				<?php } ?>
			</div>
			<div class="right">
				<h4>Payment Details</h4>
				<div class="form-row">
					<label><?php _e('Card No <span class="red">*</span>', 'stripe'); ?></label>
					<input type="text" size="20" autocomplete="off" class="card-number"/>
				</div>
				<div class="form-row">
					<label><?php _e('Cardholder Name <span class="red">*</span>', 'stripe'); ?></label>
					<input data-validation="alphanumeric" data-validation-allowing=" " type="text" size="20" autocomplete="off" class="name" />
				</div>
				<div class="form-row">
					<div class="expiration group">
						<label><?php _e('Expiration Date <span class="red">*</span>', 'stripe'); ?></label>
						<input type="text" size="2" class="card-expiry-month" placeholder="MM"/>
						<!-- Limit to 2 numbers only -->
						<input type="text" size="4" class="card-expiry-year" placeholder="YYYY"/>
						<!-- Limit to 4 numbers only -->
					</div>
					<div class="ccv group">
						<label><?php _e('CVC <span class="red">*</span>', 'stripe'); ?></label>
						<input type="text" size="3" autocomplete="off" class="card-cvc"/>
					</div>
				</div>
				<?php if(is_user_logged_in() && !strpos($actual_link, '/general-contribution/')) { ?>
					<div class="comment-box">
						<h3 class="comment-reply-title">Leave a Comment</h3>
						<label>Comments will not be made anonymously.</label>
						<textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" placeholder="Let us know why you contributed, tell us your story, or send words of encouragement!"></textarea>
						<input type="hidden" class="user-id" value="<?php echo get_current_user_id(); ?>" />
					</div>
				<?php } ?>
				<?php if(is_user_logged_in() && strpos($actual_link, '/general-contribution/')) { ?>
					<div class="form-row check-input">
						<input class="r-input-check" type="checkbox" id="recurring"/><label for="recurring">Recurring monthly payment</label>
					</div>
				<?php }  elseif(strpos($actual_link, '/general-contribution/')) { ?>
		 			<div class="form-row">
						<label>Sign In if you would like to set up recurring contribution<br><a id="log-in" href="/#wow-modal-id-3">Log in</a></label>
					</div> 
				<?php } ?>
				<input type="hidden" class="action" value="stripe"/>
				<input type="hidden" class="post_id" value="<?php 
					$id = url_to_postid($actual_link);
					if(strpos($actual_link, '/general-contribution/')) {
						echo 'general';
					} else {
						echo $id;
					}
				?>"/>
				<input type="hidden" class="stripe_nonce" value="<?php echo wp_create_nonce('stripe-nonce'); ?>"/>
				<div class="buttons">
					<button type="button" class="cancel" data-dismiss="modal" aria-label="Close">Cancel</button>
					<button type="submit" id="stripe-submit"><?php _e('Give Now!', 'stripe'); ?></button>
				</div>
			</div>
		</form>
		<div class="payment-errors"></div>
		<div class="message">
			<?php echo green_check(); ?>
			<h2>Thank you!</h2>
			<div id="message"></div>
			<hr>
			<p>Share your contribution</p>
			<div class="sharing">
				<a href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&amp;t=<?php the_title(); ?>" title="Share on Facebook." target="_blank"><?php echo facebook_svg(); ?></a>
				<a href="http://twitter.com/home/?status=<?php the_title(); ?> - <?php the_permalink(); ?>" title="Tweet this!" target="_blank"><?php echo twitter_svg(); ?></a>
				<a id="copy-link" class="msr-tooltip">
					<?php echo copy_svg(); ?>
					<p id="copy-success" class="msr-tooltiptext">Copied!</p>
				</a>
			</div>
			<button type="button" class="cancel" data-dismiss="modal" aria-label="Close">Done</button>
		</div>
	</div>

	<script type="text/javascript"> 

		function update_people_affected() {
			if($('.amount input.user-amount').val()) {
				var amount = parseFloat($('.amount input.user-amount').val());
				$('.amount p.subtext').text(parseInt((amount - 0.3 - (amount * 0.022)) / 0.8) + " people will receive safe water");
			}
		}

		$('#log-in').click(function() {
			document.getElementById('wow-modal-overlay-1').style.display = "none";
		});

		$('button.default-amount').click(function(event) {
			event.preventDefault();
			$('input.user-amount').val($(this).attr('id'));
			update_people_affected();
		});

		$('input.user-amount').focusout(function() {
			update_people_affected();
		});
	</script>
		<?php
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
        echo '<button class="default" id="' . $image->ID . '" data-dismiss="modal" aria-label="Close"><img src="' . $image->guid . '" /></button>';

    }
}

function fundraiser_listing() {
	global $post; 

	$args = array(
        'post_type' => 'fundraiser',
        'post_status' => 'publish'
    );

    $query_funds = new WP_Query($args);

    $featured = array();
    $recently_added = array();
    $ending_soon = array();
    $past = array();
    $other = array();

    foreach ($query_funds->posts as $fund) {
    	$id = $fund->ID;
    	if (get_post_meta($id, 'fundraiserEnd', true) != "" && get_fundraising_days_left(get_post_meta($id, 'fundraiserEnd', true)) < 11 && get_fundraising_days_left(get_post_meta($id, 'fundraiserEnd', true)) > 0) {
    		array_push($ending_soon, $fund);
    	} else if(get_post_meta($id, 'fundraiserEnd', true) != "" && abs(get_fundraising_days_left(get_post_meta($id, 'fundraiserStart', true))) < 11 && abs(get_fundraising_days_left(get_post_meta($id, 'fundraiserStart', true))) > 0) {
    		array_push($recently_added, $fund);
    	} else if (get_post_meta($id, 'fundraiserEnd', true) != "" && get_fundraising_days_left(get_post_meta($id, 'fundraiserEnd', true)) < 0) {
    		array_push($past, $fund);
    	} else {
    		array_push($other, $fund);
    	}
    } ?>
    
    <?php if(!empty($recently_added)) { ?>
	    <div class="user-profile-header add-clear">
				<p id="titleText">Recently Added Fundraisers</p>
		</div>
	    <div>
			<?php
		    foreach($recently_added as $fund) {
		    	$post = get_post( $fund->ID, OBJECT );
				setup_postdata( $post );
				$id = $post->ID;
		    	get_active_fundraisers($id);
		    	wp_reset_postdata();
		    } ?>
	    </div>
    <?php } ?>

    <?php if(!empty($ending_soon)) { ?>
	    <div class="user-profile-header add-clear">
				<p id="titleText">Fundraisers Ending Soon</p>
		</div>
	    <div>
			<?php
		    foreach($ending_soon as $fund) {
		    	$post = get_post( $fund->ID, OBJECT );
				setup_postdata( $post );
				$id = $post->ID;
		    	get_active_fundraisers($id);
		    	wp_reset_postdata();
		   } ?>
	    </div>
	<?php } ?>

    <?php if(!empty($past)) { ?>
	    <div class="user-profile-header add-clear">
				<p id="titleText">Past Fundraisers</p>
		</div>
	    <div>
			<?php
		    foreach($past as $fund) {
		    	$post = get_post( $fund->ID, OBJECT );
				setup_postdata( $post );
				$id = $post->ID;
		    	get_active_fundraisers($id);
		    	wp_reset_postdata();
		    } ?>
	    </div>
    <?php } ?>

    <?php if(!empty($other)) { ?>
	    <div class="user-profile-header add-clear">
				<p id="titleText">Other Fundraisers</p>
		</div>
	    <div>
		    <?php
		    foreach($other as $fund) {
		    	$post = get_post( $fund->ID, OBJECT );
				setup_postdata( $post );
				$id = $post->ID;
		    	get_active_fundraisers($id);
		    	wp_reset_postdata();
		    } ?>
	    </div>
	    <?php
    }

}

add_shortcode('payment', 'stripe_payment_form');
add_shortcode('default_images', 'default_images');
add_shortcode('listing', 'fundraiser_listing');