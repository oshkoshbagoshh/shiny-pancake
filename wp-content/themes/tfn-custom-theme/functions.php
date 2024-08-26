<?php
function tfn_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');

    register_nav_menus(array(
        'primary' => __('Primary Menu', 'tfn-custom-theme'),
    ));
}
add_action('after_setup_theme', 'tfn_theme_setup');

function tfn_enqueue_scripts() {
    // Enqueue styles
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');
    wp_enqueue_style('tfn-style', get_stylesheet_uri());
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Figtree:wght@400;700&family=Rubik:wght@400;700&display=swap');

    // Enqueue scripts
    wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);
    wp_enqueue_script('tfn-script', get_template_directory_uri() . '/js/script.js', array('jquery', 'bootstrap'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'tfn_enqueue_scripts');

// Bootstrap 5 wp_nav_menu walker
class Bootstrap_5_WP_Nav_Menu_Walker extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= "<ul class='dropdown-menu'>";
    }

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $item_html = '';
        parent::start_el($item_html, $item, $depth, $args);

        if ($item->is_dropdown && $depth === 0) {
            $item_html = str_replace('<a', '<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown"', $item_html);
            $item_html = str_replace('</a>', '</a>', $item_html);
        } elseif ($depth === 0) {
            $item_html = str_replace('<a', '<a class="nav-link"', $item_html);
        }

        if ($item->is_dropdown) {
            $item_html = str_replace('<li', '<li class="nav-item dropdown"', $item_html);
        }

        $output .= $item_html;
    }

    function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output) {
        if ($element->current) {
            $element->classes[] = 'active';
        }

        $element->is_dropdown = !empty($children_elements[$element->ID]);

        parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }
}
