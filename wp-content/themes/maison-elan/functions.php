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
    $theme_version = wp_get_theme()->get('Version');
    wp_enqueue_style('maison-elan', get_stylesheet_uri(), [], $theme_version);
    wp_enqueue_style('maison-elan-refinements', get_template_directory_uri() . '/assets/css/refinements.css', ['maison-elan'], $theme_version);

    if (is_post_type_archive('elan_service') || is_singular('elan_service')) {
        wp_enqueue_style('maison-elan-services', get_template_directory_uri() . '/assets/css/services.css', ['maison-elan', 'maison-elan-refinements'], $theme_version);
    }
    if (is_post_type_archive('elan_specialist') || is_singular('elan_specialist')) {
        wp_enqueue_style('maison-elan-specialists', get_template_directory_uri() . '/assets/css/specialists.css', ['maison-elan', 'maison-elan-refinements'], $theme_version);
    }

    wp_enqueue_script('maison-elan', get_template_directory_uri() . '/assets/js/theme.js', [], $theme_version, true);
}
add_action('wp_enqueue_scripts', 'elan_assets');

function elan_favicon_tags() {
    $favicon = get_template_directory_uri() . '/assets/images/favicon.svg';
    printf("<link rel=\"icon\" href=\"%s\" type=\"image/svg+xml\">\n", esc_url($favicon));
    printf("<link rel=\"alternate icon\" href=\"%s\">\n", esc_url($favicon));
}
add_action('wp_head', 'elan_favicon_tags', 1);
add_action('admin_head', 'elan_favicon_tags', 1);

function elan_theme_services_url() {
    return function_exists('elan_services_url') ? elan_services_url() : home_url('/services/');
}

function elan_theme_specialists_url() {
    return function_exists('elan_specialists_url') ? elan_specialists_url() : home_url('/specialists/');
}

function elan_contact_url() {
    return home_url('/#contact');
}

function elan_theme_business_detail($key, $fallback = '') {
    if (function_exists('elan_business_detail')) {
        return elan_business_detail($key, $fallback);
    }
    return $fallback;
}

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
    ];

    foreach ($fields as $id => $field) {
        $wp_customize->add_setting('elan_' . $id, ['default' => $field[1], 'sanitize_callback' => 'sanitize_text_field']);
        $wp_customize->add_control('elan_' . $id, [
            'label' => __($field[0], 'maison-elan'),
            'section' => 'elan_home',
            'type' => ($id === 'hero_copy' || $id === 'about_copy') ? 'textarea' : 'text',
        ]);
    }
}
add_action('customize_register', 'elan_customize');

function elan_mod($key, $fallback) {
    return get_theme_mod('elan_' . $key, $fallback);
}

function elan_primary_menu_link_attributes($atts, $item, $args) {
    if (!isset($args->theme_location) || $args->theme_location !== 'primary') { return $atts; }

    $title = strtolower(trim(wp_strip_all_tags($item->title)));
    $map = [
        'home' => home_url('/'),
        'services' => elan_theme_services_url(),
        'specialists' => elan_theme_specialists_url(),
        'pricing' => home_url('/#pricing'),
        'studio' => home_url('/#studio'),
        'contact' => elan_contact_url(),
    ];
    if (isset($map[$title])) { $atts['href'] = $map[$title]; }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'elan_primary_menu_link_attributes', 10, 3);

function elan_primary_menu_classes($classes, $item, $args) {
    if (!isset($args->theme_location) || $args->theme_location !== 'primary') { return $classes; }
    $title = strtolower(trim(wp_strip_all_tags($item->title)));
    if ($title === 'services' && (is_post_type_archive('elan_service') || is_singular('elan_service'))) { $classes[] = 'current-menu-item'; }
    if ($title === 'specialists' && (is_post_type_archive('elan_specialist') || is_singular('elan_specialist'))) { $classes[] = 'current-menu-item'; }
    return array_unique($classes);
}
add_filter('nav_menu_css_class', 'elan_primary_menu_classes', 10, 3);

function elan_menu_fallback() {
    $items = [
        ['Home', home_url('/')],
        ['Services', elan_theme_services_url()],
        ['Specialists', elan_theme_specialists_url()],
        ['Pricing', home_url('/#pricing')],
        ['Studio', home_url('/#studio')],
        ['Contact', elan_contact_url()],
    ];
    echo '<ul>';
    foreach ($items as $item) {
        $is_current = ($item[0] === 'Services' && (is_post_type_archive('elan_service') || is_singular('elan_service')))
            || ($item[0] === 'Specialists' && (is_post_type_archive('elan_specialist') || is_singular('elan_specialist')));
        printf('<li%s><a href="%s">%s</a></li>', $is_current ? ' class="current-menu-item"' : '', esc_url($item[1]), esc_html($item[0]));
    }
    echo '</ul>';
}

function elan_content_plugin_notice() {
    if (!current_user_can('activate_plugins') || function_exists('elan_services_url')) { return; }
    echo '<div class="notice notice-warning"><p><strong>Maison Élan:</strong> Activate the <em>Maison Élan Content</em> plugin to enable Services, Specialists, Packages, Testimonials, and shared studio details.</p></div>';
}
add_action('admin_notices', 'elan_content_plugin_notice');
