<?php
   /*
   Plugin Name: MSR Global Health Fundraising Platform
   Description: a plugin for MSR Global Health's Fundraising Platform
   Version: 0.1
   Author: Michael Nguyen
   */
   if(!defined('STRIPE_BASE_URL')) {
   	define('STRIPE_BASE_URL', plugin_dir_url(__FILE__));
   }
   if(!defined('STRIPE_BASE_DIR')) {
   	define('STRIPE_BASE_DIR', dirname(__FILE__));
   }

   $stripe_options = get_option('stripe_settings');


if(is_admin()) {
  include(STRIPE_BASE_DIR . '/includes/settings.php');
} else {
  include(STRIPE_BASE_DIR . '/includes/shortcodes.php');
  include(STRIPE_BASE_DIR . '/includes/scripts.php');
  include(STRIPE_BASE_DIR . '/includes/process-payment.php');
  include(STRIPE_BASE_DIR . '/includes/stripe-listener.php');
  include(STRIPE_BASE_DIR . '/includes/stripe-functions.php');
}

include('fundraiser.php');
include('campaign.php');
?>
