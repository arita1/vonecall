<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
require 'helper_function.php';
require 'PortaOneWS.php';
require 'PrepayNationWS.php';
require 'authorize/testing.php';
require 'twilio/test.php'; // Loads the library

//print_r($_SESSION);



/**
 * Twenty Sixteen functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

/**
 * Twenty Sixteen only works in WordPress 4.4 or later.
 */

wp_enqueue_script('custom', get_template_directory_uri() . '/js/custom.js', array(
    'jquery'
));

if (version_compare($GLOBALS['wp_version'], '4.4-alpha', '<')) {
    require get_template_directory() . '/inc/back-compat.php';
}

if (!function_exists('twentysixteen_setup')): /**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * Create your own twentysixteen_setup() function to override in a child theme.
 *
 * @since Twenty Sixteen 1.0
 */ 
    function twentysixteen_setup()
    {
        /*
         * Make theme available for translation.
         * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/twentysixteen
         * If you're building a theme based on Twenty Sixteen, use a find and replace
         * to change 'twentysixteen' to the name of your theme in all the template files
         */
        load_theme_textdomain('twentysixteen');
        
        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');
        
        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');
        
        /*
         * Enable support for custom logo.
         *
         *  @since Twenty Sixteen 1.2
         */
        add_theme_support('custom-logo', array(
            'height' => 240,
            'width' => 240,
            'flex-height' => true
        ));
        
        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
         */
        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(1200, 9999);
        
        // This theme uses wp_nav_menu() in two locations.
        register_nav_menus(array(
            'primary' => __('Primary Menu', 'twentysixteen'),
            'social' => __('Social Links Menu', 'twentysixteen')
        ));
        
        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption'
        ));
        
        /*
         * Enable support for Post Formats.
         *
         * See: https://codex.wordpress.org/Post_Formats
         */
        add_theme_support('post-formats', array(
            'aside',
            'image',
            'video',
            'quote',
            'link',
            'gallery',
            'status',
            'audio',
            'chat'
        ));
        
        /*
         * This theme styles the visual editor to resemble the theme style,
         * specifically font, colors, icons, and column width.
         */
        add_editor_style(array(
            'css/editor-style.css',
            twentysixteen_fonts_url()
        ));
        
        // Indicate widget sidebars can use selective refresh in the Customizer.
        add_theme_support('customize-selective-refresh-widgets');
    }
endif; // twentysixteen_setup
add_action('after_setup_theme', 'twentysixteen_setup');

/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_content_width()
{
    $GLOBALS['content_width'] = apply_filters('twentysixteen_content_width', 840);
}
add_action('after_setup_theme', 'twentysixteen_content_width', 0);

/**
 * Registers a widget area.
 *
 * @link https://developer.wordpress.org/reference/functions/register_sidebar/
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_widgets_init()
{
    // First footer widget area, located in the footer. Empty by default.
    register_sidebar(array(
        'name' => __('First Footer Widget Area', 'tutsplus'),
        'id' => 'first-footer-widget-area',
        'description' => __('The first footer widget area', 'tutsplus'),
        'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));
    
    // Second Footer Widget Area, located in the footer. Empty by default.
    register_sidebar(array(
        'name' => __('Second Footer Widget Area', 'tutsplus'),
        'id' => 'second-footer-widget-area',
        'description' => __('The second footer widget area', 'tutsplus'),
        'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));
    
    // Third Footer Widget Area, located in the footer. Empty by default.
    register_sidebar(array(
        'name' => __('Third Footer Widget Area', 'tutsplus'),
        'id' => 'third-footer-widget-area',
        'description' => __('The third footer widget area', 'tutsplus'),
        'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));
    
    // Fourth Footer Widget Area, located in the footer. Empty by default.
    register_sidebar(array(
        'name' => __('Fourth Footer Widget Area', 'tutsplus'),
        'id' => 'fourth-footer-widget-area',
        'description' => __('The fourth footer widget area', 'tutsplus'),
        'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));
    
    // Fourth Footer Widget Area, located in the footer. Empty by default.
    register_sidebar(array(
        'name' => __('copyright Footer Widget Area', 'tutsplus'),
        'id' => 'copyright-footer-widget-area',
        'description' => __('The copyright footer widget area', 'tutsplus'),
        'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));
}
add_action('widgets_init', 'twentysixteen_widgets_init');

if (!function_exists('twentysixteen_fonts_url')): /**
 * Register Google fonts for Twenty Sixteen.
 *
 * Create your own twentysixteen_fonts_url() function to override in a child theme.
 *
 * @since Twenty Sixteen 1.0
 *
 * @return string Google fonts URL for the theme.
 */ 
    function twentysixteen_fonts_url()
    {
        $fonts_url = '';
        $fonts     = array();
        $subsets   = 'latin,latin-ext';
        
        /* translators: If there are characters in your language that are not supported by Merriweather, translate this to 'off'. Do not translate into your own language. */
        if ('off' !== _x('on', 'Merriweather font: on or off', 'twentysixteen')) {
            $fonts[] = 'Merriweather:400,700,900,400italic,700italic,900italic';
        }
        
        /* translators: If there are characters in your language that are not supported by Montserrat, translate this to 'off'. Do not translate into your own language. */
        if ('off' !== _x('on', 'Montserrat font: on or off', 'twentysixteen')) {
            $fonts[] = 'Montserrat:400,700';
        }
        
        /* translators: If there are characters in your language that are not supported by Inconsolata, translate this to 'off'. Do not translate into your own language. */
        if ('off' !== _x('on', 'Inconsolata font: on or off', 'twentysixteen')) {
            $fonts[] = 'Inconsolata:400';
        }
        
        if ($fonts) {
            $fonts_url = add_query_arg(array(
                'family' => urlencode(implode('|', $fonts)),
                'subset' => urlencode($subsets)
            ), 'https://fonts.googleapis.com/css');
        }
        
        return $fonts_url;
    }
