<?php

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) die( LCS_HACK_MSG );

// Settings

require_once dirname( __FILE__ ) . '/class.settings-api.php';

if ( !class_exists('lcs_Settings_API_Class' ) ):
class lcs_Settings_API_Class {

    private $settings_api;

    function __construct() {
        $this->settings_api = new lcs_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_submenu_page( 'edit.php?post_type=logocarousel', 'Settings', 'Settings', 'manage_options', 'settings', array($this, 'plugin_page') );
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id' => 'lcs_general_settings',
                'title' => __( 'General Settings', 'logo-carousel-slider' )
            ),
            array(
                'id' => 'lcs_slider_settings',
                'title' => __( 'Slider Settings', 'logo-carousel-slider' ),
                'desc' => __('<p class="lcs_pro_notice">Slider Settings only available in <a href="http://adlplugins.com/plugin/logo-carousel-slider-pro" target="_blank">Pro version</a></p>', 'logo-carousel-slider')
            ),
            array(
                'id' => 'lcs_style_settings',
                'title' => __( 'Style Settings', 'logo-carousel-slider' ),
                'desc' => __('<p class="lcs_pro_notice">Style Settings only available in <a href="http://adlplugins.com/plugin/logo-carousel-slider-pro" target="_blank">Pro version</a></p>', 'logo-carousel-slider')
            ),
            array(
                'id' => 'lcs_support',
                'title' => __( 'Usage & Support', 'logo-carousel-slider' )
            )
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
            'lcs_general_settings' => array(
                array(
                    'name' => 'lcs_dna',
                    'label' => __( 'Display Navigation Arrows', 'logo-carousel-slider' ),
                    'default' => 'yes',
                    'type' => 'radio',
                    'options' => array(
                        'yes' => __('Yes', 'logo-carousel-slider'),
                        'no' => __('No', 'logo-carousel-slider')
                    )
                ),
                array(
                    'name' => 'lcs_nap',
                    'label' => __( 'Navigation Arrows Position', 'logo-carousel-slider' ),
                    'default' => 'top_right',
                    'type' => 'radio',
                    'options' => array(
                        'top_right' => __('Top Right', 'logo-carousel-slider'),
                    ),

                ),

	            //Pro version promotion
	            array(
		            'name' => 'lcs_nap_promotion',
		            'type' => 'radio',
		            'options' => array(
			            'top_right' => __('<div class="lcs_pro_promo">The following Navigation Arrow Positions are available in <a href="http://adlplugins.com/plugin/logo-carousel-slider-pro" target="_blank">Pro version</a></div>', 'logo-carousel-slider'),
			            'top_middle' => __('Top Middle ', 'logo-carousel-slider'),
			            'top_left' => __('Top Left ', 'logo-carousel-slider'),
			            'middle' => __('Middle (visible on hover only) ', 'logo-carousel-slider'),
			            'middle_const' => __('Middle (always) ', 'logo-carousel-slider'),
			            'bottom_middle' => __('Bottom Middle ', 'logo-carousel-slider'),
			            'bottom_left' => __('Bottom Left ', 'logo-carousel-slider'),
			            'bottom_right' => __('Bottom Right ', 'logo-carousel-slider'),
		            ),

	            ),

	            array(
		            'name' => 'lcs_logotype',
		            'label' => __( 'Logo Type', 'logo-carousel-slider' ),
		            'default' => 'top_right',
		            'type' => 'radio',
		            'options' => array(
			            'top_right' => __('Display Logos from Latest Published', 'logo-carousel-slider'),
		            ),

	            ),

	            //PRO VERSION Promotion
	            array(
		            'name' => 'lcs_logotype_test',
		            'type' => 'radio',
		            'options' => array(
			            'top_right' => __('<div class="lcs_pro_promo">The following Logo Type Options are available in <a href="http://adlplugins.com/plugin/logo-carousel-slider-pro" target="_blank">Pro version</a></div>', 'logo-carousel-slider'),
			            'top_middle' => __('Display Logos from Older Published
 ', 'logo-carousel-slider'),
			            'top_left' => __('Display Logos randomly
 ', 'logo-carousel-slider'),
			            'middle' => __('Display Logos from Category
', 'logo-carousel-slider'),
			            'middle_const' => __('Display Logos by ID
 ', 'logo-carousel-slider'),
			            'bottom_middle' => __('Display Logos by Year
 ', 'logo-carousel-slider'),
			            'bottom_left' => __('Display Logos by Month
 ', 'logo-carousel-slider'),
		            ),

	            ),

                array(
                    'name' => 'lcs_dlt',
                    'label' => __( 'Display Logo Title', 'logo-carousel-slider' ),
                    'default' => 'no',
                    'type' => 'radio',
                    'options' => array(
                        'yes' => __('Yes', 'logo-carousel-slider'),
                        'no' => __('No', 'logo-carousel-slider')
                    )
                ),
                array(
                    'name' => 'lcs_dlb',
                    'label' => __( 'Display Logo Border', 'logo-carousel-slider' ),
                    'default' => 'yes',
                    'type' => 'radio',
                    'options' => array(
                        'yes' => __('Yes', 'logo-carousel-slider'),
                        'no' => __('No', 'logo-carousel-slider')
                    )
                ),
                array(
                    'name' => 'lcs_lhe',
                    'label' => __( 'Logo Hover Effect', 'logo-carousel-slider' ),
                    'default' => 'yes',
                    'type' => 'radio',
                    'options' => array(
                        'yes' => __('Yes', 'logo-carousel-slider'),
                        'no' => __('No', 'logo-carousel-slider')
                    )
                ),
                array(
                    'name' => 'lcs_ic',
                    'label' => __( 'Image Crop', 'logo-carousel-slider' ),
                    'desc' => __( 'If logos are not in the same size, this feature is helpful. It automatically resizes and crops.', 'logo-carousel-slider' ),
                    'default' => 'yes',
                    'type' => 'radio',
                    'options' => array(
                        'yes' => __('Yes', 'logo-carousel-slider'),
                        'no' => __('No', 'logo-carousel-slider')
                    )
                ),
                array(
                    'name'              => 'lcs_iwfc',
                    'label'             => __( 'Image Cropping Width', 'logo-carousel-slider' ),
                    'type'              => 'number',
                    'default'           => '185',
                    'sanitize_callback' => 'intval'
                ),
                array(
                    'name'              => 'lcs_ihfc',
                    'label'             => __( 'Image Cropping height', 'logo-carousel-slider' ),
                    'type'              => 'number',
                    'default'           => '119',
                    'sanitize_callback' => 'intval'
                ),
                array(
                    'name'              => 'lcs_lig',
                    'label'             => __( 'Items', 'logo-carousel-slider' ),
                    'desc'              => __( 'Maximum amount of items to display at a time', 'logo-carousel-slider' ),
                    'type'              => 'number',
                    'default'           => '5',
                    'sanitize_callback' => 'intval'
                ),
                array(
                    'name'    => 'lcs_apg',
                    'label'   => __( 'Auto Play', 'logo-carousel-slider' ),
                    'desc'    => __( 'Whether to play slider automatically or not', 'logo-carousel-slider' ),
                    'default' => 'yes',
                    'type'    => 'radio',
                    'options' => array(
                        'yes' => __('Yes', 'logo-carousel-slider'),
                        'no'  => __('No', 'logo-carousel-slider')
                    )
                ),
                array(
                    'name' => 'lcs_pagination',
                    'label' => __( 'Pagination', 'logo-carousel-slider' ),
                    'default' => 'no',
                    'type' => 'radio',
                    'options' => array(
                        'yes' => __('Yes', 'logo-carousel-slider'),
                        'no' => __('No', 'logo-carousel-slider')
                    )
                ),
            ),




            'lcs_slider_settings' => array(
                array(
                    'name' => 'lcs_ap',
                    'label' => __( 'Auto Play', 'logo-carousel-slider' ),
                    'default' => 'yes',
                    'type' => 'radio',
                    'options' => array(
                        'yes' => __('Yes', 'logo-carousel-slider'),
                        'no' => __('No', 'logo-carousel-slider')
                    )
                ),
                array(
                    'name'              => 'lcs_aps',
                    'label'             => __( 'Speed', 'logo-carousel-slider' ),
                    'desc'              => __( 'Auto play speed in milliseconds', 'logo-carousel-slider' ),
                    'type'              => 'number',
                    'default'           => '4000',
                    'sanitize_callback' => 'intval'
                ),
                array(
                    'name' => 'lcs_soh',
                    'label' => __( 'Stop on Hover', 'logo-carousel-slider' ),
                    'default' => 'yes',
                    'type' => 'radio',
                    'options' => array(
                        'yes' => __('Yes', 'logo-carousel-slider'),
                        'no' => __('No', 'logo-carousel-slider')
                    )
                ),
                array(
                    'name'              => 'lcs_li_desktop',
                    'label'             => __( 'Logo items (on Desktop, screen larger than 1198px)', 'logo-carousel-slider' ),
                    'desc'              => __( 'Maximum amount of items to display at a time on Desktop that screen size larger than 1198px', 'logo-carousel-slider' ),
                    'type'              => 'number',
                    'default'           => '5',
                    'sanitize_callback' => 'intval'
                ),
                array(
                    'name'              => 'lcs_li_desktop_small',
                    'label'             => __( 'Logo items (on Desktop, screen larger than 978px)', 'logo-carousel-slider' ),
                    'desc'              => __( 'Maximum amount of items to display at a time on Desktop that screen size larger than 978px', 'logo-carousel-slider' ),
                    'type'              => 'number',
                    'default'           => '4',
                    'sanitize_callback' => 'intval'
                ),
                array(
                    'name'              => 'lcs_li_tablet',
                    'label'             => __( 'Logo items (on Tablet)', 'logo-carousel-slider' ),
                    'desc'              => __( 'Maximum amount of items to display at a time on Tablet', 'logo-carousel-slider' ),
                    'type'              => 'number',
                    'default'           => '3',
                    'sanitize_callback' => 'intval'
                ),
                array(
                    'name'              => 'lcs_li_mobile',
                    'label'             => __( 'Logo items (on Mobile)', 'logo-carousel-slider' ),
                    'desc'              => __( 'Maximum amount of items to display at a time on Mobile', 'logo-carousel-slider' ),
                    'type'              => 'number',
                    'default'           => '2',
                    'sanitize_callback' => 'intval'
                ),
                array(
                    'name'              => 'lcs_ss',
                    'label'             => __( 'Slide Speed', 'logo-carousel-slider' ),
                    'desc'              => __( 'Slide speed in milliseconds', 'logo-carousel-slider' ),
                    'type'              => 'number',
                    'default'           => '800',
                    'sanitize_callback' => 'intval'
                ),
                array(
                    'name' => 'lcs_spp',
                    'label' => __( 'Scroll', 'logo-carousel-slider' ),
                    'default' => 'yes',
                    'type' => 'radio',
                    'options' => array(
                        'yes' => __('Per Item', 'logo-carousel-slider'),
                        'no' => __('Per Page', 'logo-carousel-slider')
                    )
                ),
                array(
                    'name' => 'lcs_pagination',
                    'label' => __( 'Pagination', 'logo-carousel-slider' ),
                    'default' => 'no',
                    'type' => 'radio',
                    'options' => array(
                        'yes' => __('Yes', 'logo-carousel-slider'),
                        'no' => __('No', 'logo-carousel-slider')
                    )
                ),
                array(
                    'name' => 'lcs_nip',
                    'label' => __( 'Slider Scrolling Directtion
', 'logo-carousel-slider' ),
                    'default' => 'no',
                    'type' => 'radio',
                    'options' => array(
                        'no' => __('Slide from Right to Left
', 'logo-carousel-slider'),
                        'yes' => __('Slide from Left to Right
<i class="lcs_pro_ver_notice"> - This feature only available in <a href="http://adlplugins.com/plugin/logo-carousel-slider-pro" target="_blank">Pro version</a></i>', 'logo-carousel-slider')
                    )
                ),

            ),



            'lcs_style_settings' => array(
                array(
                    'name'              => 'lcs_stfs',
                    'label'             => __( 'Slider Title Font Size', 'logo-carousel-slider' ),
                    'type'              => 'text',
                    'default'           => '18px'
                ),
                array(
                    'name'    => 'lcs_stfc',
                    'label'   => __( 'Slider Title Font Color', 'logo-carousel-slider' ),
                    'type'    => 'color',
                    'default' => '#444'
                ),
                array(
                    'name'    => 'lcs_nabc',
                    'label'   => __( 'Navigation Arrows Background Color', 'logo-carousel-slider' ),
                    'type'    => 'color',
                    'default' => '#ffffff'
                ),
                array(
                    'name'    => 'lcs_nabdc',
                    'label'   => __( 'Navigation Arrows Border Color', 'logo-carousel-slider' ),
                    'type'    => 'color',
                    'default' => '#ccc'
                ),
                array(
                    'name'    => 'lcs_nac',
                    'label'   => __( 'Navigation Arrows Color', 'logo-carousel-slider' ),
                    'type'    => 'color',
                    'default' => '#ccc'
                ),
                array(
                    'name'    => 'lcs_nahbc',
                    'label'   => __( 'Navigation Arrows Hover Background Color', 'logo-carousel-slider' ),
                    'type'    => 'color',
                    'default' => '#ffffff'
                ),
                array(
                    'name'    => 'lcs_nahbdc',
                    'label'   => __( 'Navigation Arrows Hover Border Color', 'logo-carousel-slider' ),
                    'type'    => 'color',
                    'default' => '#A0A0A0'
                ),
                array(
                    'name'    => 'lcs_nahc',
                    'label'   => __( 'Navigation Arrows Hover Color', 'logo-carousel-slider' ),
                    'type'    => 'color',
                    'default' => '#A0A0A0'
                ),
                array(
                    'name'    => 'lcs_lbc',
                    'label'   => __( 'Logo Border Color', 'logo-carousel-slider' ),
                    'type'    => 'color',
                    'default' => '#d6d4d4'
                ),
                array(
                    'name'    => 'lcs_lbhc',
                    'label'   => __( 'Logo Border Hover Color', 'logo-carousel-slider' ),
                    'type'    => 'color',
                    'default' => '#A0A0A0'
                ),
                array(
                    'name'              => 'lcs_ltfs',
                    'label'             => __( 'Logo Title Font Size', 'logo-carousel-slider' ),
                    'type'              => 'text',
                    'default'           => '14px'
                ),
                array(
                    'name'    => 'lcs_ltfc',
                    'label'   => __( 'Logo Title Font Color', 'logo-carousel-slider' ),
                    'type'    => 'color',
                    'default' => '#444'
                ),
                array(
                    'name'    => 'lcs_ltfhc',
                    'label'   => __( 'Logo Title Font Hover Color', 'logo-carousel-slider' ),
                    'type'    => 'color',
                    'default' => '#808080'
                ),
                array(
                    'name'    => 'lcs_tbc',
                    'label'   => __( 'Tooltip Background Color', 'logo-carousel-slider' ),
                    'type'    => 'color',
                    'default' => '#666666'
                ),
                array(
                    'name'    => 'lcs_tfc',
                    'label'   => __( 'Tooltip Font Color', 'logo-carousel-slider' ),
                    'type'    => 'color',
                    'default' => '#ffffff'
                ),
                array(
                    'name'              => 'lcs_tfs',
                    'label'             => __( 'Tooltip Font Size', 'logo-carousel-slider' ),
                    'type'              => 'text',
                    'default'           => '14px'
                ),
                array(
                    'name'    => 'lcs_pc',
                    'label'   => __( 'Pagination Color', 'logo-carousel-slider' ),
                    'type'    => 'color',
                    'default' => '#666666'
                )
            ),




            'lcs_support' => array(
                array(
                    'name' => 'lcs_sp',
                    'type' => 'html',
                    'desc' => '

                        <h2>Usage</h2>
                        <p>After successfully installing and activating the plugin, you will find "Logo Carousel" menu on left column of WordPress dashboard. To add a logo, go to Logo Carousel >> Add New Logo. Configure any options as desired using "Settings" page. Then use shortcode [logo_carousel_slider slider_title="Slider Title"] to display the logo carousel slider. If you don\'t want to display slider title/name, just use [logo_carousel_slider].</p><br /><br />

                         <h2> Support Forum</h2>
                         <p>If you need any helps, please don\'t hesitate to post it on <a href="https://wordpress.org/support/plugin/logo-carousel-slider" target="_blank">WordPress.org Support Forum</a> or <a href="http://adlplugins.com/support" target="_blank">AdlPlugins.com Support Forum</a>.</p><br /><br />

                         <h2>More Features</h2>
                         <p>Upgrading to the <a href="http://adlplugins.com/plugin/logo-carousel-slider-pro" target="_blank">Pro Version</a> would unlock more amazing features of the plugin.</p><br /><br />'
                )
            )
        );

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class="wrap lcs_settings_page">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
endif;

$settings = new lcs_Settings_API_Class();


//Retrieving the values
function lcs_get_option( $option, $section, $default = '' ) {
 
    $options = get_option( $section );
 
    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }
 
    return $default;
}