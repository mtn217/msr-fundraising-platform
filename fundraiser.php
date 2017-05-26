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
    register_post_type( 'fundraiser',
	    array(
	      'labels' => $labels,
	      'public' => true,
	      'has_archive' => false,
          'supports' => array( 'thumbnail', 'comments', 'author', 'editor', 'title' ),          
	    )
	);
}

add_action( 'add_meta_boxes', 'fundraiser_meta_box_add' );
function fundraiser_meta_box_add()
{
    add_meta_box( 'fundraser-form', 'Fundraiser', 'fundraiser_meta_box_cb', 'fundraiser', 'normal', 'high' );
}

function fundraiser_meta_box_cb($post) {
    if($post->post_type == 'fundraiser') {
    	wp_nonce_field(basename(__FILE__), 'fundraiser_nonce'); ?>

        <h3><label for="fundraiserGoal">Goal Amount</label></h3>
        <input type="text" name="fundraiserGoal" id="fundraiserGoal" value="<?php echo esc_attr(get_post_meta($post->ID, 'fundraiserGoal', true)); ?>" />

        <h3><label for="fundraiserStart">Start Date</label></h3>
        <input type="date" name="fundraiserStart" id="fundraiserStart" value="<?php echo esc_attr(get_post_meta($post->ID, 'fundraiserStart', true)); ?>" />

        <h3><label for="fundraiserEnd">End Date</label></h3>
        <input type="date" name="fundraiserEnd" id="fundraiserEnd" value="<?php echo esc_attr(get_post_meta($post->ID, 'fundraiserEnd', true)); ?>" />
        
        <?php
    }
}

add_action('save_post', 'save_fundraiser_form');
function save_fundraiser_form($post_id) {
    if (!verify_save('fundraiser_nonce', $post_id))
        return $post_id;
    update_custom_post($post_id, 'fundraiser_form', 'fundraiserGoal');
    update_custom_post($post_id, 'fundraiser_form', 'fundraiserStart');
    update_custom_post($post_id, 'fundraiser_form', 'fundraiserEnd');
    flush_rewrite_rules();
}

function verify_save ($nonce_name, $post_id) {
    if (!isset($_POST[$nonce_name])) {
        return false;
    }
    if (!wp_verify_nonce($_POST[$nonce_name], basename(__FILE__))) {
        return false;
    }
    if (!current_user_can('edit_page', $post_id)) {
        return false;
    }
    return true;
}

function update_custom_post($post_id, $form, $detail_name) {
    $new_meta_value = (isset($_POST[$detail_name]) ? sanitize_post_field($detail_name, $_POST[$detail_name], $post_id, 'display'):'');
    $meta_key = $detail_name;
    $meta_value = get_post_meta($post_id, $meta_key, true);
    if ($new_meta_value && '' == $meta_value)
        add_post_meta($post_id, $meta_key, $new_meta_value, true);
    elseif ($new_meta_value && $new_meta_value != $meta_value)
        update_post_meta($post_id, $meta_key, $new_meta_value);
    elseif ('' == $new_meta_value && $meta_value)
        delete_post_meta($post_id, $meta_key, $meta_value);
}
?>