endif;

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_javascript_detection()
{
    echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action('wp_head', 'twentysixteen_javascript_detection', 0);

/**
 * Enqueues scripts and styles.
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_scripts()
{
    // Add custom fonts, used in the main stylesheet.
    wp_enqueue_style('twentysixteen-fonts', twentysixteen_fonts_url(), array(), null);
    
    // Add Genericons, used in the main stylesheet.
    wp_enqueue_style('genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.4.1');
    
    // Theme stylesheet.
    wp_enqueue_style('twentysixteen-style', get_stylesheet_uri());
    
    // Load the Internet Explorer specific stylesheet.
    wp_enqueue_style('twentysixteen-ie', get_template_directory_uri() . '/css/ie.css', array(
        'twentysixteen-style'
    ), '20160816');
    wp_style_add_data('twentysixteen-ie', 'conditional', 'lt IE 10');
    
    // Load the Internet Explorer 8 specific stylesheet.
    wp_enqueue_style('twentysixteen-ie8', get_template_directory_uri() . '/css/ie8.css', array(
        'twentysixteen-style'
    ), '20160816');
    wp_style_add_data('twentysixteen-ie8', 'conditional', 'lt IE 9');
    
    // Load the Internet Explorer 7 specific stylesheet.
    wp_enqueue_style('twentysixteen-ie7', get_template_directory_uri() . '/css/ie7.css', array(
        'twentysixteen-style'
    ), '20160816');
    wp_style_add_data('twentysixteen-ie7', 'conditional', 'lt IE 8');
    
    // Load the html5 shiv.
    wp_enqueue_script('twentysixteen-html5', get_template_directory_uri() . '/js/html5.js', array(), '3.7.3');
    wp_script_add_data('twentysixteen-html5', 'conditional', 'lt IE 9');
    
    wp_enqueue_script('twentysixteen-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20160816', true);
    
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    
    if (is_singular() && wp_attachment_is_image()) {
        wp_enqueue_script('twentysixteen-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array(
            'jquery'
        ), '20160816');
    }
    
    wp_enqueue_script('twentysixteen-script', get_template_directory_uri() . '/js/functions.js', array(
        'jquery'
    ), '20160816', true);
    
    wp_localize_script('twentysixteen-script', 'screenReaderText', array(
        'expand' => __('expand child menu', 'twentysixteen'),
        'collapse' => __('collapse child menu', 'twentysixteen')
    ));
}
add_action('wp_enqueue_scripts', 'twentysixteen_scripts');

/**
 * Adds custom classes to the array of body classes.
 *
 * @since Twenty Sixteen 1.0
 *
 * @param array $classes Classes for the body element.
 * @return array (Maybe) filtered body classes.
 */
function twentysixteen_body_classes($classes)
{
    // Adds a class of custom-background-image to sites with a custom background image.
    if (get_background_image()) {
        $classes[] = 'custom-background-image';
    }
    
    // Adds a class of group-blog to sites with more than 1 published author.
    if (is_multi_author()) {
        $classes[] = 'group-blog';
    }
    
    // Adds a class of no-sidebar to sites without active sidebar.
    if (!is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }
    
    // Adds a class of hfeed to non-singular pages.
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }
    
    return $classes;
}
add_filter('body_class', 'twentysixteen_body_classes');

/**
 * Converts a HEX value to RGB.
 *
 * @since Twenty Sixteen 1.0
 *
 * @param string $color The original color, in 3- or 6-digit hexadecimal form.
 * @return array Array containing RGB (red, green, and blue) values for the given
 *               HEX code, empty array otherwise.
 */
