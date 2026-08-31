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

function elan_register_content_types() {
    $types = [
        'elan_service' => ['Services', 'Service', 'dashicons-heart'],
        'elan_specialist' => ['Specialists', 'Specialist', 'dashicons-businessperson'],
        'elan_package' => ['Packages', 'Package', 'dashicons-tickets-alt'],
        'elan_testimonial' => ['Testimonials', 'Testimonial', 'dashicons-format-quote'],
    ];

    foreach ($types as $slug => $data) {
        $is_service = $slug === 'elan_service';

        register_post_type($slug, [
            'labels' => [
                'name' => $data[0],
                'singular_name' => $data[1],
                'add_new_item' => 'Add New ' . $data[1],
                'edit_item' => 'Edit ' . $data[1],
            ],
            'public' => true,
            'show_in_rest' => true,
            'menu_icon' => $data[2],
            'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'],
            'has_archive' => $is_service ? 'services' : false,
            'rewrite' => $is_service ? ['slug' => 'services', 'with_front' => false] : false,
        ]);
    }

    register_taxonomy('elan_service_category', ['elan_service'], [
        'labels' => [
            'name' => __('Service Categories', 'maison-elan'),
            'singular_name' => __('Service Category', 'maison-elan'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => true,
        'rewrite' => false,
    ]);
}
add_action('init', 'elan_register_content_types');

function elan_services_url() {
    $url = get_post_type_archive_link('elan_service');
    return $url ?: home_url('/services/');
}

function elan_contact_url() {
    return home_url('/#contact');
}

function elan_service_image($post_id, $fallback = '') {
    $image = get_post_meta($post_id, 'elan_service_image', true);
    if ($image) {
        return $image;
    }

    $thumbnail = get_the_post_thumbnail_url($post_id, 'large');
    if ($thumbnail) {
        return $thumbnail;
    }

    return $fallback ?: 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=1400&q=85';
}

function elan_service_meta($post_id, $key, $fallback = '') {
    $value = get_post_meta($post_id, 'elan_service_' . $key, true);
    return $value !== '' ? $value : $fallback;
}

function elan_flush_rewrites() {
    elan_register_content_types();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'elan_flush_rewrites');

function elan_seed_service_terms() {
    $terms = ['Facials', 'Skin Treatments', 'Brows & Lashes', 'Massage'];
    foreach ($terms as $term) {
        if (!term_exists($term, 'elan_service_category')) {
            wp_insert_term($term, 'elan_service_category');
        }
    }
}

function elan_maybe_seed_services() {
    $seed_version = '1';
    if (get_option('elan_service_seed_version') === $seed_version) {
        return;
    }

    elan_seed_service_terms();

    $existing = get_posts([
        'post_type' => 'elan_service',
        'post_status' => 'any',
        'numberposts' => 1,
        'fields' => 'ids',
    ]);

    if ($existing) {
        update_option('elan_service_seed_version', $seed_version, false);
        flush_rewrite_rules(false);
        return;
    }

    $services = [
        [
            'title' => 'Signature Facial',
            'slug' => 'signature-facial',
            'category' => 'Facials',
            'excerpt' => 'A customized facial to deeply cleanse, hydrate and restore balance.',
            'content' => "Our Signature Facial is a personalized treatment tailored to your skin’s unique needs. Using advanced techniques and premium products, we cleanse, hydrate and nourish your skin while supporting long-term skin health.\n\nEach step is thoughtfully curated to restore balance, strengthen the skin barrier and enhance your natural radiance—leaving your complexion refreshed, smooth and luminous.",
            'duration' => '60 min',
            'price' => '$155',
            'best_for' => 'All skin types',
            'image' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=1400&q=85',
            'benefits' => ['Deep cleansing to remove impurities', 'Hydration boost for plump, healthy skin', 'Improved texture and tone', 'Radiant glow that lasts'],
            'includes' => [
                ['Consultation', 'We assess your skin and concerns to create a custom treatment plan.'],
                ['Cleanse & Exfoliate', 'Gentle cleansing and exfoliation remove buildup and prepare your skin.'],
                ['Treatment Mask', 'Targeted masks and serums hydrate, calm and nourish deeply.'],
                ['Aftercare Guidance', 'Personalized recommendations help maintain your results and glow.'],
            ],
            'faqs' => [
                ['How often should I book this treatment?', 'Most clients benefit from a facial every four to six weeks, adjusted to their skin goals.'],
                ['Is there downtime?', 'No planned downtime. Mild temporary redness can occur after exfoliation and typically settles quickly.'],
                ['Is it suitable for sensitive skin?', 'Yes. The treatment is customized and can be adjusted for sensitive or reactive skin.'],
                ['How should I prepare?', 'Arrive with minimal makeup when possible and pause strong exfoliating products for a few days beforehand.'],
            ],
        ],
        [
            'title' => 'Hydrating Treatment',
            'slug' => 'hydrating-treatment',
            'category' => 'Facials',
            'excerpt' => 'Intense hydration therapy to plump, soothe and revitalize dry, tired skin.',
            'content' => "Designed for dehydrated, tight or lacklustre skin, this treatment layers replenishing hydration with calming care. Skin is left softer, more comfortable and visibly refreshed.\n\nWe tailor the treatment to your current condition, focusing on barrier support and long-lasting moisture rather than a temporary surface glow.",
            'duration' => '60 min',
            'price' => '$165',
            'best_for' => 'Dry & dehydrated skin',
            'image' => 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?auto=format&fit=crop&w=1400&q=85',
            'benefits' => ['Replenishes depleted moisture', 'Supports the skin barrier', 'Softens visible dehydration lines', 'Restores a comfortable glow'],
            'includes' => [
                ['Skin Assessment', 'We identify dehydration patterns and current sensitivities.'],
                ['Gentle Cleanse', 'A non-stripping cleanse prepares the skin without disrupting comfort.'],
                ['Hydration Layers', 'Serums and masks deliver targeted, replenishing moisture.'],
                ['Barrier Care', 'Finishing products help seal in hydration and support recovery.'],
            ],
            'faqs' => [
                ['Who is this treatment best for?', 'It is ideal for dry, dehydrated, travel-stressed or seasonally compromised skin.'],
                ['Can I combine it with other treatments?', 'Yes. It pairs especially well with gentle exfoliation or restorative facial treatments.'],
                ['Is there downtime?', 'No. Skin should feel comfortable and hydrated immediately after treatment.'],
            ],
        ],
        [
            'title' => 'Renewal Peel',
            'slug' => 'renewal-peel',
            'category' => 'Skin Treatments',
            'excerpt' => 'Advanced exfoliation to refine texture, brighten tone and promote cell renewal.',
            'content' => "The Renewal Peel is a controlled resurfacing treatment designed to improve dullness, uneven tone and rough texture. The peel strength is selected according to your skin history and goals.\n\nOur approach prioritizes visible refinement while protecting the skin barrier, with clear preparation and aftercare guidance included.",
            'duration' => '45 min',
            'price' => '$175',
            'best_for' => 'Texture & uneven tone',
            'image' => 'https://images.unsplash.com/photo-1515377905703-c4788e51af15?auto=format&fit=crop&w=1400&q=85',
            'benefits' => ['Refines uneven texture', 'Brightens dull-looking skin', 'Supports more even tone', 'Encourages fresh cell turnover'],
            'includes' => [
                ['Consultation', 'We review sensitivity, skincare use and resurfacing goals.'],
                ['Preparation', 'The skin is cleansed and prepared for controlled exfoliation.'],
                ['Customized Peel', 'A carefully selected peel is applied and monitored throughout.'],
                ['Recovery Plan', 'You leave with simple aftercare guidance for the days ahead.'],
            ],
            'faqs' => [
                ['Will my skin peel visibly?', 'Some formulations may cause light flaking, while others produce little to no visible peeling.'],
                ['How much downtime should I expect?', 'Downtime varies by peel strength, but we will explain the expected recovery before treatment.'],
                ['Can I use retinoids beforehand?', 'Strong active products may need to be paused before treatment. We will confirm timing during consultation.'],
            ],
        ],
        [
            'title' => 'Brow Styling',
            'slug' => 'brow-styling',
            'category' => 'Brows & Lashes',
            'excerpt' => 'Brow shaping and enhancement for balanced, natural-looking definition.',
            'content' => "Brow Styling combines careful mapping, shaping and finishing to enhance your natural features without overpowering them. Every appointment is tailored to your face shape, growth pattern and preferred level of definition.\n\nThe result is polished, balanced brows that remain distinctly yours.",
            'duration' => '30 min',
            'price' => '$55',
            'best_for' => 'Natural definition',
            'image' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=1400&q=85',
            'benefits' => ['Creates balanced definition', 'Works with natural brow growth', 'Refines shape and symmetry', 'Low-maintenance polished finish'],
            'includes' => [
                ['Brow Consultation', 'We discuss shape, maintenance and the finish you prefer.'],
                ['Mapping', 'Your brow shape is planned around your natural features.'],
                ['Shaping', 'Precise grooming refines the final silhouette.'],
                ['Finishing', 'Brows are styled and finished with tailored product recommendations.'],
            ],
            'faqs' => [
                ['How long should I grow my brows before an appointment?', 'Two to three weeks of growth usually gives us more flexibility when refining the shape.'],
                ['Can you keep my brows looking natural?', 'Absolutely. Natural-looking balance is the default approach unless you request a stronger shape.'],
                ['How often should I return?', 'Most guests rebook every three to five weeks depending on growth.'],
            ],
        ],
        [
            'title' => 'Lash Lift',
            'slug' => 'lash-lift',
            'category' => 'Brows & Lashes',
            'excerpt' => 'Lift, curl and define your natural lashes for a longer, brighter look.',
            'content' => "The Lash Lift enhances your natural lashes by creating a soft, eye-opening curl from root to tip. It is designed for guests who want definition without extensions or daily curling.\n\nThe finish is clean, effortless and customized to your lash length and preferred level of lift.",
            'duration' => '60 min',
            'price' => '$85',
            'best_for' => 'Natural lash enhancement',
            'image' => 'https://images.unsplash.com/photo-1487412912498-0447578fcca8?auto=format&fit=crop&w=1400&q=85',
            'benefits' => ['Creates an eye-opening curl', 'Enhances natural lash length', 'Reduces daily styling time', 'Soft, low-maintenance definition'],
            'includes' => [
                ['Consultation', 'We choose a lift style that suits your lashes and eye shape.'],
                ['Preparation', 'Lashes are cleansed and positioned carefully for even results.'],
                ['Lift & Set', 'The lifting process creates a controlled, lasting curl.'],
                ['Aftercare', 'You receive simple guidance to protect the result.'],
            ],
            'faqs' => [
                ['How long does a lash lift last?', 'Results commonly last six to eight weeks depending on your natural lash cycle.'],
                ['Can I wear mascara afterward?', 'Yes, once the initial aftercare period has passed.'],
                ['Does a lash lift use extensions?', 'No. The treatment works entirely with your natural lashes.'],
            ],
        ],
        [
            'title' => 'Restorative Massage',
            'slug' => 'restorative-massage',
            'category' => 'Massage',
            'excerpt' => 'Therapeutic massage to release tension, improve circulation and restore calm.',
            'content' => "Our Restorative Massage blends slow, grounding techniques with focused therapeutic work where your body needs it most. Pressure and pace are adjusted throughout the session for comfort and effectiveness.\n\nThe experience is designed to reduce muscular tension while creating space to reset physically and mentally.",
            'duration' => '60 min',
            'price' => '$145',
            'best_for' => 'Tension & relaxation',
            'image' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=1400&q=85',
            'benefits' => ['Releases muscular tension', 'Encourages circulation', 'Supports relaxation and recovery', 'Creates a deep sense of calm'],
            'includes' => [
                ['Consultation', 'We identify tension areas and your preferred pressure.'],
                ['Warm-Up', 'Gentle techniques prepare the body and settle the nervous system.'],
                ['Focused Massage', 'Targeted work addresses your priority areas.'],
                ['Reset', 'A calm closing sequence helps you leave grounded and restored.'],
            ],
            'faqs' => [
                ['Can the pressure be adjusted?', 'Yes. Pressure is always adapted to your comfort and goals.'],
                ['What should I wear?', 'You can arrive in normal clothing; appropriate draping is used throughout the treatment.'],
                ['How often should I book?', 'Frequency depends on your goals, lifestyle and level of muscular tension.'],
            ],
        ],
    ];

    foreach ($services as $service) {
        $post_id = wp_insert_post([
            'post_type' => 'elan_service',
            'post_status' => 'publish',
            'post_title' => $service['title'],
            'post_name' => $service['slug'],
            'post_excerpt' => $service['excerpt'],
            'post_content' => $service['content'],
        ]);

        if (is_wp_error($post_id) || !$post_id) {
            continue;
        }

        wp_set_object_terms($post_id, $service['category'], 'elan_service_category');
        update_post_meta($post_id, 'elan_service_duration', $service['duration']);
        update_post_meta($post_id, 'elan_service_price', $service['price']);
        update_post_meta($post_id, 'elan_service_best_for', $service['best_for']);
        update_post_meta($post_id, 'elan_service_image', $service['image']);
        update_post_meta($post_id, 'elan_service_benefits', $service['benefits']);
        update_post_meta($post_id, 'elan_service_includes', $service['includes']);
        update_post_meta($post_id, 'elan_service_faqs', $service['faqs']);
    }

    update_option('elan_service_seed_version', $seed_version, false);
    flush_rewrite_rules(false);
}
add_action('init', 'elan_maybe_seed_services', 20);

function elan_service_meta_box() {
    add_meta_box(
        'elan-service-details',
        __('Service Details', 'maison-elan'),
        'elan_service_meta_box_render',
        'elan_service',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'elan_service_meta_box');

function elan_service_meta_box_render($post) {
    wp_nonce_field('elan_save_service_details', 'elan_service_nonce');

    $duration = elan_service_meta($post->ID, 'duration');
    $price = elan_service_meta($post->ID, 'price');
    $best_for = elan_service_meta($post->ID, 'best_for');
    $image = elan_service_meta($post->ID, 'image');
    $benefits = get_post_meta($post->ID, 'elan_service_benefits', true);
    $includes = get_post_meta($post->ID, 'elan_service_includes', true);
    $faqs = get_post_meta($post->ID, 'elan_service_faqs', true);

    $benefits_text = is_array($benefits) ? implode("\n", $benefits) : '';
    $includes_text = '';
    if (is_array($includes)) {
        foreach ($includes as $item) {
            $includes_text .= ($item[0] ?? '') . '|' . ($item[1] ?? '') . "\n";
        }
    }
    $faqs_text = '';
    if (is_array($faqs)) {
        foreach ($faqs as $item) {
            $faqs_text .= ($item[0] ?? '') . '|' . ($item[1] ?? '') . "\n";
        }
    }
    ?>
    <style>.elan-admin-field{margin:0 0 18px}.elan-admin-field label{display:block;font-weight:600;margin-bottom:6px}.elan-admin-field input,.elan-admin-field textarea{width:100%}.elan-admin-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}</style>
    <div class="elan-admin-grid">
      <p class="elan-admin-field"><label for="elan_service_duration">Duration</label><input id="elan_service_duration" name="elan_service_duration" value="<?php echo esc_attr($duration); ?>" placeholder="60 min"></p>
      <p class="elan-admin-field"><label for="elan_service_price">Starting price</label><input id="elan_service_price" name="elan_service_price" value="<?php echo esc_attr($price); ?>" placeholder="$155"></p>
      <p class="elan-admin-field"><label for="elan_service_best_for">Best for</label><input id="elan_service_best_for" name="elan_service_best_for" value="<?php echo esc_attr($best_for); ?>" placeholder="All skin types"></p>
    </div>
    <p class="elan-admin-field"><label for="elan_service_image">Image URL</label><input id="elan_service_image" name="elan_service_image" type="url" value="<?php echo esc_attr($image); ?>" placeholder="https://..."></p>
    <p class="elan-admin-field"><label for="elan_service_benefits">Benefits — one per line</label><textarea id="elan_service_benefits" name="elan_service_benefits" rows="5"><?php echo esc_textarea($benefits_text); ?></textarea></p>
    <p class="elan-admin-field"><label for="elan_service_includes">What’s included — one item per line as Title|Description</label><textarea id="elan_service_includes" name="elan_service_includes" rows="6"><?php echo esc_textarea(trim($includes_text)); ?></textarea></p>
    <p class="elan-admin-field"><label for="elan_service_faqs">FAQs — one item per line as Question|Answer</label><textarea id="elan_service_faqs" name="elan_service_faqs" rows="7"><?php echo esc_textarea(trim($faqs_text)); ?></textarea></p>
    <?php
}

function elan_parse_pipe_rows($raw) {
    $rows = [];
    foreach (preg_split('/\r\n|\r|\n/', (string) $raw) as $line) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }
        $parts = array_map('trim', explode('|', $line, 2));
        if (!empty($parts[0])) {
            $rows[] = [sanitize_text_field($parts[0]), sanitize_textarea_field($parts[1] ?? '')];
        }
    }
    return $rows;
}

