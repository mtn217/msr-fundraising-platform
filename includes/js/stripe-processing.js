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
        // insert the token into the form so it gets submitted to the server
        form$.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
        // and submit
        // var data = $('form').serialize();

        var amount = $('#user-amount').val() * 100;
        var nounce = $('input[name=stripe_nonce').val();
        var dataString = 'user-amount=' + amount + '&stripeToken=' + token + '&action=stripe' + '&stripe_nonce=' + nounce;
    	$.ajax({
			type: "POST",
			url: "process-payment.php",
			data: dataString, 
			success: function(){
				// alert("yay")
				$('#payment-form').html("<div id='message'></div>");
				$('#message').html("<h2>Thank you for your payment. Please check your email for your receipt.</h2>")
					.append("<p>Your confirmation code is: </p>")
      				.hide()
      				.fadeIn(1000);
			},
			error: function(xhr) {
				alert(xhr.status + " " + xhr.statusText);
			}
		});
        //form$.get(0).submit(); //fix this part
    }
}
jQuery(document).ready(function($) {
	$("#stripe-payment-form").submit(function(event) {
		// disable the submit button to prevent repeated clicks
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
    	// print ("finished processing");

		// prevent the form from submitting with the default action
		event.preventDefault();
		return false;
	});

	$("#test-form").submit(function(event) {
		// disable the submit button to prevent repeated clicks
		// event.preventDefault();
		$.ajax({
			type: "POST",
			success: function(){
				// alert("Yay");
				$('#form-test').html("<div id='message'></div>");
				$('#message').html("<h2>Your payment was successful!</h2>");
				$('#message').fadeIn(5000);
			}
		});
		event.preventDefault();
		return false;
		// jQuery("#test-form").get(0).submit();
	});
});
