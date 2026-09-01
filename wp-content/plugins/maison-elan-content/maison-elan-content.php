<?php
/**
 * Plugin Name: Maison Élan Content
 * Description: Content models, editorial fields, demo content, and business details for the Maison Élan sample site.
 * Version: 0.1.0
 */

if (!defined('ABSPATH')) { exit; }

function elan_content_register_types() {
    $types = [
        'elan_service' => [
            'plural' => 'Services', 'singular' => 'Service', 'icon' => 'dashicons-heart',
            'archive' => 'services', 'rewrite' => ['slug' => 'services', 'with_front' => false],
            'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'page-attributes'],
        ],
        'elan_specialist' => [
            'plural' => 'Specialists', 'singular' => 'Specialist', 'icon' => 'dashicons-businessperson',
            'archive' => 'specialists', 'rewrite' => ['slug' => 'specialists', 'with_front' => false],
            'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'page-attributes'],
        ],
        'elan_package' => [
            'plural' => 'Packages', 'singular' => 'Package', 'icon' => 'dashicons-tickets-alt',
            'archive' => false, 'rewrite' => false,
            'supports' => ['title', 'editor', 'excerpt', 'custom-fields', 'page-attributes'],
        ],
        'elan_testimonial' => [
            'plural' => 'Testimonials', 'singular' => 'Testimonial', 'icon' => 'dashicons-format-quote',
            'archive' => false, 'rewrite' => false,
            'supports' => ['title', 'editor', 'custom-fields', 'page-attributes'],
        ],
    ];

    foreach ($types as $slug => $data) {
        register_post_type($slug, [
            'labels' => [
                'name' => $data['plural'],
                'singular_name' => $data['singular'],
                'add_new_item' => 'Add New ' . $data['singular'],
                'edit_item' => 'Edit ' . $data['singular'],
            ],
            'public' => true,
            'show_in_rest' => true,
            'menu_icon' => $data['icon'],
            'supports' => $data['supports'],
            'has_archive' => $data['archive'],
            'rewrite' => $data['rewrite'],
        ]);
    }

    register_taxonomy('elan_service_category', ['elan_service'], [
        'labels' => ['name' => 'Service Categories', 'singular_name' => 'Service Category'],
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => true,
        'rewrite' => false,
    ]);
}
add_action('init', 'elan_content_register_types');

function elan_services_url() {
    return get_post_type_archive_link('elan_service') ?: home_url('/services/');
}

function elan_specialists_url() {
    return get_post_type_archive_link('elan_specialist') ?: home_url('/specialists/');
}

function elan_service_meta($post_id, $key, $fallback = '') {
    $value = get_post_meta($post_id, 'elan_service_' . $key, true);
    return $value !== '' ? $value : $fallback;
}

function elan_service_image($post_id, $fallback = '') {
    $image = elan_service_meta($post_id, 'image');
    if ($image) { return $image; }
    $thumbnail = get_the_post_thumbnail_url($post_id, 'large');
    if ($thumbnail) { return $thumbnail; }
    return $fallback ?: 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=1400&q=85';
}

function elan_specialist_meta($post_id, $key, $fallback = '') {
    $value = get_post_meta($post_id, 'elan_specialist_' . $key, true);
    return $value !== '' ? $value : $fallback;
}

function elan_specialist_image($post_id, $fallback = '') {
    $image = elan_specialist_meta($post_id, 'image');
    if ($image) { return $image; }
    $thumbnail = get_the_post_thumbnail_url($post_id, 'large');
    if ($thumbnail) { return $thumbnail; }
    return $fallback ?: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=1200&q=85';
}

function elan_package_meta($post_id, $key, $fallback = '') {
    $value = get_post_meta($post_id, 'elan_package_' . $key, true);
    return $value !== '' ? $value : $fallback;
}

function elan_testimonial_client($post_id) {
    $client = get_post_meta($post_id, 'elan_testimonial_client', true);
    return $client !== '' ? $client : get_the_title($post_id);
}

function elan_business_detail($key, $fallback = '') {
    $value = get_option('elan_business_' . $key, '');
    return $value !== '' ? $value : $fallback;
}

function elan_content_lines($raw) {
    $lines = preg_split('/\r\n|\r|\n/', (string) $raw);
    return array_values(array_filter(array_map('sanitize_text_field', $lines)));
}