function elan_save_service_details($post_id) {
    if (!isset($_POST['elan_service_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['elan_service_nonce'])), 'elan_save_service_details')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $simple_fields = ['duration', 'price', 'best_for'];
    foreach ($simple_fields as $field) {
        $key = 'elan_service_' . $field;
        if (isset($_POST[$key])) {
            update_post_meta($post_id, $key, sanitize_text_field(wp_unslash($_POST[$key])));
        }
    }

    if (isset($_POST['elan_service_image'])) {
        update_post_meta($post_id, 'elan_service_image', esc_url_raw(wp_unslash($_POST['elan_service_image'])));
    }

    if (isset($_POST['elan_service_benefits'])) {
        $benefits = array_values(array_filter(array_map('sanitize_text_field', preg_split('/\r\n|\r|\n/', wp_unslash($_POST['elan_service_benefits'])))));
        update_post_meta($post_id, 'elan_service_benefits', $benefits);
    }

    if (isset($_POST['elan_service_includes'])) {
        update_post_meta($post_id, 'elan_service_includes', elan_parse_pipe_rows(wp_unslash($_POST['elan_service_includes'])));
    }

    if (isset($_POST['elan_service_faqs'])) {
        update_post_meta($post_id, 'elan_service_faqs', elan_parse_pipe_rows(wp_unslash($_POST['elan_service_faqs'])));
    }
}
add_action('save_post_elan_service', 'elan_save_service_details');

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

function elan_primary_menu_link_attributes($atts, $item, $args) {
    if (!isset($args->theme_location) || $args->theme_location !== 'primary') {
        return $atts;
    }

    $title = strtolower(trim(wp_strip_all_tags($item->title)));
    $map = [
        'home' => home_url('/'),
        'services' => elan_services_url(),
        'specialists' => home_url('/#specialists'),
        'pricing' => home_url('/#pricing'),
        'studio' => home_url('/#studio'),
        'contact' => elan_contact_url(),
    ];

    if (isset($map[$title])) {
        $atts['href'] = $map[$title];
    }

    return $atts;
}
add_filter('nav_menu_link_attributes', 'elan_primary_menu_link_attributes', 10, 3);

function elan_primary_menu_classes($classes, $item, $args) {
    if (isset($args->theme_location) && $args->theme_location === 'primary') {
        $title = strtolower(trim(wp_strip_all_tags($item->title)));
        if ($title === 'services' && (is_post_type_archive('elan_service') || is_singular('elan_service'))) {
            $classes[] = 'current-menu-item';
        }
    }
    return array_unique($classes);
}
add_filter('nav_menu_css_class', 'elan_primary_menu_classes', 10, 3);

function elan_menu_fallback() {
    $items = [
        ['Home', home_url('/')],
        ['Services', elan_services_url()],
        ['Specialists', home_url('/#specialists')],
        ['Pricing', home_url('/#pricing')],
        ['Studio', home_url('/#studio')],
        ['Contact', elan_contact_url()],
    ];
    echo '<ul>';
    foreach ($items as $item) {
        $current = $item[0] === 'Services' && (is_post_type_archive('elan_service') || is_singular('elan_service')) ? ' class="current-menu-item"' : '';
        printf('<li%s><a href="%s">%s</a></li>', $current, esc_url($item[1]), esc_html($item[0]));
    }
    echo '</ul>';
}
