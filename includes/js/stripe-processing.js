Stripe.setPublishableKey(stripe_vars.publishable_key);
function stripeResponseHandler(status, response) {
    if (response.error) {
		// show errors returned by Stripe
        jQuery(".payment-errors").html(response.error.message);
		// re-enable the submit button
		jQuery('#stripe-submit').attr("disabled", false);
    } else {
        var form$ = jQuery("#stripe-payment-form");
        // token contains id, last4, and card type
        var token = response['id'];
        var amount = $('.user-amount').val() * 100;
        var nounce = $('.stripe_nonce').val();
        var post_id = $('.post_id').val();
        var recurring = $('.recurring').val();
        var email = $('.email').val();
        var action = $('.action').val();
        var fullname = $('.name').val();

        var dataString = 'user-amount=' + amount + '&stripeToken=' + token + '&action=' + action + '&stripe_nonce=' + nounce + 
        	"&postID=" + post_id + "&email=" + email + "&name=" + fullname;

        if(document.getElementById("recurring")) {
        	if(document.getElementById("recurring").checked) {
        		dataString = dataString.concat("&recurring=recurring");
        	}
        }
   
    	$.ajax({
			type: "POST",
			url: "process-payment.php",
			data: dataString,
			dataType: 'json',
			success: function(data){
				$('#payment-form').html("<div id='message'></div>");
				$('#message').html("<h2>Thank you for your payment. Please check your email for your receipt.</h2>")
					.append("<p>Your confirmation code is:  " + data.id + "</p>")
      				.hide()
      				.fadeIn(1000);
			},
			error: function(xhr) {
				alert(xhr.status + " " + xhr.statusText);
			}
		});
    }
}
jQuery(document).ready(function($) {
	// Validates form
	$.validate({
		form : '#stripe-payment-form',
		//modules: 'date, security',
		onSuccess: function($form) {
			$('#stripe-submit').attr("disabled", "disabled");
			// send the card details to Stripe
			Stripe.createToken({
				number: $('.card-number').val(),
				cvc: $('.card-cvc').val(),
				exp_month: $('.card-expiry-month').val(),
				exp_year: $('.card-expiry-year').val(),
				name: $('.name').val(),
				address_zip: $('.zipcode').val(),
			}, stripeResponseHandler);
			event.preventDefault();
			return false;
		}
	});

// 	$("#stripe-payment-form").submit(function(event) {
// 		// disable the submit button to prevent repeated clicks
// 		$('#stripe-submit').attr("disabled", "disabled");

// 

// 		// Stripe.createToken({
// 		// 	number: $('.card-number').val(),
// 		// 	cvc: $('.card-cvc').val(),
// 		// 	exp_month: $('.card-expiry-month').val(),
// 		// 	exp_year: $('.card-expiry-year').val(),
// 		// 	name: $('.name').val(),
// 		// 	address_zip: $('.zipcode').val(),
// 		// }, stripeResponseHandler);

// 		// prevent the form from submitting with the default action
// 		event.preventDefault();
// 		return false;
// 	});
});