function elan_content_pipe_rows($raw) {
    $rows = [];
    foreach (preg_split('/\r\n|\r|\n/', (string) $raw) as $line) {
        $line = trim($line);
        if ($line === '') { continue; }
        $parts = array_map('trim', explode('|', $line, 2));
        if (!empty($parts[0])) {
            $rows[] = [sanitize_text_field($parts[0]), sanitize_textarea_field($parts[1] ?? '')];
        }
    }
    return $rows;
}

function elan_content_seed_service_terms() {
    foreach (['Facials', 'Skin Treatments', 'Brows & Lashes', 'Massage'] as $term) {
        if (!term_exists($term, 'elan_service_category')) { wp_insert_term($term, 'elan_service_category'); }
    }
}

function elan_content_seed_services() {
    if (get_posts(['post_type' => 'elan_service', 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids'])) { return; }
    elan_content_seed_service_terms();
    $items = [
        ['Signature Facial','signature-facial','Facials','A customized facial to deeply cleanse, hydrate and restore balance.',"Our Signature Facial is a personalized treatment tailored to your skin’s unique needs. Using advanced techniques and premium products, we cleanse, hydrate and nourish your skin while supporting long-term skin health.\n\nEach step is thoughtfully curated to restore balance, strengthen the skin barrier and enhance your natural radiance—leaving your complexion refreshed, smooth and luminous.",'60 min','$155','All skin types','https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=1400&q=85',['Deep cleansing to remove impurities','Hydration boost for plump, healthy skin','Improved texture and tone','Radiant glow that lasts'],[['Consultation','We assess your skin and concerns to create a custom treatment plan.'],['Cleanse & Exfoliate','Gentle cleansing and exfoliation remove buildup and prepare your skin.'],['Treatment Mask','Targeted masks and serums hydrate, calm and nourish deeply.'],['Aftercare Guidance','Personalized recommendations help maintain your results and glow.']],[['How often should I book this treatment?','Most clients benefit from a facial every four to six weeks, adjusted to their skin goals.'],['Is there downtime?','No planned downtime. Mild temporary redness can occur after exfoliation and typically settles quickly.'],['Is it suitable for sensitive skin?','Yes. The treatment is customized and can be adjusted for sensitive or reactive skin.']]],
        ['Hydrating Treatment','hydrating-treatment','Facials','Intense hydration therapy to plump, soothe and revitalize dry, tired skin.',"Designed for dehydrated, tight or lacklustre skin, this treatment layers replenishing hydration with calming care. Skin is left softer, more comfortable and visibly refreshed.\n\nWe tailor the treatment to your current condition, focusing on barrier support and long-lasting moisture rather than a temporary surface glow.",'60 min','$165','Dry & dehydrated skin','https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?auto=format&fit=crop&w=1400&q=85',['Replenishes depleted moisture','Supports the skin barrier','Softens visible dehydration lines','Restores a comfortable glow'],[['Skin Assessment','We identify dehydration patterns and current sensitivities.'],['Gentle Cleanse','A non-stripping cleanse prepares the skin without disrupting comfort.'],['Hydration Layers','Serums and masks deliver targeted, replenishing moisture.'],['Barrier Care','Finishing products help seal in hydration and support recovery.']],[['Who is this treatment best for?','It is ideal for dry, dehydrated, travel-stressed or seasonally compromised skin.'],['Is there downtime?','No. Skin should feel comfortable and hydrated immediately after treatment.']]],
        ['Renewal Peel','renewal-peel','Skin Treatments','Advanced exfoliation to refine texture, brighten tone and promote cell renewal.',"The Renewal Peel is a controlled resurfacing treatment designed to improve dullness, uneven tone and rough texture. The peel strength is selected according to your skin history and goals.\n\nOur approach prioritizes visible refinement while protecting the skin barrier, with clear preparation and aftercare guidance included.",'45 min','$175','Texture & uneven tone','https://images.unsplash.com/photo-1515377905703-c4788e51af15?auto=format&fit=crop&w=1400&q=85',['Refines uneven texture','Brightens dull-looking skin','Supports more even tone','Encourages fresh cell turnover'],[['Consultation','We review sensitivity, skincare use and resurfacing goals.'],['Preparation','The skin is cleansed and prepared for controlled exfoliation.'],['Customized Peel','A carefully selected peel is applied and monitored throughout.'],['Recovery Plan','You leave with simple aftercare guidance for the days ahead.']],[['Will my skin peel visibly?','Some formulations may cause light flaking, while others produce little to no visible peeling.'],['How much downtime should I expect?','Downtime varies by peel strength, but we will explain the expected recovery before treatment.']]],
        ['Brow Styling','brow-styling','Brows & Lashes','Brow shaping and enhancement for balanced, natural-looking definition.',"Brow Styling combines careful mapping, shaping and finishing to enhance your natural features without overpowering them. Every appointment is tailored to your face shape, growth pattern and preferred level of definition.\n\nThe result is polished, balanced brows that remain distinctly yours.",'30 min','$55','Natural definition','https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=1400&q=85',['Creates balanced definition','Works with natural brow growth','Refines shape and symmetry','Low-maintenance polished finish'],[['Brow Consultation','We discuss shape, maintenance and the finish you prefer.'],['Mapping','Your brow shape is planned around your natural features.'],['Shaping','Precise grooming refines the final silhouette.'],['Finishing','Brows are styled and finished with tailored product recommendations.']],[['How often should I return?','Most guests rebook every three to five weeks depending on growth.']]],
        ['Lash Lift','lash-lift','Brows & Lashes','Lift, curl and define your natural lashes for a longer, brighter look.',"The Lash Lift enhances your natural lashes by creating a soft, eye-opening curl from root to tip. It is designed for guests who want definition without extensions or daily curling.",'60 min','$85','Natural lash enhancement','https://images.unsplash.com/photo-1487412912498-0447578fcca8?auto=format&fit=crop&w=1400&q=85',['Creates an eye-opening curl','Enhances natural lash length','Reduces daily styling time','Soft, low-maintenance definition'],[['Consultation','We choose a lift style that suits your lashes and eye shape.'],['Lift & Set','The lifting process creates a controlled, lasting curl.'],['Aftercare','You receive simple guidance to protect the result.']],[['How long does a lash lift last?','Results commonly last six to eight weeks depending on your natural lash cycle.']]],
        ['Restorative Massage','restorative-massage','Massage','Therapeutic massage to release tension, improve circulation and restore calm.',"Our Restorative Massage blends slow, grounding techniques with focused therapeutic work where your body needs it most. Pressure and pace are adjusted throughout the session for comfort and effectiveness.",'60 min','$145','Tension & relaxation','https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=1400&q=85',['Releases muscular tension','Encourages circulation','Supports relaxation and recovery','Creates a deep sense of calm'],[['Consultation','We identify tension areas and your preferred pressure.'],['Warm-Up','Gentle techniques prepare the body and settle the nervous system.'],['Focused Massage','Targeted work addresses your priority areas.'],['Reset','A calm closing sequence helps you leave grounded and restored.']],[['Can the pressure be adjusted?','Yes. Pressure is always adapted to your comfort and goals.']]],
    ];
    foreach ($items as $index => $item) {
        [$title,$slug,$category,$excerpt,$content,$duration,$price,$best_for,$image,$benefits,$includes,$faqs] = $item;
        $id = wp_insert_post(['post_type'=>'elan_service','post_status'=>'publish','post_title'=>$title,'post_name'=>$slug,'post_excerpt'=>$excerpt,'post_content'=>$content,'menu_order'=>$index]);
        if (!$id || is_wp_error($id)) { continue; }
        wp_set_object_terms($id, $category, 'elan_service_category');
        foreach (['duration'=>$duration,'price'=>$price,'best_for'=>$best_for,'image'=>$image,'benefits'=>$benefits,'includes'=>$includes,'faqs'=>$faqs] as $key=>$value) { update_post_meta($id, 'elan_service_'.$key, $value); }
    }
}

function elan_content_seed_specialists() {
    if (get_posts(['post_type'=>'elan_specialist','post_status'=>'any','numberposts'=>1,'fields'=>'ids'])) { return; }
    $items = [
        ['Sofia Marin','sofia-marin','Founder & Lead Aesthetician','Specializing in advanced skincare and holistic facial rejuvenation.',"With over 10 years of experience in advanced skincare and facial rejuvenation, Sofia founded Maison Élan with a simple belief: real beauty comes from healthy, confident skin.\n\nHer approach combines results-focused treatments with a holistic, personalized touch to create natural, long-lasting outcomes.",'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=1200&q=85',['Advanced Facials','Anti-Aging Treatments','Skin Rejuvenation','Custom Skincare Plans'],'10+ Years','Enhance. Refine. Empower.',['International Dermal Institute — Advanced Aesthetics Diploma','CIDESCO Certification — Skincare & Facial Therapy','Ongoing training in advanced skin technologies and techniques']],
        ['Elena Laurent','elena-laurent','Senior Skin Specialist','Passionate about skin health and customized treatment plans.',"Elena brings a precise, thoughtful approach to skin health, combining careful consultation with treatment plans that evolve with each client.",'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=1200&q=85',['Corrective Facials','Hydration Therapy','Sensitive Skin','Barrier Repair'],'8 Years','Listen first. Treat thoughtfully.',['Advanced Facial Therapy Certification','Skin Barrier & Sensitivity Training','Continuing education in professional skincare']],
        ['Isabella Ricci','isabella-ricci','Brow & Lash Artist','Enhancing natural beauty through precise brows and lashes.',"Isabella specializes in subtle, polished brow and lash work designed around each client’s natural features.",'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=1200&q=85',['Brow Design','Brow Styling','Lash Lifts','Natural Definition'],'7 Years','Definition should still feel like you.',['Advanced Brow Mapping Certification','Professional Lash Lift Training','Ongoing artistry and product education']],
        ['Mei Ling','mei-ling','Nail Specialist','Detail-oriented nail artistry with an elegant, long-lasting finish.',"Mei’s work is grounded in immaculate preparation, healthy nail care and understated artistry.",'https://images.unsplash.com/photo-1531123897727-8f129e1688ce?auto=format&fit=crop&w=1200&q=85',['Structured Manicures','Natural Nail Care','Minimal Nail Art','Long-Wear Finishes'],'6 Years','Luxury lives in the details.',['Professional Nail Technology Diploma','Advanced Structured Gel Training','Sanitation and natural nail health certification']],
        ['Camille Dubois','camille-dubois','Massage Therapist','Focused on relaxation, recovery and overall well-being.',"Camille blends restorative massage techniques with a calm, intuitive approach to help clients release tension and reconnect with their bodies.",'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=1200&q=85',['Restorative Massage','Tension Release','Relaxation','Recovery Support'],'9 Years','Restore ease through intentional care.',['Registered Massage Therapy Diploma','Myofascial and relaxation technique training','Continuing education in recovery-focused bodywork']],
        ['Juliette Moreau','juliette-moreau','Wellness Coordinator','Your guide to a balanced beauty and wellness journey.',"Juliette helps guests navigate services, treatment timing and long-term care plans so every visit feels effortless and considered.",'https://images.unsplash.com/photo-1488426862026-3ee34a7d66df?auto=format&fit=crop&w=1200&q=85',['Client Consultations','Treatment Planning','Wellness Journeys','Studio Care'],'5 Years','Care should feel seamless and personal.',['Beauty & Wellness Management Diploma','Client Experience Training','Continuing education in treatment planning and skincare']],
    ];
    foreach ($items as $index => $item) {
        [$title,$slug,$role,$excerpt,$content,$image,$specialties,$experience,$philosophy,$education] = $item;
        $id = wp_insert_post(['post_type'=>'elan_specialist','post_status'=>'publish','post_title'=>$title,'post_name'=>$slug,'post_excerpt'=>$excerpt,'post_content'=>$content,'menu_order'=>$index]);
        if (!$id || is_wp_error($id)) { continue; }
        foreach (compact('role','image','specialties','experience','philosophy','education') as $key=>$value) { update_post_meta($id, 'elan_specialist_'.$key, $value); }
    }
}

function elan_content_seed_packages() {
    if (get_posts(['post_type'=>'elan_package','post_status'=>'any','numberposts'=>1,'fields'=>'ids'])) { return; }
    $items = [
        ['Essential Glow','$195','Perfect for maintaining healthy, radiant skin.',['Signature Facial','LED Light Therapy','Custom Skincare Plan'],false],
        ['Radiance Renewal','$395','Advanced treatments for visible renewal and glow.',['Skin Renewal Treatment','LED Light Therapy','Hydrating Mask','Post-Treatment Care Kit'],true],
        ['Ultimate Indulgence','$595','The ultimate luxury experience from head to toe.',['Signature Facial','Massage Ritual (60 min)','LED Light Therapy','Scalp & Hand Treatment'],false],
    ];
    foreach ($items as $index => $item) {
        [$title,$price,$excerpt,$features,$featured] = $item;
        $id = wp_insert_post(['post_type'=>'elan_package','post_status'=>'publish','post_title'=>$title,'post_excerpt'=>$excerpt,'menu_order'=>$index]);
        if (!$id || is_wp_error($id)) { continue; }
        update_post_meta($id, 'elan_package_price', $price); update_post_meta($id, 'elan_package_features', $features); update_post_meta($id, 'elan_package_featured', $featured ? '1' : '0');
    }
}

function elan_content_seed_testimonials() {
    if (get_posts(['post_type'=>'elan_testimonial','post_status'=>'any','numberposts'=>1,'fields'=>'ids'])) { return; }
    $items = [
        ['Léa B.','Maison Élan is my sanctuary. The attention to detail, the ambiance, and the results are unmatched. I leave every appointment feeling refreshed and glowing.'],
        ['Marie P.','The team is incredibly knowledgeable and makes you feel so cared for. My skin has never looked better.'],
        ['Julie D.','Luxury, professionalism and real results. I wouldn’t trust anyone else with my skin and brows.'],
    ];
    foreach ($items as $index => $item) {
        [$client,$quote] = $item;
        $id = wp_insert_post(['post_type'=>'elan_testimonial','post_status'=>'publish','post_title'=>$client,'post_content'=>$quote,'menu_order'=>$index]);
        if ($id && !is_wp_error($id)) { update_post_meta($id, 'elan_testimonial_client', $client); }
    }
}

function elan_content_seed_demo_data() {
    elan_content_register_types();
    elan_content_seed_service_terms();
    elan_content_seed_services();
    elan_content_seed_specialists();
    elan_content_seed_packages();
    elan_content_seed_testimonials();
}

function elan_content_activate() {
    elan_content_seed_demo_data();
    foreach (['address'=>'123 Belleview Avenue, Toronto, ON M5R 2L3','phone'=>'(647) 555-1234','instagram'=>'@maison.elan.studio','email'=>'hello@example.com'] as $key=>$default) {
        if (get_option('elan_business_'.$key, null) === null) {
            $theme_value = get_theme_mod('elan_'.$key, '');
            add_option('elan_business_'.$key, $theme_value !== '' ? $theme_value : $default);
        }
    }
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'elan_content_activate');

function elan_content_ensure_seeded() {
    if (get_option('elan_content_seed_version') === '1') { return; }
    elan_content_seed_demo_data();
    update_option('elan_content_seed_version', '1', false);
}
add_action('init', 'elan_content_ensure_seeded', 30);

function elan_content_deactivate() { flush_rewrite_rules(); }
register_deactivation_hook(__FILE__, 'elan_content_deactivate');

function elan_content_add_meta_boxes() {
    add_meta_box('elan-service-details','Service Details','elan_content_service_box','elan_service','normal','high');
    add_meta_box('elan-specialist-details','Specialist Details','elan_content_specialist_box','elan_specialist','normal','high');
    add_meta_box('elan-package-details','Package Details','elan_content_package_box','elan_package','normal','high');
    add_meta_box('elan-testimonial-details','Testimonial Details','elan_content_testimonial_box','elan_testimonial','normal','high');
}
add_action('add_meta_boxes', 'elan_content_add_meta_boxes');

function elan_content_admin_style() { echo '<style>.elan-admin-field{margin:0 0 18px}.elan-admin-field label{display:block;font-weight:600;margin-bottom:6px}.elan-admin-field input,.elan-admin-field textarea{width:100%}.elan-admin-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}@media(max-width:782px){.elan-admin-grid{grid-template-columns:1fr}}</style>'; }

function elan_content_service_box($post) {
    wp_nonce_field('elan_content_save_service','elan_content_service_nonce'); elan_content_admin_style();
    $benefits=get_post_meta($post->ID,'elan_service_benefits',true); $includes=get_post_meta($post->ID,'elan_service_includes',true); $faqs=get_post_meta($post->ID,'elan_service_faqs',true);
    $rows=function($items){$out=''; if(is_array($items)){foreach($items as $item){$out.=($item[0]??'').'|'.($item[1]??'')."\n";}} return trim($out);};
    ?>
    <div class="elan-admin-grid"><p class="elan-admin-field"><label>Duration</label><input name="elan_service_duration" value="<?php echo esc_attr(elan_service_meta($post->ID,'duration')); ?>"></p><p class="elan-admin-field"><label>Starting price</label><input name="elan_service_price" value="<?php echo esc_attr(elan_service_meta($post->ID,'price')); ?>"></p></div>
    <p class="elan-admin-field"><label>Best for</label><input name="elan_service_best_for" value="<?php echo esc_attr(elan_service_meta($post->ID,'best_for')); ?>"></p>
    <p class="elan-admin-field"><label>Image URL</label><input type="url" name="elan_service_image" value="<?php echo esc_attr(elan_service_meta($post->ID,'image')); ?>"></p>
    <p class="elan-admin-field"><label>Benefits — one per line</label><textarea rows="5" name="elan_service_benefits"><?php echo esc_textarea(is_array($benefits)?implode("\n",$benefits):''); ?></textarea></p>
    <p class="elan-admin-field"><label>What’s included — Title|Description per line</label><textarea rows="6" name="elan_service_includes"><?php echo esc_textarea($rows($includes)); ?></textarea></p>
    <p class="elan-admin-field"><label>FAQs — Question|Answer per line</label><textarea rows="6" name="elan_service_faqs"><?php echo esc_textarea($rows($faqs)); ?></textarea></p><?php
}

function elan_content_specialist_box($post) {
    wp_nonce_field('elan_content_save_specialist','elan_content_specialist_nonce'); elan_content_admin_style();
    $specialties=get_post_meta($post->ID,'elan_specialist_specialties',true); $education=get_post_meta($post->ID,'elan_specialist_education',true); ?>
    <div class="elan-admin-grid"><p class="elan-admin-field"><label>Role</label><input name="elan_specialist_role" value="<?php echo esc_attr(elan_specialist_meta($post->ID,'role')); ?>"></p><p class="elan-admin-field"><label>Experience</label><input name="elan_specialist_experience" value="<?php echo esc_attr(elan_specialist_meta($post->ID,'experience')); ?>"></p></div>
    <p class="elan-admin-field"><label>Philosophy</label><input name="elan_specialist_philosophy" value="<?php echo esc_attr(elan_specialist_meta($post->ID,'philosophy')); ?>"></p>
    <p class="elan-admin-field"><label>Portrait image URL</label><input type="url" name="elan_specialist_image" value="<?php echo esc_attr(elan_specialist_meta($post->ID,'image')); ?>"></p>
    <p class="elan-admin-field"><label>Specialties — one per line</label><textarea rows="5" name="elan_specialist_specialties"><?php echo esc_textarea(is_array($specialties)?implode("\n",$specialties):''); ?></textarea></p>
    <p class="elan-admin-field"><label>Education & certifications — one per line</label><textarea rows="5" name="elan_specialist_education"><?php echo esc_textarea(is_array($education)?implode("\n",$education):''); ?></textarea></p><?php
}

function elan_content_package_box($post) {
    wp_nonce_field('elan_content_save_package','elan_content_package_nonce'); elan_content_admin_style(); $features=get_post_meta($post->ID,'elan_package_features',true); ?>
    <p class="elan-admin-field"><label>Price</label><input name="elan_package_price" value="<?php echo esc_attr(elan_package_meta($post->ID,'price')); ?>"></p>
    <p class="elan-admin-field"><label>Features — one per line</label><textarea rows="6" name="elan_package_features"><?php echo esc_textarea(is_array($features)?implode("\n",$features):''); ?></textarea></p>
    <p><label><input type="checkbox" name="elan_package_featured" value="1" <?php checked(elan_package_meta($post->ID,'featured'),'1'); ?>> Feature this package</label></p><?php
}

function elan_content_testimonial_box($post) {
    wp_nonce_field('elan_content_save_testimonial','elan_content_testimonial_nonce'); elan_content_admin_style(); ?>
    <p class="elan-admin-field"><label>Client display name</label><input name="elan_testimonial_client" value="<?php echo esc_attr(elan_testimonial_client($post->ID)); ?>"></p><?php
}

function elan_content_can_save($post_id, $nonce_name, $action) {
    return isset($_POST[$nonce_name]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST[$nonce_name])), $action) && !(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) && current_user_can('edit_post',$post_id);
}

function elan_content_save_service($post_id) {
    if (!elan_content_can_save($post_id,'elan_content_service_nonce','elan_content_save_service')) { return; }
    foreach (['duration','price','best_for'] as $field) { if(isset($_POST['elan_service_'.$field])) update_post_meta($post_id,'elan_service_'.$field,sanitize_text_field(wp_unslash($_POST['elan_service_'.$field]))); }
    if(isset($_POST['elan_service_image'])) update_post_meta($post_id,'elan_service_image',esc_url_raw(wp_unslash($_POST['elan_service_image'])));
    if(isset($_POST['elan_service_benefits'])) update_post_meta($post_id,'elan_service_benefits',elan_content_lines(wp_unslash($_POST['elan_service_benefits'])));
    if(isset($_POST['elan_service_includes'])) update_post_meta($post_id,'elan_service_includes',elan_content_pipe_rows(wp_unslash($_POST['elan_service_includes'])));
    if(isset($_POST['elan_service_faqs'])) update_post_meta($post_id,'elan_service_faqs',elan_content_pipe_rows(wp_unslash($_POST['elan_service_faqs'])));
}
add_action('save_post_elan_service','elan_content_save_service');

function elan_content_save_specialist($post_id) {
    if (!elan_content_can_save($post_id,'elan_content_specialist_nonce','elan_content_save_specialist')) { return; }
    foreach(['role','experience','philosophy'] as $field){if(isset($_POST['elan_specialist_'.$field])) update_post_meta($post_id,'elan_specialist_'.$field,sanitize_text_field(wp_unslash($_POST['elan_specialist_'.$field])));}
    if(isset($_POST['elan_specialist_image'])) update_post_meta($post_id,'elan_specialist_image',esc_url_raw(wp_unslash($_POST['elan_specialist_image'])));
    if(isset($_POST['elan_specialist_specialties'])) update_post_meta($post_id,'elan_specialist_specialties',elan_content_lines(wp_unslash($_POST['elan_specialist_specialties'])));
    if(isset($_POST['elan_specialist_education'])) update_post_meta($post_id,'elan_specialist_education',elan_content_lines(wp_unslash($_POST['elan_specialist_education'])));
}
add_action('save_post_elan_specialist','elan_content_save_specialist');

function elan_content_save_package($post_id) {
    if (!elan_content_can_save($post_id,'elan_content_package_nonce','elan_content_save_package')) { return; }
    if(isset($_POST['elan_package_price'])) update_post_meta($post_id,'elan_package_price',sanitize_text_field(wp_unslash($_POST['elan_package_price'])));
    if(isset($_POST['elan_package_features'])) update_post_meta($post_id,'elan_package_features',elan_content_lines(wp_unslash($_POST['elan_package_features'])));
    update_post_meta($post_id,'elan_package_featured',isset($_POST['elan_package_featured'])?'1':'0');
}
add_action('save_post_elan_package','elan_content_save_package');

function elan_content_save_testimonial($post_id) {
    if (!elan_content_can_save($post_id,'elan_content_testimonial_nonce','elan_content_save_testimonial')) { return; }
    if(isset($_POST['elan_testimonial_client'])) update_post_meta($post_id,'elan_testimonial_client',sanitize_text_field(wp_unslash($_POST['elan_testimonial_client'])));
}
add_action('save_post_elan_testimonial','elan_content_save_testimonial');

function elan_content_business_settings() {
    register_setting('elan_business','elan_business_address',['sanitize_callback'=>'sanitize_text_field']);
    register_setting('elan_business','elan_business_phone',['sanitize_callback'=>'sanitize_text_field']);
    register_setting('elan_business','elan_business_instagram',['sanitize_callback'=>'sanitize_text_field']);
    register_setting('elan_business','elan_business_email',['sanitize_callback'=>'sanitize_email']);
    add_settings_section('elan_business_main','Business details',function(){echo '<p>Reusable studio details shared across themes and templates.</p>';},'elan-business');
    foreach(['address'=>'Address','phone'=>'Phone','instagram'=>'Instagram','email'=>'Email'] as $key=>$label){
        add_settings_field('elan_business_'.$key,$label,function()use($key){$type=$key==='email'?'email':'text';printf('<input class="regular-text" type="%s" name="elan_business_%s" value="%s">',esc_attr($type),esc_attr($key),esc_attr(elan_business_detail($key)));},'elan-business','elan_business_main');
    }
}
add_action('admin_init','elan_content_business_settings');

function elan_content_business_page() {
    add_options_page('Maison Élan','Maison Élan','manage_options','elan-business',function(){ ?><div class="wrap"><h1>Maison Élan</h1><form method="post" action="options.php"><?php settings_fields('elan_business'); do_settings_sections('elan-business'); submit_button(); ?></form></div><?php });
}
add_action('admin_menu','elan_content_business_page');
