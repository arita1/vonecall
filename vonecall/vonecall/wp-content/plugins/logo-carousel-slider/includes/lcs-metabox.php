<?php

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) die( LCS_HACK_MSG );

/**
 * Registers logo carousel slider post type.
 */
function lcs_init() {
    $labels = array(
        'name'               => _x( 'Logos', 'logo-carousel-slider' ),
        'singular_name'      => _x( 'Logo', 'logo-carousel-slider' ),
        'menu_name'          => _x( 'Logo Carousel', 'logo-carousel-slider' ),
        'name_admin_bar'     => _x( 'Logo Carousel', 'logo-carousel-slider' ),
        'all_items'          => __( 'All Logos', 'logo-carousel-slider' ),
        'add_new'            => _x( 'Add New Logo', 'logo-carousel-slider' ),
        'add_new_item'       => __( 'Add New Logo', 'logo-carousel-slider' ),
        'new_item'           => __( 'New Logo', 'logo-carousel-slider' ),
        'edit_item'          => __( 'Edit Logo', 'logo-carousel-slider' ),
        'view_item'          => __( 'View Logo', 'logo-carousel-slider' ),
        'search_items'       => __( 'Search Logo', 'logo-carousel-slider' ),
        'parent_item_colon'  => __( 'Parent Logos:', 'logo-carousel-slider' ),
        'not_found'          => __( 'No Logo found.', 'logo-carousel-slider' ),
        'not_found_in_trash' => __( 'No Logo found in Trash.', 'logo-carousel-slider' )
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'logo' ),
        'capability_type'    => 'post',
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'thumbnail' ),
        'menu_icon' => 'dashicons-images-alt2'
    );

    register_post_type( 'logocarousel', $args );
}
add_action( 'init', 'lcs_init' );

/**
 * Changes default meta box location of logo
 */
function lcs_meta_box_position() {
    remove_meta_box( 'postimagediv', 'logocarousel', 'side' );
    add_meta_box( 'postimagediv', __('Logo'), 'post_thumbnail_meta_box', 'logocarousel', 'normal', 'high' );
}
add_action('do_meta_boxes', 'lcs_meta_box_position');

/**
 * Adds two boxes to the main column on the logo carousel slider post type edit screens.
 */
function lcs_add_meta_box() {
    add_meta_box( 'lcs_metabox', __( 'URL','logo-carousel-slider' ), 'lcs_meta_box_content_output', 'logocarousel', 'normal' );
    add_meta_box( 'lcsp_tooltip_metabox', __( 'Tooltip','logo-carousel-slider' ), 'tooltip_meta_box_content_output', 'logocarousel', 'normal' );
}
add_action( 'add_meta_boxes', 'lcs_add_meta_box' ); 

/**
 * Prints the boxes content.
 */
function lcs_meta_box_content_output( $post ) {

// Add a nonce field so we can check for it later.
wp_nonce_field( 'lcs_save_meta_box_data', 'lcs_meta_box_nonce' );

$lcs_logo_link = get_post_meta( $post->ID, 'lcs_logo_link', true );

?>

<div class="lcs-row">
    <div class="lcs-th">
        <label for="lcs_logo_link"><?php _e('Logo link', 'logo-carousel-slider'); ?></label>
    </div>
    <div class="lcs-td">
        <input type="text" class="lcs-text-input" name="lcs_logo_link" id="lcs_logo_link" value="<?php if(isset($lcs_logo_link)) { echo $lcs_logo_link; } else { echo ''; } ?>">
    </div>
</div>

<?php }


function tooltip_meta_box_content_output ( $post ) {

?>

<div class="lcs-row">
        <div class="lcs-th">
            <label for="lcs_tooltip_txt"><?php _e('Tooltip Text', 'logo-carousel-slider'); ?></label>
        </div>
        <div class="lcs-td disabled">
            <input type="text" class="lcs-text-input" name="lcs_tooltip_text" id="lcs_tooltip_text">
            <i class="lcs_pro_ver_notice">This feature only available in <a href="http://adlplugins.com/plugin/logo-carousel-slider-pro" target="_blank">Pro version</a></i>
        </div>
</div>

<?php }


/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function lcs_save_meta_box_data( $post_id ) {
/*
     * We need to verify this came from our screen and with proper authorization,
     * because the save_post action can be triggered at other times.
     */

    // Check if our nonce is set.
    if ( ! isset( $_POST['lcs_meta_box_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['lcs_meta_box_nonce'], 'lcs_save_meta_box_data' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }

    } else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    $lcs_logo_link_value = "";

    if(isset($_POST["lcs_logo_link"]))
    {
        $lcs_logo_link_value = sanitize_text_field( $_POST["lcs_logo_link"] );
    }   
    update_post_meta($post_id, "lcs_logo_link", $lcs_logo_link_value);
}
add_action( 'save_post', 'lcs_save_meta_box_data' );

