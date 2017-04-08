<?php

add_action( 'init', 'create_fundraiser_post_type' );
function create_fundraiser_post_type() {
	$labels = array(
        'name' => __('Fundraisers', 'post type general name'),
        'singular_name' => __('Fundraiser', 'post type signular name'),
        'add_new' => __('Add New', 'fundraiser'),
        'add_new_item' => __('Add New Fundraiser'),
        'edit_item' => __('Edit Fundraiser'),
        'new_item' => __('New Fundraiser'),
        'all_items' => __('All Fundraisers'),
        'view_item' => __('View Fundraiser'),
        'search_items' => __('Search Fundraisers'),
        'not_found' => __('No Fundraisers Found'),
        'not_found_in_trash' => __('No Fundraisers found in the Trash'),
        'parent_item_colon' => ''
    );
    register_post_type( 'msr_fundraiser',
	    array(
	      'labels' => $labels,
	      'public' => true,
	      'has_archive' => true,
	    )
	);
}
?>