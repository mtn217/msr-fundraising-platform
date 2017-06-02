# MSR Fundraising Platform Plugin

## Overview of your project
### Problem Statement:
How might we create a fundraising platform so that MSR Global Health can increase awareness and support for their global health initiative from MSR’s outdoor customer audience?

### Background and Context:
MSR, a leader in providing high quality outdoor equipment, hosted a crowdfunding campaign on Indiegogo in October, 2016. MSR developed a chlorine maker device that allows anyone to treat contaminated water with very little resources. Their goal for the campaign was to deploy the chlorine maker devices to communities that need access to clean water the most. If the donation goal was met, MSR would be able to distribute enough chlorine maker devices to provide safe drinking water for 500,000 people. From this campaign, MSR found that a large amount of donations came from the outdoor enthusiasts that buys their outdoor equipment. Now, MSR wants to create a better way to connect with this audience. MSR also is in the process of creating a nonprofit division (MSR Global Health) within the company, to focus on raising funds and projects such as providing low cost water treatment solutions. MSR Global Health has decided that a great way to better connect to their outdoor customers while supporting MSR Global Health is to create a fundraising platform that will allow people to utilize their outdoor adventures to help raise money.


## MSR Fundraising Platform Plugin List of Contents
This repo contains the files for the MSR Fundraising Platform plugin. The plugin is responsible for the custom “Fundraiser” posts, payment forms, and updating the website’s database when contributions have been made.  
ReadMe.md is the file that contains this document. 
msrdonation.php is the file that helps the Wordpress website access the rest of our plugin and load up the necessary files when this plugin is activated.
- fundraiser.php is the file that creates the custom “Fundraiser” post and gives MSR Global Health the ability to manage all user submitted fundraisers.
- The lib folder contains Stripe API’s functions that we utilized for processing payments. 
- The includes folder contains the rest of the files for the plugin.
- settings.php is the file that lets MSR Global Health connect their Wordpress website to their Stripe Account.
- shortcodes.php is the file that contains the payment form code and the code to display all of the active fundraisers on the Browse page. 
- stripe-processing.js in the js folder creates a unique Stripe token once the payment form has been submitted by the user.
- process-payment.php takes the Stripe token generated by stripe-processing.js and charges the user’s credit card and updates the database with the amount charged. 
- stripe-listener.php is the file that will send out confirmation emails once a transaction has been successful. 
- scripts.php is the file that will universally load the necessary scripts to make the plugin function. 

## Summary of Major Technology Decisions
Since our team took on a project sponsored by MSR Global Health, and we wanted to create a solution that integrated into their existing Wordpress website, we were confined to using Wordpress as our platform. To add in the fundraising platform functionality, we created a plugin for the site (msr-fundraising-platform) and a child theme (msr-child) to properly display the fundraising platform.

To keep track of which users were running which fundraisers, we added the ability for front-end users to create their own accounts.

We added “Fundraiser” as a custom post type, essentially a data type, in order to keep the fundraisers separate from the normal Wordpress posts. With custom “Fundraisers”, we can code them to look and act the way that MSR Global Health would want it to. 

To process contributions made on the fundraising platform, we utilized Stripe as they offered the best non-profit pricing for each transaction made. This would ensure that the maximum amount of money is being donated towards the organization. 

## Contact Information
Ali Haugh
haughar@uw.edu

Amber Kim
amberkim@uw.edu

Nichelle Song
nsong94@uw.edu

Michael Nguyen
mtn217@uw.edu

