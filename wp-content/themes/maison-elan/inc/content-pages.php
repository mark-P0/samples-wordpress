<?php
if (!defined('ABSPATH')) { exit; }

function elan_theme_content_page_url($slug) {
    $page = get_page_by_path($slug, OBJECT, 'page');
    return $page ? get_permalink($page) : home_url('/' . trim($slug, '/') . '/');
}

function elan_theme_pricing_url() {
    return elan_theme_content_page_url('pricing');
}

function elan_theme_studio_url() {
    return elan_theme_content_page_url('studio');
}

function elan_theme_ensure_content_pages() {
    if (get_option('elan_theme_content_pages_version') === '1') { return; }

    $pages = [
        'pricing' => [
            'title' => 'Pricing',
            'excerpt' => 'Transparent pricing for thoughtful, personalized care.',
        ],
        'studio' => [
            'title' => 'Studio',
            'excerpt' => 'A serene space designed for your beauty and well-being.',
        ],
    ];

    foreach ($pages as $slug => $page_data) {
        if (get_page_by_path($slug, OBJECT, 'page')) { continue; }
        wp_insert_post([
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_title' => $page_data['title'],
            'post_name' => $slug,
            'post_excerpt' => $page_data['excerpt'],
        ]);
    }

    update_option('elan_theme_content_pages_version', '1', false);
}
add_action('init', 'elan_theme_ensure_content_pages', 40);
