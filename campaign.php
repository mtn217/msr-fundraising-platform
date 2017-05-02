<?php

add_action( 'init', 'create_campaign_post_type' );
function create_campaign_post_type() {
	$labels = array(
        'name' => __('Campaigns', 'post type general name'),
        'singular_name' => __('Campaign', 'post type signular name'),
        'add_new' => __('Add New', 'campaign'),
        'add_new_item' => __('Add New Campaign'),
        'edit_item' => __('Edit Campaign'),
        'new_item' => __('New Campaign'),
        'all_items' => __('All Campaign'),
        'view_item' => __('View Campaign'),
        'search_items' => __('Search Campaigns'),
        'not_found' => __('No Campaigns Found'),
        'not_found_in_trash' => __('No Campaigns found in the Trash'),
        'parent_item_colon' => ''
    );
    register_post_type( 'campaign',
	    array(
	      'labels' => $labels,
	      'public' => true,
	      'has_archive' => true,
          'supports' => array( 'thumbnail',  'editor', 'title' ),
	    )
	);
}

add_action( 'add_meta_boxes', 'campaign_meta_box_add' );
function campaign_meta_box_add()
{
    add_meta_box( 'campaign-form', 'Campaign', 'campaign_meta_box_cb', 'campaign', 'normal', 'high' );
}

function campaign_meta_box_cb($post) {
	wp_nonce_field(basename(__FILE__), 'campaign_nonce'); ?>

    <h3><label for="campaign-goal">Goal Amount</label></h3>
    <input type="text" name="campaign-goal" id="campaign-goal" value="<?php echo esc_attr(get_post_meta($post->ID, 'campaign-goal', true)); ?>" />

    <h3><label for="campaign-start">Start Date</label></h3>
    <input type="date" name="campaign-start" id="campaign-start" value="<?php echo esc_attr(get_post_meta($post->ID, 'campaign-start', true)); ?>" />

    <h3><label for="campaign-end">End Date</label></h3>
    <input type="date" name="campaign-end" id="campaign-end" value="<?php echo esc_attr(get_post_meta($post->ID, 'campaign-end', true)); ?>" />
    
    <?php
}

function add_msr_nonce() {
    wp_nonce_field(basename(__FILE__), 'msr_nonce');
}

function save_campaign_form($post_id) {
    // if (!verify_save('campaign_nonce', $post_id))
    //     return $post_id;
    update_custom_post($post_id, 'campaign_form', 'campaign-goal');
    update_custom_post($post_id, 'campaign_form', 'campaign-start');
    update_custom_post($post_id, 'campaign_form', 'campaign-end');
    flush_rewrite_rules();
}

add_action('save_post', 'save_campaign_form');

?>