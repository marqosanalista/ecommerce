<?php
/**
 * Ecommerce Center functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Ecommerce Center
 */

function ecommerce_center_enqueue_styles() {
    $parentcss = 'ecommerce-zone-style';
    $theme = wp_get_theme(); wp_enqueue_style( $parentcss, get_template_directory_uri() . '/style.css', array(), $theme->parent()->get('Version'));
    wp_enqueue_style( 'ecommerce-center-style', get_stylesheet_uri(), array( $parentcss ), $theme->get('Version'));
}

add_action( 'wp_enqueue_scripts', 'ecommerce_center_enqueue_styles' );

/**
 * Enqueue theme color style.
 */
function ecommerce_center_theme_color() {

    $theme_color_css = '';
    $ecommerce_zone_theme_color = get_theme_mod('ecommerce_zone_theme_color');

    $theme_color_css = '
        span.cart-value,#button,.main-navigation .menu > li > a:hover,.pro-button a:hover, .woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover,.woocommerce ul.products li.product .onsale, .woocommerce span.onsale,.woocommerce .woocommerce-ordering select,.woocommerce-account .woocommerce-MyAccount-navigation ul li,.wp-block-button__link,.comment-respond input#submit{
            background: '.esc_attr($ecommerce_zone_theme_color).';
        }
        {
            color: '.esc_attr($ecommerce_zone_theme_color).';
        }
        .wp-block-quote, .wp-block-quote:not(.is-large):not(.is-style-large), .wp-block-pullquote{
            border-color: '.esc_attr($ecommerce_zone_theme_color).' !important;
        }
        .serv-box:hover{
            background: transparent;
        }
    ';
    wp_add_inline_style( 'ecommerce-center-style',$theme_color_css );

}
add_action( 'wp_enqueue_scripts', 'ecommerce_center_theme_color' );

function ecommerce_center_string_limit_words($string, $word_limit) {
	$words = explode(' ', $string, ($word_limit + 1));
	if(count($words) > $word_limit)
	array_pop($words);
	return implode(' ', $words);
}

function ecommerce_center_customize_register($wp_customize){

	// Blog Section
    $wp_customize->add_section( 'ecommerce_center_blog_section' , array(
        'title'      => __( 'Blog Settings', 'ecommerce-center' ),
        'priority'   => null
    ) );

    $wp_customize->add_setting('ecommerce_center_blog_section_title',array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field'
    )); 
    $wp_customize->add_control('ecommerce_center_blog_section_title',array(
        'label' => esc_html__('Section Title','ecommerce-center'),
        'section' => 'ecommerce_center_blog_section',
        'setting' => 'ecommerce_center_blog_section_title',
        'type'  => 'text'
    ));

    $categories = get_categories();
    $cat_post = array();
    $cat_post[]= 'select';
    $i = 0; 
    foreach($categories as $category){
        if($i==0){
            $default = $category->slug;
            $i++;
        }
        $cat_post[$category->slug] = $category->name;
    }

    $wp_customize->add_setting('ecommerce_center_blog',array(
        'default'   => 'select',
        'sanitize_callback' => 'ecommerce_zone_sanitize_choices',
    ));
    $wp_customize->add_control('ecommerce_center_blog',array(
        'type'    => 'select',
        'choices' => $cat_post,
        'label' => __('Select Category to display Services','ecommerce-center'),
        'section' => 'ecommerce_center_blog_section',
    ));
}
add_action('customize_register', 'ecommerce_center_customize_register');

if ( ! function_exists( 'ecommerce_center_setup' ) ) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function ecommerce_center_setup() {

        add_theme_support( 'responsive-embeds' );

        add_theme_support( 'woocommerce' );
        
        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support( 'title-tag' );

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support( 'post-thumbnails' );

        add_image_size('ecommerce-center-featured-header-image', 2000, 660, true);

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         * to output valid HTML5.
         */
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ) );

        // Set up the WordPress core custom background feature.
        add_theme_support( 'custom-background', apply_filters( 'ecommerce_zone_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        ) ) );

        /**
         * Add support for core custom logo.
         *
         * @link https://codex.wordpress.org/Theme_Logo
         */
        add_theme_support( 'custom-logo', array(
            'height'      => 50,
            'width'       => 50,
            'flex-width'  => true,
        ) );

        add_editor_style( array( '/editor-style.css' ) );

        add_theme_support( 'align-wide' );

        add_theme_support( 'wp-block-styles' );
    }
endif;
add_action( 'after_setup_theme', 'ecommerce_center_setup' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function ecommerce_center_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'ecommerce-center' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here.', 'ecommerce-center' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h5 class="widget-title">',
        'after_title'   => '</h5>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Home Sidebar', 'ecommerce-center' ),
        'id'            => 'sidebar-2',
        'description'   => esc_html__( 'Add widgets here.', 'ecommerce-center' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h5 class="widget-title">',
        'after_title'   => '</h5>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Column 1', 'ecommerce-center' ),
        'id'            => 'ecommerce-center-footer1',
        'description'   => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h5 class="footer-column-widget-title">',
        'after_title'   => '</h5>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Column 2', 'ecommerce-center' ),
        'id'            => 'ecommerce-center-footer2',
        'description'   => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h5 class="footer-column-widget-title">',
        'after_title'   => '</h5>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Column 3', 'ecommerce-center' ),
        'id'            => 'ecommerce-center-footer3',
        'description'   => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h5 class="footer-column-widget-title">',
        'after_title'   => '</h5>',
    ) );
}
add_action( 'widgets_init', 'ecommerce_center_widgets_init' );
