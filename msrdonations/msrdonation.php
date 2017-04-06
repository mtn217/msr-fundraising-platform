<?php
   /*
   Plugin Name: MSR Global Health Fundraising Platform
   Description: a plugin for MSR Global Health's Fundraising Platform
   Version: 0.1
   Author: Michael Nguyen
   */

 function donation_admin() {
     include('donation_admin.php');
 }

function donation_admin_actions() {
    add_menu_page("MSR Donation Platform", "MSR Donation Platform", 1, "MSR Donation Platform", "donation_admin", "dashicons-chart-line", 79);
}

add_action('admin_menu', 'donation_admin_actions');

/*
https://code.tutsplus.com/tutorials/create-a-custom-wordpress-plugin-from-scratch--net-2668
https://codex.wordpress.org/Adding_Administration_Menus
https://codex.wordpress.org/Plugin_API/Action_Reference
https://codex.wordpress.org/Administration_Screens
*/
?>
