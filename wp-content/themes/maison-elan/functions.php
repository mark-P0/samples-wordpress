<?php
if (!defined('ABSPATH')) { exit; }

function elan_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);
    register_nav_menus(['primary' => __('Primary navigation', 'maison-elan')]);
}
add_action('after_setup_theme', 'elan_theme_setup');

function elan_assets() {
    wp_enqueue_style('maison-elan', get_stylesheet_uri(), [], '0.1.0');
    wp_enqueue_script('maison-elan', get_template_directory_uri() . '/assets/js/theme.js', [], '0.1.0', true);
}
add_action('wp_enqueue_scripts', 'elan_assets');

function elan_favicon_tags() {
    $favicon = get_template_directory_uri() . '/assets/images/favicon.svg';
    printf("<link rel=\"icon\" href=\"%s\" type=\"image/svg+xml\">\n", esc_url($favicon));
    printf("<link rel=\"alternate icon\" href=\"%s\">\n", esc_url($favicon));
}
add_action('wp_head', 'elan_favicon_tags', 1);
add_action('admin_head', 'elan_favicon_tags', 1);

function elan_register_content_types() {
    $types = [
        'elan_service' => ['Services', 'Service', 'dashicons-heart'],
        'elan_specialist' => ['Specialists', 'Specialist', 'dashicons-businessperson'],
        'elan_package' => ['Packages', 'Package', 'dashicons-tickets-alt'],
        'elan_testimonial' => ['Testimonials', 'Testimonial', 'dashicons-format-quote'],
    ];
    foreach ($types as $slug => $data) {
        register_post_type($slug, [
            'labels' => ['name' => $data[0], 'singular_name' => $data[1], 'add_new_item' => 'Add New ' . $data[1], 'edit_item' => 'Edit ' . $data[1]],
            'public' => true,
            'show_in_rest' => true,
            'menu_icon' => $data[2],
            'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'],
            'has_archive' => false,
            'rewrite' => false,
        ]);
    }
}
add_action('init', 'elan_register_content_types');

function elan_customize($wp_customize) {
    $wp_customize->add_section('elan_home', [
        'title' => __('Maison Élan — Homepage', 'maison-elan'),
        'priority' => 30,
    ]);
    $fields = [
        'hero_title' => ['Hero title', 'Refined Beauty, Thoughtfully Curated'],
        'hero_copy' => ['Hero copy', 'Personalized treatments in a serene, luxurious space designed to enhance your natural beauty and wellbeing.'],
        'hero_cta' => ['Hero CTA', 'Book your consultation'],
        'services_title' => ['Services heading', 'Thoughtful Treatments. Visible Results.'],
        'about_title' => ['About heading', 'A Sanctuary for Timeless Beauty'],
        'about_copy' => ['About copy', 'Maison Élan is a boutique beauty and aesthetics studio where science meets sophistication. Our curated treatments and premium products are designed to enhance your natural beauty in a calm, luxurious setting.'],
        'specialists_title' => ['Specialists heading', 'Experts in Enhancing Your Natural Beauty'],
        'packages_title' => ['Packages heading', 'Curated Packages for Every Skin & Self-Care Goal'],
        'visit_title' => ['Visit heading', 'We Can’t Wait to Welcome You'],
        'address' => ['Address', '123 Belleview Avenue, Toronto, ON M5R 2L3'],
        'phone' => ['Phone', '(647) 555-1234'],
        'instagram' => ['Instagram', '@maison.elan.studio'],
    ];
    foreach ($fields as $id => $field) {
        $wp_customize->add_setting('elan_' . $id, ['default' => $field[1], 'sanitize_callback' => 'sanitize_text_field']);
        $wp_customize->add_control('elan_' . $id, ['label' => __($field[0], 'maison-elan'), 'section' => 'elan_home', 'type' => ($id === 'hero_copy' || $id === 'about_copy') ? 'textarea' : 'text']);
    }
}
add_action('customize_register', 'elan_customize');

function elan_mod($key, $fallback) {
    return get_theme_mod('elan_' . $key, $fallback);
}

function elan_menu_fallback() {
    $items = [
        ['Home', '#top'], ['Services', '#services'], ['Specialists', '#specialists'], ['Pricing', '#pricing'], ['Studio', '#studio'], ['Contact', '#contact']
    ];
    echo '<ul>';
    foreach ($items as $item) {
        printf('<li><a href="%s">%s</a></li>', esc_url($item[1]), esc_html($item[0]));
    }
    echo '</ul>';
}