function twentysixteen_hex2rgb($color)
{
    $color = trim($color, '#');
    
    if (strlen($color) === 3) {
        $r = hexdec(substr($color, 0, 1) . substr($color, 0, 1));
        $g = hexdec(substr($color, 1, 1) . substr($color, 1, 1));
        $b = hexdec(substr($color, 2, 1) . substr($color, 2, 1));
    } else if (strlen($color) === 6) {
        $r = hexdec(substr($color, 0, 2));
        $g = hexdec(substr($color, 2, 2));
        $b = hexdec(substr($color, 4, 2));
    } else {
        return array();
    }
    
    return array(
        'red' => $r,
        'green' => $g,
        'blue' => $b
    );
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images
 *
 * @since Twenty Sixteen 1.0
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function twentysixteen_content_image_sizes_attr($sizes, $size)
{
    $width = $size[0];
    
    840 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 62vw, 840px';
    
    if ('page' === get_post_type()) {
        840 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
    } else {
        840 > $width && 600 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 61vw, (max-width: 1362px) 45vw, 600px';
        600 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
    }
    
    return $sizes;
}
add_filter('wp_calculate_image_sizes', 'twentysixteen_content_image_sizes_attr', 10, 2);

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails
 *
 * @since Twenty Sixteen 1.0
 *
 * @param array $attr Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size Registered image size or flat array of height and width dimensions.
 * @return string A source size value for use in a post thumbnail 'sizes' attribute.
 */
function twentysixteen_post_thumbnail_sizes_attr($attr, $attachment, $size)
{
    if ('post-thumbnail' === $size) {
        is_active_sidebar('sidebar-1') && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 60vw, (max-width: 1362px) 62vw, 840px';
        !is_active_sidebar('sidebar-1') && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 88vw, 100%';
    }
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'twentysixteen_post_thumbnail_sizes_attr', 10, 3);

/**
 * Modifies tag cloud widget arguments to have all tags in the widget same font size.
 *
 * @since Twenty Sixteen 1.1
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array A new modified arguments.
 */
function twentysixteen_widget_tag_cloud_args($args)
{
    $args['largest']  = 1;
    $args['smallest'] = 1;
    $args['unit']     = 'em';
    return $args;
}
add_filter('widget_tag_cloud_args', 'twentysixteen_widget_tag_cloud_args');

/***********************custom function addition started bya arita ************/

wp_localize_script('custom', 'MBAjax', array(
    'ajaxurl' => admin_url('admin-ajax.php')
));


//** site URL**//
wp_localize_script('custom', 'Site_url', array(
    'url' => site_url()
));

// add user current timezone
add_action('wp_ajax_timezone', 'add_timezone');
add_action('wp_ajax_nopriv_timezone', 'add_timezone');
function add_timezone(){
    $time= $_POST["time"];
    $_SESSION["timezone"] = $time;
  exit();
}

// add store session 
add_action('wp_ajax_store', 'store_session');
add_action('wp_ajax_nopriv_store', 'store_session');
function store_session(){
    $name = $_POST['store'];
  $session_state =   get_store_status($name);
  
  if($session_state == 1){
    $_SESSION["store"] = $name;
  }else{
    $_SESSION['store'] = null;
  }
   echo  $_SESSION['store'];
  exit();
}


// get store session 
add_action('wp_ajax_get_store', 'get_store_session');
add_action('wp_ajax_nopriv_get_store', 'get_store_session');
function get_store_session(){
    $name =  $_SESSION['store'];
  $session_state =   get_store_status($name);
  print_r($session_state);
  if($session_state == 1){
    $_SESSION["store"] = $name;
  }elseif($session_state == 0){
   unset($_SESSION['store']);
  }
  
  exit();
}

add_action('wp_ajax_check_login', 'check_user_login');
add_action('wp_ajax_nopriv_check_login', 'check_user_login');

function check_user_login()
{
    
    $unm       = $_POST['email'];
    $password  = $_POST['key'];
    $pass      = md5($_POST['key']);
    $user_type = $_POST['user_role'];
    
    
    global $wpdb;
    
    $post_id = $wpdb->get_results("SELECT * FROM `wp_users` WHERE `user_login`='" . $unm . "' AND user_pass='" . $pass . "'");
    if (!empty($_POST)) {
        $result                 = "user login Successfully";
        $_SESSION['userid']     = $post_id[0]->ID;
        $_SESSION['user_name']  = $post_id[0]->user_login;
        $_SESSION['user_email'] = $post_id[0]->user_email;
    } else {
        $result = "User not found";
    }
    
    echo $result;
    exit();
}


/****************REGISTRATION RELATED FUNCTION **************/

add_action('wp_ajax_register_user', 'register_user');

function register_user()
{
    
    $unm   = $_POST['unm'];
    $pass  = $_POST['pass'];
    $email = $_POST['email'];
    $data  = array(
        'user_login' => $unm,
        'user_pass' => md5($pass),
        'user_nicename' => $unm,
        'user_email' => $email,
        'user_status' => '1',
        'display_name' => $unm
    );
    // print_r($data);
    global $wpdb;
    $result = $wpdb->insert('wp_users', $data);
    if (!empty($result)) {
        $return = "user Registered  Successfully";
    } else {
        $return = "User not found";
    }
    echo $return;
    exit();
}


/***********************FUNCTION TO SEARCH COUNTRY NAME ************************/
add_action('wp_ajax_search_country', 'country_search');
add_action('wp_ajax_nopriv_search_country', 'country_search');

function country_search()
{
    $keyword = $_POST['key']; // getting keyword /country name
    global $wpdb;
    $data = $wpdb->get_results("SELECT distinct `Country` FROM `rates` WHERE `Country` LIKE '%" . $keyword . "%'");
    if (!empty($data)) {
        foreach ($data as $row) {
            $country_name    = $row->Country;
            $country_array[] = array(
                'country' => $country_name
            );
        }
        $return_array = json_encode($country_array);
        echo $return_array;
    } else {
        echo "No match found";
    }
    
    exit();
}


/********************GET ALL COUNTRY ********/
add_action('get_country', 'get_all_country');
function get_all_country()
{
    
    global $wpdb;
    $data = $wpdb->get_results("SELECT distinct `Country` FROM `rates`");
    if (!empty($data)) {
        foreach ($data as $row) {
            $country_name    = $row->Country;
            $country_array[] = array(
                'country' => $country_name
            );
        }
        return $country_array;
    } else {
        return $return = "No country Found";
    }
    
    exit();
}


/*********** RATE SEARCH FUNCTION **************/

add_action('wp_ajax_search_rate', 'search_rates');
add_action('wp_ajax_nopriv_search_rate', 'search_rates');

function search_rates()
{
    $keyword = $_POST['key']; // getting keyword /country name
    
    global $wpdb;
    if($keyword == 'All'){
           $data = $wpdb->get_results("SELECT * FROM `rates`");
    }else{
    
    $data = $wpdb->get_results("SELECT * FROM `rates` WHERE `Country` LIKE '%" . $keyword . "%'");
    }
     foreach ($data as $row) {
            
            $date           = strtotime($row->Effective_date);
            $Effective_date = date('d-m-Y', $date); // converting date format
            $rate_array[]   = array(
                'country' => $row->Country,
                'description' => $row->Description,
                'rates' => $row->Rate,
                "date" => $Effective_date,
                "new_rates"=> $row->Rate,
                "minutes"=> null

            );
        }
        
        echo json_encode($rate_array);
        exit();
    // if (!empty($data)) {
    //     $count = count($data);
    //     if ($count > 1) { //if more than 1 recor found 
    //         if ($data[0]->Effective_date != '') {
    //             $date = strtotime($data[0]->Effective_date);
    //         } else {
    //             $date = strtotime($data[1]->Effective_date);
    //         }
    //         $Effective_date = date('d-m-Y', $date); // converting date format
            
    //         $rate_array  = array(
    //             'landline' => $data[0]->Rate,
    //             "mobile" => $data[1]->Rate,
    //             "date" => $Effective_date
    //         );
    //         $return_json = json_encode($rate_array);
            
    //         echo $return_json;
    //     } else {
    //         $rate_array  = array(
    //             'landline' => $data[0]->Rate,
    //             "mobile" => "",
    //             "date" => $Effective_date
    //         );
    //         $return_json = json_encode($rate_array);
            
    //         echo $return_json;
    //     }
        
        
    // } else {
    //     echo 0;
    // }
    // exit();
    
}

/******* GET DATA FOR ACCESS NUMBERS******************/
add_action('Get_access_numbers', 'get_access');
function get_access()
{
    global $wpdb;
    $get_data_result = $wpdb->get_results('SELECT * FROM `access`');
    if ($get_data_result > 0) {
        
        $i = 1;
        foreach ($get_data_result as $row) {
            
            $access_number = $row->access_numbers;
            $city          = $row->city;
            $state         = $row->state;
            $language      = $row->language;
            $data[]        = array(
                'access_number' => $access_number,
                "city" => $city,
                "state" => $state,
                "language" => $language,
                "sno" => $i
            );
            $i++;
        }
        
        return $data;
    }
    
    
    
}


/*******************Get rates for all countries ******************/
add_action('rates_all', 'get_all_rates');
function get_all_rates()
{
    global $wpdb;
    $get_data_result = $wpdb->get_results('SELECT * FROM `rates`');
    if ($get_data_result > 0) {
        
        
        foreach ($get_data_result as $row) {
            
            $date           = strtotime($row->Effective_date);
            $Effective_date = date('d-m-Y', $date); // converting date format
            $rate_array[]   = array(
                'country' => $row->Country,
                'description' => $row->Description,
                'rates' => $row->Rate,
                "date" => $Effective_date
            );
        }
        
        return $rate_array;
    }
    
    
    
    
}
/******************check pinless AUTHENTICATION********************/


add_action('wp_ajax_check_auth', 'check_auth');
add_action('wp_ajax_nopriv_check_auth', 'check_auth');

function check_auth()
{
    $loginSession = portaoneSession();
    $phone        = $_POST['username'];
    $password     = md5(trim($_POST['password']));
    // $data         = getCustomerByPhone($phone);
    $login_by_email = getCustomersLogin("email = '".$phone."'" ,"password = '".$password."'");
    $login_by_phone = getCustomersLogin("phone ='".$phone."'" ,"password = '".$password."'");

    $data = $login_by_email ;// if user found by his email address
    if(empty($login_by_email)){
        $data =  $login_by_phone;// if user found by his phone number
    }
    if (!empty($data)) {

      
        if ($password == $data[0]->password) {
            $account_details        = getAccountDetailsByPortaone($data[0]->loginID, $loginSession);
            // update 
            $customerID             = $data->customerID;
            $update_data            = array(
                                                'last_login' => date('Y-m-d H:i:s'),
                                                "balance" => $account_details->balance
                                         );
            
            $update                 = update($customerID, $update_data);
            //set data in session
            $_SESSION['last_login'] = date('Y-m-d H:i:s');
            $_SESSION['customerID'] = $data[0]->customerID;
            $_SESSION['firstName']  = $data[0]->first_name;
            $_SESSION['email']      = $data[0]->email;
            $_SESSION['phone']      = $data[0]->phone;
            echo 1;// password matched
        } else {
            echo 4;// incorrect password
        }
    } else {
        echo 0;// user not exist
    }
    
    exit();
    
}




/***************FUNCTION TO CREATE PORTAONE USER SESSION **************/
function portaoneSession()
{
    
    // Get Pinless Mode
    $pinlessDetails = getMode('pinlessMode', 'pinlessUsername', 'pinlessPassword');

    $portaOne = new PortaOneWS("SessionAdminService.wsdl");
    
    $loginSession = $portaOne->getSessionID(array(
        "user" => $pinlessDetails['username'],
        "password" => $pinlessDetails['password']
    ));
    
    // Set login session in Session variable
    $_SESSION['portaoneSession'] = $loginSession;
    
    return $loginSession;
}
/***************FUNCTION TO GET ACCOUNT DETAILS FROM PORTAONE **************/
function getAccountDetailsByPortaone($phone, $loginSession)
{
    $getAccountRequest = array(
        'login' => $phone
    );
    $api               = new PortaOneWS("AccountAdminService.wsdl");
    $result            = $api->getInfo($getAccountRequest, $loginSession);

    if (isset($result->error)) {
        return 0;
    } else {
        $account_details          = $result->account_info;
        $_SESSION['account_info'] =  $account_details;
      
        return $account_details;
    }
    
}

function getAccountDetailsFromPortaOne($phone)
{
    $loginSession       = portaoneSession();
    $getAccountResponse = getAccountDetailsByPortaone($phone, $loginSession); // Get Account details from Portaone                     
    if ($getAccountResponse)
        return $getAccountResponse;
    else
        return 0;
}


/*****************AJAX CALL TO VERIFY PHONE NUMBER *****************/
add_action('wp_ajax_verify_user_phone', 'verify_user_phone_byportaone');
add_action('wp_ajax_nopriv_verify_user_phone', 'verify_user_phone_byportaone');
function verify_user_phone_byportaone()
{
    $loginSession      = portaoneSession(); // create session
    $phone             = $_POST['phone']; // get phone number
    $getAccountRequest = array(
        'login' => $phone
    );
    $api               = new PortaOneWS("AccountAdminService.wsdl");
    
    $result             = $api->getInfo($getAccountRequest, $loginSession);
    $getAccountResponse = $result->account_info;
    $customerDetails    = getCustomerByPhone($phone);
    
    if (!empty($customerDetails) && !empty($getAccountResponse)) {
        //exist on portaone and exist on db
        //redirect to login page
        $redirect_rule = 1;
    } elseif (empty($customerDetails) && !empty($getAccountResponse)) {
        //exist on portaone but not in db
        $otp           = send_otp($phone);
        $redirect_rule = 2;
    } elseif (!empty($customerDetails) && empty($getAccountResponse)) {
        //exist on db but not in portaone
        $create_account = addNewPortaoneAccount($phone, $loginSession,$_SESSION['time']);// create user account on portaone.
        if($create_account){
         $redirect_rule = 3;
        }
    } else {
        //not in both means fresh customer
        $otp           = send_otp($phone);
        $redirect_rule = 4;
        
    }
    echo json_encode(array(
        'vonecall' => $customerDetails,
        'portaOne' => $getAccountResponse,
        'redirect_rule' => $redirect_rule,
        'smsStatus' => $otp['smsStatus'],
        'message' => $otp['message'],
        'otp' => $otp['otp'],
        "phone" => $phone
    ));
    exit();
}
/*******************AJAX CALL TO VERIFY OTP ****************/
add_action('wp_ajax_check_otp', 'check_user_otp');
add_action('wp_ajax_nopriv_check_otp', 'check_user_otp');
function check_user_otp()
{
    $phone = $_POST['otpNumber'];
    $otpString = $_POST['otp'];
    $otp_check = verify_OTP($Phone, $otpString);
    if($otp_check){
        echo 1;
    }else{
        echo 0;
    }
    
    
}


add_action('wp_ajax_register_user', 'register_new_customer');
add_action('wp_ajax_nopriv_register_user', 'register_new_customer');

function register_new_customer()
{
    //===========
    $phone      = $_POST['newPhone'];
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $email      = $_POST['email'];
    $password   = $_POST['password'];
    $cpassword   = $_POST['confirm_password'];
    $zip_code   = $_POST['zip_code'];
    
    /* PASSWORD AND CONFIRM PASSWORD MATCH  */
    if(isset($_POST['confirm_password'])){
        if($_POST['confirm_password'] != $_POST['password']){
        $result['code']=0;
        $result['error']="password and confirm pasword doesn't match!";
        echo json_encode($result);
        die;
        }
    }
    $createdDate  = date('Y-m-d H:i:s');
    $randomSubsId = trim('04156' . substr(number_format(time() * rand(), 0, '', ''), 0, 10));
    $subscriberId = preg_replace('/[\s]+/', '', $randomSubsId);
    
    //create pinless account
    $timezone     = $_POST['timezone'];
    $loginSession = portaoneSession();
    $portaone_add = addNewPortaoneAccount($phone, $loginSession, $timezone);
    if($portaone_add->i_account){
        $i_account= $portaone_add->i_account;
    }else{
        $i_account= 0;
    }
    /* already mail exist */
    if(isset($_POST['email'])){
        $email_check = getCustomersLogin("email='$email'");
//        print_r($email_check);
        if(count($email_check) > 0){
        $result['code']=0;
        $result['error']="Email id already exist!";
        echo json_encode($result);
        die;
        }
    }
    /* check otp code isset and valid otp code put*/
    if(isset($_POST['one_time_password'])){
        $isOTPexists = checkOtpForPhone($phone);
//        print_r($isOTPexists);
//        echo $isOTPexists[0]->otpString;
        if ($isOTPexists[0]->otpString != $_POST['one_time_password']) {
        $result['code']=0;
        $result['error']="Otp dose not match, please check and enter again!";
        echo json_encode($result);
        exit();
        }
    }
    // Add customer in db
    $data = array(
        'firstName' => $first_name,
        'lastName' => $last_name,
        'phone' => $phone,
        'loginID' => $phone,
        'password' => md5($password),
        'createdDate' => $createdDate,
        'subscriberID' => $subscriberId,
        'customerProduct' => 'pinless',
        'email' => $email,
        'zipCode' => $zip_code,
        'i_account' => $i_account,
        'balance' => 0
    );
    $customerID = add($data);
    if ($customerID) {
        //Update last login time
        $data                   = array(
            'last_login' => date('Y-m-d H:i:s')
        );
        $update                 = update($customerID, $data);
        // set variable's in session
        $_SESSION['customerID'] = $customerID;
        $_SESSION['firstName']  = $first_name;
        $_SESSION['email']      = $email;
        $_SESSION['phone']      = $phone;
        $account_details        = getAccountDetailsByPortaone($phone, $loginSession);
         $header = array();
            $header[] = "Bcc: 14singhi.arita@gmail.com \r\n";
            $header[] = 'Bcc: mobileapps.amit@gmail.com' . "\r\n";
        wp_mail($email,'Registration successfully','Hi'.$first_name.',<br> Thankyou for Registering on Vonecall',$header );
        echo 1;
    } else {
        $result['code']=0;
        $result['error']="Error in user registeration";
        echo json_encode($result);
    }
    
    exit();
    
}


function addNewPortaoneAccount($phone, $loginSession, $timezone)
{
    $password          = substr(uniqid('', true), -5); // Generate Random password
    $AddAccountRequest = array(
        "account_info" => array(
            'i_customer' => customerID,
            'billing_model' => '-1',
            'i_product' => product,
            'activation_date' => date('Y-m-d'),
            'id' => 'ani' . '1' . $phone,
            'balance' => '0',
            'opening_balance' => '0',
            // 'i_time_zone' => $timezone,
            'i_lang' => 'en',
            'login' => $phone,
            'password' => $password,
            'h323_password' => $password,
            'blocked' => 'N'
        )
    );
    
    $api    = new PortaOneWS("AccountAdminService.wsdl");
    $result = $api->addAccount($AddAccountRequest, $loginSession);
    return $result;
}


/*******************AJAX CALL TO VERIFY OTP ****************/
add_action('wp_ajax_update_profile', 'Update_user_detail');
add_action('wp_ajax_nopriv_update_profile', 'Update_user_detail');
function Update_user_detail(){
   
          $wp_upload_dir = WP_PROFILE_UPLOAD;
     /*** Upload file *********/
         $uploadedfile      = $_FILES['user_image']; // GET UPLOADED FILE
         $upload_overrides  = array( 'test_form' => false ); // SET UPLOAD OVERRIDE FALSE

         /********** FUNCTION TO UPLOAD IMAGE ***********/
         $movefile          = wp_handle_upload( $uploadedfile, $upload_overrides ); 
            if ( $movefile && !isset( $movefile['error'] ) ) {
                 $image = explode('uploads/', $movefile['file']);
               $filename =  $image[1];
            } else {
                /**
                 * Error generated by _wp_handle_upload()
                 * @see _wp_handle_upload() in wp-admin/includes/file.php
                 */
                $movefile['error'];
                $filename = $_POST['old_image'];
            }
        $data           = array("firstName"=>$_POST['first_name'], "lastName"=>$_POST['last_name'],
                                "email" => $_POST['email'],  "phone"=>$_POST['phone'],
                                "zipCode"=>$_POST['zip_code'], "city" => $_POST['city'],
                                "country" => $_POST['country'], "stateID" => $_POST['state'] ,
                                "customer_image" => $filename
                                );
        $cutomerID      = $_POST['customerID'];
        $update_user_in_db = update($cutomerID , $data); // update user data in database
        $portaone_update = updateAccountOnPortaone($_POST); // update data in portaone account of user
        if($portaone_update){
            echo 1;
        }else{
            echo 0;
        }
    exit();
}


/*********Update Password **********/
 add_action('wp_ajax_update_pass', 'Update_user_pass');
add_action('wp_ajax_nopriv_update_pass', 'Update_user_pass');
function Update_user_pass(){
   
   $old_password    = trim($_POST['session_pass']);
   $new_pass        = $_POST['new_pass'];
   $current_pass     = trim($_POST['old_pass']);
    if($old_password == md5($current_pass)){
        $data = array("password" => md5($current_pass) );
        $cutomerID      = $_POST['customerID'];
       $result =  update($cutomerID , $data);
       if($result)
        echo 1;
       else
        echo 0;

    }else{
        echo 2;
    }

exit();
    


}


/*******Update detail  at portaone ************/
 function updateAccountOnPortaone($update_field)
    {
        $account_id = $_SESSION['account_info']->i_account;
        
        $data= array("firstname" => $update_field['first_name'] ,
                      "lastname" => $update_field['last_name'] ,
                       "city" => $update_field['city'] ,
                        "state" => $update_field['state'] ,
                        "email"=> $update_field["email"],
                         "country" => $update_field['country'],
                         'i_account'=> $account_id
                       );

        $loginSession          = $_SESSION['portaoneSession'];
        $getAccountListRequest = array("account_info" => $data);
        $api    = new PortaOneWS("AccountAdminService.wsdl");
        $result = $api->updateAccount($getAccountListRequest, $loginSession);
        if($result){
            return true;
        }else{
            return false;
        }
    }
/***********prepaynation function*********/
function admin_balance(){
        
        // getPPN Mode
        $getPPN    = getMode('ppnMode', 'ppnUsername', 'ppnPassword');
        $username = $getPPN['username'];
        $password = $getPPN['password'];
        if($getPPN['mode'] == 'test'){
            $sandbox = TRUE;
        }else{
            $sandbox = FALSE;
        }
        // getPPN Mode END          
        $api = new PrepaynationWS($username, $password, $sandbox);  
       
        $result1     = $api->get_balance();
       $result = $api->getCarrierList();
        //$result = $api->getSkuList();
       echo"<pre>";
       // $carrier = array( 'carrierId' => 630 );
       //  $result  = $api->getSkuListByCarrier( $carrier );
       //  $carriers = $api->getCarrierInfoByMobileNumber('524521597722');
        echo  $client->__getLastRequest;
        print_r($carriers);
         print_r($result);
       
    }

/***************get_product*******/
add_action('wp_ajax_get_calling_history', 'get_calling_history');
add_action('wp_ajax_nopriv_get_calling_history', 'get_calling_history');
function get_calling_history(){

    if (!empty($_SESSION['portaoneSession'])){
                    $loginSession = $_SESSION['portaoneSession'];
                } else {
                    $loginSession = portaoneSession();
                }
                $from_date = $_POST['from_date'];
                $to_date  = $_POST['to_date'];
                //$getAccountDetails = $this->getAccountDetailsByPortaone($phone, $loginSession);
                $xcdrRequest = array('i_account' => $_SESSION['account_info']->i_account, 
                                     'i_service' => 3,
                                     'from_date' => date('Y-m-d 00:00:00', strtotime( $from_date)),
                                     'to_date'   => date('Y-m-d 11:59:59', strtotime( $to_date))                                                                            
                                );
    
                $api    = new PortaOneWS("AccountAdminService.wsdl");
                $result = $api->getxCdr($xcdrRequest, $loginSession);
                 
                 if(!empty($result->xdr_list)){
                         $i=1;
                    foreach($result->xdr_list as $data){
                      $call_duration = $data->unix_disconnect_time - $data->unix_connect_time ;// call duration by unix time out
                     $call_date = date("d-m-Y H:i:s",$data->unix_connect_time);
                        $paid_amount = $data->charged_amount;
                     $return_array[] = array("sno"=>$i,"destined_number"=>$data->CLD ,"charged_amount"=> $paid_amount,"call_duration"=>date('H:i:s',$call_duration),"call_on" => $call_date);
                    $i++;  
                 }
                
             echo  json_encode($return_array);
             }
             exit();
}
/****************get_transaction **********/
add_action('wp_ajax_get_transaction_history', 'get_transaction_history');
add_action('wp_ajax_nopriv_get_transaction_history', 'get_transaction_history');
function get_transaction_history(){
    
     if (!empty($_SESSION['portaoneSession'])){
                    $loginSession = $_SESSION['portaoneSession'];
                } else {
                    $loginSession = portaoneSession();
                }
                $from_date = $_POST['from_date'];
                $to_date  = $_POST['to_date'];
                $xcdrRequest = array('i_account' => $_SESSION['account_info']->i_account, 
                                     'i_service' => 2, // (Recharge/transaction)
                                     'from_date' => date('Y-m-d 00:00:00', strtotime( $from_date)),
                                     'to_date'   => date('Y-m-d 11:59:59', strtotime( $to_date))                                                                            
                                );
    
                $api    = new PortaOneWS("AccountAdminService.wsdl");
                $result = $api->getxCdr($xcdrRequest, $loginSession);
                 
                 if(!empty($result->xdr_list)){
                         $i=1;
                    foreach($result->xdr_list as $data){
                          $paid_amount = -($data->charged_amount).".00";
                     $call_date = date("d-m-Y H:i:s",$data->unix_connect_time);// date of payment
                     $return_array[] = array("sno"=>$i,"payment_type"=>$data->CLD ,"charged_amount"=>$paid_amount,"call_on" => $call_date);
                    $i++;  
                 }
                
             echo  json_encode($return_array);
             }
             exit();
            
}
/***********************send sms **************/
function send_sms($to,$body){

 
    $sms = new twilio;
   $data = $sms->sms($to,$body);
   if($data === true){
    return "message sent Successfully";
   }else{
    return "Error in message ";
   }
  
}


/**************Recharge and pay ************/

add_action('wp_ajax_do_recharge', 'payment');
add_action('wp_ajax_nopriv_do_recharge', 'payment');

function payment(){

       // initialize PortaOne library
    $api              = new PortaOneWS("AccountAdminService.wsdl");
    $currentBalance   = $_SESSION['account_info']->balance;             // Get Current balance
    $customerDetails  = getCustomerByPhone($_SESSION['phone']); // Get Customer Details
    $loginSession     = $_SESSION['portaoneSession'];
    $amount           = $_POST['amount'];
    $phone            = $_SESSION['phone'];
    $createdDate      = date('Y-m-d H:i:s');
    $ip_address       = $_SERVER['REMOTE_ADDR'];

    // initialize Authorize library
    $transaction      = new authorize;

    if($_REQUEST['saved_card'] == 0){
   
    // set customer card detail
    $card_details     =  array('card_number' => $_REQUEST['cardNumber'],
                        'exp_date' => $_REQUEST['exp_date']
                        
                        );

// create customer payment profile 
    $customer         = $transaction->createCustomerProfile($card_details);
    $paymentProfile   = $customer['payment_profile'];
    $profileId        = $customer['profileId'];

   // charge customer transaction                                                         
                
    $payment_details  = $transaction->chargeCustomerProfile($profileId, $paymentProfile, $amount);

     // Update portaone balance
     if(!empty($payment_details)){
      $trans_id = $payment_details['trans_id'];
        if(empty($payment_details['trans_id'])){
             $trans_id = 0;
         }
        $responseArray    = array(
                                        'x_card_num'=>$_REQUEST['cardNumber'], // Visa
                                        'x_description'=> 'Vonecall customer account from web',
                                        'x_amount'=> $amount,
                                        'x_first_name'=> $_REQUEST['accountName'],
                                        'x_phone'=> $phone,
                                        'customerID'=>$_SESSION['customerID'],
                                        'transactionID'=> $trans_id,
                                        'approvalCode'=>$payment_details['approved'],
                                        'createdOn'=>$createdDate,
                                        'x_customer_ip'=> $ip_address
                                        );
     
                            
     $addPinlessTxn        = addPinlessTxn($responseArray);    
     $updateAccountRequest = array('i_account' =>  $_SESSION['account_info']->i_account,
                                    'action'    => 'Manual payment',
                                    'amount'    =>   $amount                                       
                                );
            
     $result               = $api->rechargeAccount($updateAccountRequest, $loginSession);

     $_SESSION['account_info']->balance = $result->balance;
   // $result = array();
     if($result->error){
                    $warning = ($result->error) ? $result->error : 'Invalid Account ID';
                    echo 0; exit();
    }else{


                    $createdDate  = date('Y-m-d H:i:s');
                   // Get Pinless product ======
                    $products = getVProductsByCommission(array('ac.agentID' => 388, 'vp.terminateDate' => NULL, 'vp.vproductCategory' => 'Pinless'));
                    
                    // Get Product Details by ProductID                 
                    $getCommission = getCommissionByProductID(388, $products[0]->vproductID);
              
                    // calculate RYD Commission
                    $rydCommission = 0; 
                    if($getCommission)          
                        $rydCommission =  $amount * $getCommission[0]->vproductAdminCommission / 100;
                                    
                    //calculate store commission
                    $storeCommission = 0;
                    if ($getCommission) {
                        $storeCommission = $amount * $getCommission[0]->commissionRate / 100;
                    }
                    
                    //calculate Distributor commission
                       $distCommission =  $amount * ($getCommission[0]->vproducttotalCommission - ($getCommission[0]->commissionRate + $getCommission[0]->vproductAdminCommission)) / 100;
                    
                    //Check Subdist Parent Account
                    $getParentDistributor = getAgent(388);

                    $parentAgentID =387;
                    if($getParentDistributor->agentTypeID == 4){
                        $parentAgentID = $getParentDistributor->parentAgentID;
                    }
                                                        
                    //insert to payment
                    $dataPayment = array(
                        'customerID'            => $_SESSION['customerID'],
                        'seqNo'                 => getSeqNo($customerDetails->customerID),
                        'paymentMethodID'       => 1,
                        'chargedAmount'         => $amount,
                        'enteredBy'             => 'vcsonline',
                        'chargedBy'             => 'STORE SITE',
                        'productID'             => $getCommission[0]->vproductID,
                        'agentID'               => 388,
                        'storeCommission'       => $storeCommission,
                        'accountRepID'          => $parentAgentID,
                        'accountRepCommission'  => $distCommission,
                        'comment'               => 'Pinless: ',
                        'createdDate'           => $createdDate,
                        'adminCommission'       => $rydCommission
                    );  
               
                    //insert customer  payment details            
                    add_payment($dataPayment);
                      // update balance customer
                    $updateBalance  = updateBalance($_SESSION['customerID'], $amount); 

                   //insert Agent   payment details 
                   
                    // $chargedDiscount      = 0;
                    // $accountRepCommission = 0;
                    // $data = array(
                    //     'agentID'               =>  388,
                    //     'transactionID'         => $trans_id,
                    //     'chargedAmount'         => $amount,
                    //     'chargedDiscount'       => $chargedDiscount,
                    //     'paymentMethodID'       => 1,
                    //     'enteredBy'             => 'vcsonline - store',
                      
                    //     'paidTo'                => 'System Admin',
                    //     'comment'               => 'payment by customer portal',//'payment with credit card',
                    //     'accountRepID'          => $parentAgentID,
                    //     'accountRepCommission'  => 0,
                    //     'collectedByCompany'    => 1,
                    //     'dateCollectedByCompany'=> $createdDate,
                    //     'createdDate'           => $createdDate
                    // );

                    // addAgentPayment($data);// agent payment details 

                    //update balance agent
                    $totalCredit = -$amount + $storeCommission;
                   $updateAgent_balance = updateagentBalance(388, $totalCredit);
   
     if($_REQUEST['future_save'] == 1){  
                                        
     $cardArray     = array('sa_card_number'=>$_REQUEST['cardNumber'],
                            'sa_card_exp'=>$_REQUEST['exp_date'],
                             'sa_card_cvv'=>$_REQUEST['cvv'],
                            'customerID'=>$_SESSION['customerID']
                        );
     $save_my_card  = saveMyCard($cardArray);   
     } // future save
    
   }//no error else
    echo 1;
 }// payment
 else{
        echo 2;
    }
}else{
    //if saved card
                 
    $sa_card_id     = $_POST['saved_card'];
    $save_card_data = getSavedCardByCardID($sa_card_id);
    $card_number    = $save_card_data[0]->sa_card_number;
    $sa_card_exp    = $save_card_data[0]->sa_card_exp;

    $card_details     =  array('card_number'     => $save_card_data[0]->sa_card_number,
                        'exp_date' => $save_card_data[0]->sa_card_exp
                        );
                    
    
    // Authorize.net lib
    $customer         = $transaction->createCustomerProfile($card_details);   
    $paymentProfile   = $customer['payment_profile'];
    $profileId        = $customer['profileId'];

   // charge customer transaction                                                                        
    $payment_details  = $transaction->chargeCustomerProfile($profileId, $paymentProfile, $amount);
   
     // Update portaone balance
     if(!empty($payment_details)){
         $trans_id = $payment_details['trans_id'];
         if(empty($payment_details['trans_id'])){
           $trans_id = 0;
          }
        $responseArray = array(
                                        'x_card_num'=> $card_number, // Visa
                                        'x_description'=> 'Vonecall customer account from web',
                                        'x_amount'=>  $amount,
                                        'x_phone'=> $phone,
                                        'customerID'=>$_SESSION['customerID'],
                                        'transactionID'=>$trans_id,
                                        'approvalCode'=>$payment_details['approved'],
                                        'createdOn'=>$created_on
                                        );
                            
     $addPinlessTxn = addPinlessTxn($responseArray); //add  transaction detail   
     // recharge portaone account
     $updateAccountRequest = array('i_account' => $_SESSION['account_info']->i_account,
                                    'action'    => 'Manual payment',
                                    'amount'    =>  $amount
                                     );
            
    $result = $api->rechargeAccount($updateAccountRequest, $loginSession);
   
      $_SESSION['account_info']->balance = $result->balance;
    //  $result = array();
     if($result->error){
                    $warning = ($result->error) ? $result->error : 'Invalid Account ID';
                     echo 0; exit();
     }else{

                    $createdDate  = date('Y-m-d H:i:s');
                        
                    // Get Pinless product ======
                    $products = getVProductsByCommission(array('ac.agentID' => 388, 'vp.terminateDate' => NULL, 'vp.vproductCategory' => 'Pinless'));
                    
                    // Get Product Details by ProductID                 
                    $getCommission = getCommissionByProductID(388, $products[0]->vproductID);
              
                    // calculate RYD Commission
                    $rydCommission = 0; 
                    if($getCommission)          
                        $rydCommission =  $amount * $getCommission[0]->vproductAdminCommission / 100;
                                    
                    //calculate store commission
                    $storeCommission = 0;
                    if ($getCommission) {
                        $storeCommission = $amount * $getCommission[0]->commissionRate / 100;
                    }
                    
                    //calculate Distributor commission
                       $distCommission =  $amount * ($getCommission[0]->vproducttotalCommission - ($getCommission[0]->commissionRate + $getCommission[0]->vproductAdminCommission)) / 100;
                    
                    //Check Subdist Parent Account
                    $getParentDistributor = getAgent(388);

                    $parentAgentID =387;
                    if($getParentDistributor->agentTypeID == 4){
                        $parentAgentID = $getParentDistributor->parentAgentID;
                    }
                                                        
                    //insert to payment
                    $dataPayment = array(
                        'customerID'            => $_SESSION['customerID'],
                        'seqNo'                 => getSeqNo($customerDetails->customerID),
                        'paymentMethodID'       => 1,
                        'chargedAmount'         => $amount,
                        'enteredBy'             => 'vcsonline',
                        'chargedBy'             => 'STORE SITE',
                        'productID'             => $getCommission[0]->vproductID,
                        'agentID'               => 388,
                        'storeCommission'       => $storeCommission,
                        'accountRepID'          => $parentAgentID,
                        'accountRepCommission'  => $distCommission,
                        'comment'               => 'Pinless: ',
                        'createdDate'           => $createdDate,
                        'adminCommission'       => $rydCommission
                    );  
               
                    //insert customer  payment details            
                    add_payment($dataPayment);
                      // update balance customer
                    $updateBalance  = updateBalance($_SESSION['customerID'], $amount); 

               // //insert Agent   payment details 
               //      $trans_id = $payment_details['trans_id'];
               //      if(empty($payment_details['trans_id'])){
               //          $trans_id = 0;
               //      }
               //      $chargedDiscount      = 0;
               //      $accountRepCommission = 0;
               //      $data = array(
               //          'agentID'               =>  388,
               //          'transactionID'         => $trans_id,
               //          'chargedAmount'         => $amount,
               //          'chargedDiscount'       => $chargedDiscount,
               //          'paymentMethodID'       => 1,
               //          'enteredBy'             => 'vcsonline - store',
                      
               //          'paidTo'                => 'System Admin',
               //          'comment'               => 'payment by customer portal',//'payment with credit card',
               //          'accountRepID'          => $parentAgentID,
               //          'accountRepCommission'  => 0,
               //          'collectedByCompany'    => 1,
               //          'dateCollectedByCompany'=> $createdDate,
               //          'createdDate'           => $createdDate
               //      );

               //      addAgentPayment($data);// agent payment details 

                    //update balance agent
                    $totalCredit = -$amount + $storeCommission;
                   $updateAgent_balance = updateagentBalance(388, $totalCredit);
                  
   
    
   }//no error else
    echo 1;
 }// payment
 else{
        echo 2;
    }
} // else part close 
exit();
}//function close

/****************get card Detail **********/
add_action('wp_ajax_getcard', 'get_card_detail');
add_action('wp_ajax_nopriv_getcard', 'get_card_detail');
 function get_card_detail(){

    $sa_card_id=$_POST['card_id'];
    $data = getSavedCardByCardID($sa_card_id);
    echo json_encode($data);
    exit();
 }



/*******contact form submission ********/

add_action('wp_ajax_send_contact_mail', 'contact_form');
add_action('wp_ajax_nopriv_send_contact_mail', 'contact_form');
function contact_form(){
  if(isset($_POST["customer_email"])){
          
              // get the posted data
            $name    = $_POST["customer_name"];// customer name
            $email   = $_POST["customer_email"];// customer request back email
            $subject = $_POST["mail_subject"];// customer asked subject
            $message = $_POST["message"];// customer message

            $textMessage = "Hi Admin, <br> $name has sent a contact back request.<br>Title:- $subject!<br>Email - $email <br>Message - $message";
            $subject    = 'Contact back Request from vonecall';
            $header     = array();
            $header[]   = "Bcc: 14singhi.arita@gmail.com \r\n";
            $admin_mail = 'billing@rydtechnologies.com';
            $data       = wp_mail($admin_mail,$subject, $textMessage,$header);
           
           if($data){
            echo 1;
           }else{
             echo 0;
           }
            exit();

        }// customer email

}

/****************logout********/
add_action('wp_ajax_logout', 'logout_session');
add_action('wp_ajax_nopriv_logout', 'logout_session');

function logout_session(){
  session_start() ; 
  session_destroy() ;
   echo 1;
  
exit();

}



/****************app url send phone no ********/
add_action('wp_ajax_app_url_send', 'app_url_send');
add_action('wp_ajax_nopriv_app_url_send', 'app_url_send');
function app_url_send(){
    if(isset($_POST['mobile_no'])){
        $phone =$_POST['mobile_no'];
        $phoneEmail = getPhoneEmail($phone);
        if ($phoneEmail != 0) {
            $textMessage = 'https://www.google.co.in/';
            $subject     = 'App url';
            $header = array();
            $header[] = "Bcc: 14singhi.arita@gmail.com \r\n";
            $header[] = 'Bcc: mobileapps.amit@gmail.com' . "\r\n";
            $data = wp_mail($phoneEmail,$subject, $textMessage,$header);
            //$data = wp_mail("nikkuagrawal1994@gmail.com",$subject, $textMessage);
            //print_r($data);
            $result['sentSMS'] = "success";
            $result['message'] = "sms send successfully!";
        }else{
            $result['sentSMS'] = "error";
            $result['message'] = "Some error!";
        }
        echo json_encode($result);
    }
    exit();
}

/****************app url send phone no ********/
add_action('wp_ajax_resend_otp', 'resend_otp');
add_action('wp_ajax_nopriv_resend_otp', 'resend_otp');
function resend_otp(){
    if(isset($_POST['mobile_no'])){
        $phone =$_POST['mobile_no'];
        $otp = send_otp($phone);
        echo json_encode($otp);
    }
    exit();
}

/**************** forgot password code ********/
add_action('wp_ajax_forgot_password', 'forgot_password');
add_action('wp_ajax_nopriv_forgot_password', 'forgot_password');
function forgot_password(){
    if(isset($_POST['email'])){
        $email = $_POST['email'];
        $data = getCustomersLogin("email='$email'");
//        print_r($data);
        if(count($data) > 0){
            $fname = $data[0]->firstName;
            $password = mt_rand(10000000,99999999);
            $customerID = $data[0]->customerID;
            $pdata= array(
                'password' => md5($password)
            );
            update($customerID, $pdata);
            $textMessage = "Hi $fname, <br> your password is reset sucessfully.<br>Please login and change password!<br>Email - $email <br>password - $password";
            $subject     = 'Forgot Password';
            $header = array();
            $header[] = "Bcc: 14singhi.arita@gmail.com \r\n";
            $header[] = 'Bcc: mobileapps.amit@gmail.com' . "\r\n";
            $data = wp_mail($email,$subject, $textMessage,$header);
            
        
            $result['code']=1;
            $result['message']="Please check your mail!";
            echo json_encode($result);
            exit();
        }else{
            $result['code']=0;
            $result['message']="Email id does not exist!";
            echo json_encode($result);
            exit();
        }
    }
    exit();
}