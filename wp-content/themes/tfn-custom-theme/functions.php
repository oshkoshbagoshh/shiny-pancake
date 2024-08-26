<?php
function tfn_theme_setup() {
    // Add theme support for various features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'tfn-custom-theme'),
    ));
}
add_action('after_setup_theme', 'tfn_theme_setup');

function tfn_enqueue_scripts() {
    // Enqueue styles
    wp_enqueue_style('tfn-style', get_stylesheet_uri());
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Figtree:wght@400;700&family=Rubik:wght@400;700&display=swap');

    // Enqueue scripts
    wp_enqueue_script('tfn-script', get_template_directory_uri() . '/js/script.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'tfn_enqueue_scripts');

