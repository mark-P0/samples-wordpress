<?php
/**
 * Plugin Name: Maison Élan Specialists
 * Description: Adds routable specialist profiles and editable specialist details for the Maison Élan sample.
 */

if (!defined('ABSPATH')) {
    exit;
}

function elan_specialists_post_type_args($args, $post_type) {
    if ($post_type !== 'elan_specialist') {
        return $args;
    }

    $args['has_archive'] = 'specialists';
    $args['rewrite'] = [
        'slug' => 'specialists',
        'with_front' => false,
    ];
    $args['supports'] = ['title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'page-attributes'];

    return $args;
}
add_filter('register_post_type_args', 'elan_specialists_post_type_args', 10, 2);

function elan_specialists_url() {
    $url = get_post_type_archive_link('elan_specialist');
    return $url ?: home_url('/specialists/');
}

function elan_specialist_meta($post_id, $key, $fallback = '') {
    $value = get_post_meta($post_id, 'elan_specialist_' . $key, true);
    return $value !== '' ? $value : $fallback;
}

function elan_specialist_image($post_id, $fallback = '') {
    $image = elan_specialist_meta($post_id, 'image');
    if ($image) {
        return $image;
    }

    $thumbnail = get_the_post_thumbnail_url($post_id, 'large');
    if ($thumbnail) {
        return $thumbnail;
    }

    return $fallback ?: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=1200&q=85';
}

function elan_specialists_assets() {
    if (!is_post_type_archive('elan_specialist') && !is_singular('elan_specialist')) {
        return;
    }

    $theme = wp_get_theme('maison-elan');
    $version = $theme->exists() ? $theme->get('Version') : null;
    wp_enqueue_style(
        'maison-elan-specialists',
        get_theme_root_uri() . '/maison-elan/assets/css/specialists.css',
        ['maison-elan', 'maison-elan-refinements'],
        $version
    );
}
add_action('wp_enqueue_scripts', 'elan_specialists_assets', 20);

function elan_specialists_nav_link($atts, $item, $args) {
    if (!isset($args->theme_location) || $args->theme_location !== 'primary') {
        return $atts;
    }

    $title = strtolower(trim(wp_strip_all_tags($item->title)));
    if ($title === 'specialists') {
        $atts['href'] = elan_specialists_url();
    }

    return $atts;
}
add_filter('nav_menu_link_attributes', 'elan_specialists_nav_link', 20, 3);

function elan_specialists_nav_classes($classes, $item, $args) {
    if (isset($args->theme_location) && $args->theme_location === 'primary') {
        $title = strtolower(trim(wp_strip_all_tags($item->title)));
        if ($title === 'specialists' && (is_post_type_archive('elan_specialist') || is_singular('elan_specialist'))) {
            $classes[] = 'current-menu-item';
        }
    }

    return array_unique($classes);
}
add_filter('nav_menu_css_class', 'elan_specialists_nav_classes', 20, 3);

function elan_seed_specialists() {
    $seed_version = '1';
    if (get_option('elan_specialist_seed_version') === $seed_version) {
        return;
    }

    $existing = get_posts([
        'post_type' => 'elan_specialist',
        'post_status' => 'any',
        'numberposts' => 1,
        'fields' => 'ids',
    ]);

    if ($existing) {
        update_option('elan_specialist_seed_version', $seed_version, false);
        flush_rewrite_rules(false);
        return;
    }

    $specialists = [
        [
            'title' => 'Sofia Marin',
            'slug' => 'sofia-marin',
            'role' => 'Founder & Lead Aesthetician',
            'excerpt' => 'Specializing in advanced skincare and holistic facial rejuvenation.',
            'content' => "With over 10 years of experience in advanced skincare and facial rejuvenation, Sofia founded Maison Élan with a simple belief: real beauty comes from healthy, confident skin.\n\nHer approach combines results-focused treatments with a holistic, personalized touch to create natural, long-lasting outcomes.",
            'image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=1200&q=85',
            'specialties' => ['Advanced Facials', 'Anti-Aging Treatments', 'Skin Rejuvenation', 'Custom Skincare Plans'],
            'experience' => '10+ Years',
            'philosophy' => 'Enhance. Refine. Empower.',
            'education' => [
                'International Dermal Institute — Advanced Aesthetics Diploma',
                'CIDESCO Certification — Skincare & Facial Therapy',
                'Ongoing training in advanced skin technologies and techniques',
            ],
        ],
        [
            'title' => 'Elena Laurent',
            'slug' => 'elena-laurent',
            'role' => 'Senior Skin Specialist',
            'excerpt' => 'Passionate about skin health and customized treatment plans.',
            'content' => "Elena brings a precise, thoughtful approach to skin health, combining careful consultation with treatment plans that evolve with each client.\n\nShe is known for making advanced skincare approachable and for creating calm, confidence-building experiences in the treatment room.",
            'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=1200&q=85',
            'specialties' => ['Corrective Facials', 'Hydration Therapy', 'Sensitive Skin', 'Barrier Repair'],
            'experience' => '8 Years',
            'philosophy' => 'Listen first. Treat thoughtfully.',
            'education' => ['Advanced Facial Therapy Certification', 'Skin Barrier & Sensitivity Training', 'Continuing education in professional skincare'],
        ],
        [
            'title' => 'Isabella Ricci',
            'slug' => 'isabella-ricci',
            'role' => 'Brow & Lash Artist',
            'excerpt' => 'Enhancing natural beauty through precise brows and lashes.',
            'content' => "Isabella specializes in subtle, polished brow and lash work designed around each client’s natural features.\n\nHer aesthetic is refined rather than dramatic, with an emphasis on balance, proportion and low-maintenance results.",
            'image' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=1200&q=85',
            'specialties' => ['Brow Design', 'Brow Styling', 'Lash Lifts', 'Natural Definition'],
            'experience' => '7 Years',
            'philosophy' => 'Definition should still feel like you.',
            'education' => ['Advanced Brow Mapping Certification', 'Professional Lash Lift Training', 'Ongoing artistry and product education'],
        ],
        [
            'title' => 'Mei Ling',
            'slug' => 'mei-ling',
            'role' => 'Nail Specialist',
            'excerpt' => 'Detail-oriented nail artistry with an elegant, long-lasting finish.',
            'content' => "Mei’s work is grounded in immaculate preparation, healthy nail care and understated artistry.\n\nShe creates refined manicures that complement personal style while prioritizing comfort, durability and nail health.",
            'image' => 'https://images.unsplash.com/photo-1531123897727-8f129e1688ce?auto=format&fit=crop&w=1200&q=85',
            'specialties' => ['Structured Manicures', 'Natural Nail Care', 'Minimal Nail Art', 'Long-Wear Finishes'],
            'experience' => '6 Years',
            'philosophy' => 'Luxury lives in the details.',
            'education' => ['Professional Nail Technology Diploma', 'Advanced Structured Gel Training', 'Sanitation and natural nail health certification'],
        ],
        [
            'title' => 'Camille Dubois',
            'slug' => 'camille-dubois',
            'role' => 'Massage Therapist',
            'excerpt' => 'Focused on relaxation, recovery and overall well-being.',
            'content' => "Camille blends restorative massage techniques with a calm, intuitive approach to help clients release tension and reconnect with their bodies.\n\nEach session is adapted to the client’s comfort, lifestyle and recovery needs rather than following a fixed routine.",
            'image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=1200&q=85',
            'specialties' => ['Restorative Massage', 'Tension Release', 'Relaxation', 'Recovery Support'],
            'experience' => '9 Years',
            'philosophy' => 'Restore ease through intentional care.',
            'education' => ['Registered Massage Therapy Diploma', 'Myofascial and relaxation technique training', 'Continuing education in recovery-focused bodywork'],
        ],
        [
            'title' => 'Juliette Moreau',
            'slug' => 'juliette-moreau',
            'role' => 'Wellness Coordinator',
            'excerpt' => 'Your guide to a balanced beauty and wellness journey.',
            'content' => "Juliette helps guests navigate services, treatment timing and long-term care plans so every visit feels effortless and considered.\n\nHer role connects the studio experience from first consultation through ongoing appointments and home-care recommendations.",
            'image' => 'https://images.unsplash.com/photo-1488426862026-3ee34a7d66df?auto=format&fit=crop&w=1200&q=85',
            'specialties' => ['Client Consultations', 'Treatment Planning', 'Wellness Journeys', 'Studio Care'],
            'experience' => '5 Years',
            'philosophy' => 'Care should feel seamless and personal.',
            'education' => ['Beauty & Wellness Management Diploma', 'Client Experience Training', 'Continuing education in treatment planning and skincare'],
        ],
    ];

    foreach ($specialists as $index => $specialist) {
        $post_id = wp_insert_post([
            'post_type' => 'elan_specialist',
            'post_status' => 'publish',
            'post_title' => $specialist['title'],
            'post_name' => $specialist['slug'],
            'post_excerpt' => $specialist['excerpt'],
            'post_content' => $specialist['content'],
            'menu_order' => $index,
        ]);

        if (is_wp_error($post_id) || !$post_id) {
            continue;
        }

        update_post_meta($post_id, 'elan_specialist_role', $specialist['role']);
        update_post_meta($post_id, 'elan_specialist_image', $specialist['image']);
        update_post_meta($post_id, 'elan_specialist_specialties', $specialist['specialties']);
        update_post_meta($post_id, 'elan_specialist_experience', $specialist['experience']);
        update_post_meta($post_id, 'elan_specialist_philosophy', $specialist['philosophy']);
        update_post_meta($post_id, 'elan_specialist_education', $specialist['education']);
    }

    update_option('elan_specialist_seed_version', $seed_version, false);
    flush_rewrite_rules(false);
}
add_action('init', 'elan_seed_specialists', 30);

function elan_specialist_meta_box() {
    add_meta_box(
        'elan-specialist-details',
        __('Specialist Details', 'maison-elan'),
        'elan_specialist_meta_box_render',
        'elan_specialist',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'elan_specialist_meta_box');

function elan_specialist_meta_box_render($post) {
    wp_nonce_field('elan_save_specialist_details', 'elan_specialist_nonce');

    $role = elan_specialist_meta($post->ID, 'role');
    $image = elan_specialist_meta($post->ID, 'image');
    $experience = elan_specialist_meta($post->ID, 'experience');
    $philosophy = elan_specialist_meta($post->ID, 'philosophy');
    $specialties = get_post_meta($post->ID, 'elan_specialist_specialties', true);
    $education = get_post_meta($post->ID, 'elan_specialist_education', true);

    $specialties_text = is_array($specialties) ? implode("\n", $specialties) : '';
    $education_text = is_array($education) ? implode("\n", $education) : '';
    ?>
    <style>
      .elan-specialist-admin-field{margin:0 0 18px}.elan-specialist-admin-field label{display:block;font-weight:600;margin-bottom:6px}.elan-specialist-admin-field input,.elan-specialist-admin-field textarea{width:100%}.elan-specialist-admin-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}@media(max-width:782px){.elan-specialist-admin-grid{grid-template-columns:1fr}}
    </style>
    <div class="elan-specialist-admin-grid">
      <p class="elan-specialist-admin-field"><label for="elan_specialist_role">Role</label><input id="elan_specialist_role" name="elan_specialist_role" value="<?php echo esc_attr($role); ?>" placeholder="Senior Skin Specialist"></p>
      <p class="elan-specialist-admin-field"><label for="elan_specialist_experience">Experience</label><input id="elan_specialist_experience" name="elan_specialist_experience" value="<?php echo esc_attr($experience); ?>" placeholder="8 Years"></p>
    </div>
    <p class="elan-specialist-admin-field"><label for="elan_specialist_philosophy">Philosophy</label><input id="elan_specialist_philosophy" name="elan_specialist_philosophy" value="<?php echo esc_attr($philosophy); ?>" placeholder="Listen first. Treat thoughtfully."></p>
    <p class="elan-specialist-admin-field"><label for="elan_specialist_image">Portrait image URL</label><input id="elan_specialist_image" name="elan_specialist_image" type="url" value="<?php echo esc_attr($image); ?>" placeholder="https://..."></p>
    <p class="elan-specialist-admin-field"><label for="elan_specialist_specialties">Specialties — one per line</label><textarea id="elan_specialist_specialties" name="elan_specialist_specialties" rows="5"><?php echo esc_textarea($specialties_text); ?></textarea></p>
    <p class="elan-specialist-admin-field"><label for="elan_specialist_education">Education & certifications — one per line</label><textarea id="elan_specialist_education" name="elan_specialist_education" rows="5"><?php echo esc_textarea($education_text); ?></textarea></p>
    <?php
}

function elan_specialist_lines($raw) {
    $lines = preg_split('/\r\n|\r|\n/', (string) $raw);
    return array_values(array_filter(array_map('sanitize_text_field', $lines)));
}

function elan_save_specialist_details($post_id) {
    if (!isset($_POST['elan_specialist_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['elan_specialist_nonce'])), 'elan_save_specialist_details')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    foreach (['role', 'experience', 'philosophy'] as $field) {
        $key = 'elan_specialist_' . $field;
        if (isset($_POST[$key])) {
            update_post_meta($post_id, $key, sanitize_text_field(wp_unslash($_POST[$key])));
        }
    }

    if (isset($_POST['elan_specialist_image'])) {
        update_post_meta($post_id, 'elan_specialist_image', esc_url_raw(wp_unslash($_POST['elan_specialist_image'])));
    }
    if (isset($_POST['elan_specialist_specialties'])) {
        update_post_meta($post_id, 'elan_specialist_specialties', elan_specialist_lines(wp_unslash($_POST['elan_specialist_specialties'])));
    }
    if (isset($_POST['elan_specialist_education'])) {
        update_post_meta($post_id, 'elan_specialist_education', elan_specialist_lines(wp_unslash($_POST['elan_specialist_education'])));
    }
}
add_action('save_post_elan_specialist', 'elan_save_specialist_details');
