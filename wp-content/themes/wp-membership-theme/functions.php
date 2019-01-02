<?php
/**
 * wp-membership-theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package wp-membership-theme
 */

if ( ! function_exists( 'wp_membership_theme_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function wp_membership_theme_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on wp-membership-theme, use a find and replace
		 * to change 'wp-membership-theme' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'wp-membership-theme', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );
 
		add_theme_support( 'title-tag' );
 
		add_theme_support( 'post-thumbnails' );
 
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'wp-membership-theme' ),
		) );
 
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );
 
		add_theme_support( 'custom-background', apply_filters( 'wp_membership_theme_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );
 
		add_theme_support( 'customize-selective-refresh-widgets' );
 
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'wp_membership_theme_setup' );
 
function wp_membership_theme_content_width() { 
	$GLOBALS['content_width'] = apply_filters( 'wp_membership_theme_content_width', 640 );
}
add_action( 'after_setup_theme', 'wp_membership_theme_content_width', 0 );
 
/**
 * Enqueue scripts and styles.
 */
function wp_membership_theme_scripts() {
	
	wp_enqueue_style( 'wp-membership-theme-style', get_stylesheet_uri() );
    
    wp_enqueue_style( 'wp-membership-theme-styles-custom', get_template_directory_uri() .'/css/wp-membership-theme.min.css' );
  
}

add_action( 'wp_enqueue_scripts', 'wp_membership_theme_scripts' );
 
require get_template_directory() . '/inc/custom-header.php';
 
require get_template_directory() . '/inc/template-tags.php';
 
require get_template_directory() . '/inc/template-functions.php';
 
require get_template_directory() . '/inc/customizer.php';
 