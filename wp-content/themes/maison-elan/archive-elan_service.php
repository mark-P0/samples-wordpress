<?php
get_header();

$hero_image = 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=1800&q=85';
$current_category = isset($_GET['category']) ? sanitize_title(wp_unslash($_GET['category'])) : '';

$query_args = [
    'post_type' => 'elan_service',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => ['menu_order' => 'ASC', 'date' => 'ASC'],
];

if ($current_category) {
    $query_args['tax_query'] = [[
        'taxonomy' => 'elan_service_category',
        'field' => 'slug',
        'terms' => $current_category,
    ]];
}

$services_query = new WP_Query($query_args);
$categories = get_terms([
    'taxonomy' => 'elan_service_category',
    'hide_empty' => true,
]);

$signature = get_page_by_path('signature-facial', OBJECT, 'elan_service');
?>

<main class="elan-services-page">
  <section class="elan-services-hero" aria-labelledby="services-page-title">
    <img class="elan-services-hero__image" src="<?php echo esc_url($hero_image); ?>" alt="Relaxing facial treatment at Maison Élan">
    <div class="elan-services-hero__overlay"></div>
    <div class="elan-shell elan-services-hero__content">
      <p class="elan-kicker elan-kicker--light">Our services</p>
      <h1 id="services-page-title">Services</h1>
      <p>Thoughtful treatments tailored for radiant, visible results.</p>
    </div>
  </section>

  <section class="elan-service-catalog elan-section elan-section--white">
    <div class="elan-shell">
      <nav class="elan-service-filters" aria-label="Service categories">
        <a class="<?php echo $current_category === '' ? 'is-active' : ''; ?>" href="<?php echo esc_url(elan_services_url()); ?>">All</a>
        <?php if (!is_wp_error($categories)): ?>
          <?php foreach ($categories as $category): ?>
            <a class="<?php echo $current_category === $category->slug ? 'is-active' : ''; ?>" href="<?php echo esc_url(add_query_arg('category', $category->slug, elan_services_url())); ?>"><?php echo esc_html($category->name); ?></a>
          <?php endforeach; ?>
        <?php endif; ?>
      </nav>

      <?php if ($services_query->have_posts()): ?>
        <div class="elan-service-grid">
          <?php while ($services_query->have_posts()): $services_query->the_post(); ?>
            <?php
            $service_id = get_the_ID();
            $duration = elan_service_meta($service_id, 'duration', '60 min');
            $price = elan_service_meta($service_id, 'price', '$155');
            ?>
            <article class="elan-service-tile">
              <a class="elan-service-tile__image-link" href="<?php the_permalink(); ?>" aria-label="View <?php the_title_attribute(); ?>">
                <img class="elan-service-tile__image" src="<?php echo esc_url(elan_service_image($service_id)); ?>" alt="">
              </a>
              <div class="elan-service-tile__body">
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <p><?php echo esc_html(get_the_excerpt()); ?></p>
                <div class="elan-service-tile__meta">
                  <span>◷ <?php echo esc_html($duration); ?></span>
                  <strong>From <?php echo esc_html($price); ?></strong>
                </div>
              </div>
            </article>
          <?php endwhile; ?>
        </div>
      <?php else: ?>
        <div class="elan-service-empty">
          <p>No services are available in this category yet.</p>
          <a class="elan-text-link" href="<?php echo esc_url(elan_services_url()); ?>">View all services →</a>
        </div>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </section>

  <?php if ($signature instanceof WP_Post): ?>
    <?php
    $signature_id = $signature->ID;
    $signature_benefits = get_post_meta($signature_id, 'elan_service_benefits', true);
    ?>
    <section class="elan-signature-feature">
      <div class="elan-shell elan-signature-feature__inner">
        <div class="elan-signature-feature__media">
          <img src="<?php echo esc_url(elan_service_image($signature_id)); ?>" alt="Signature Facial treatment">
        </div>
        <div class="elan-signature-feature__content">
          <p class="elan-kicker">Our signature</p>
          <h2><?php echo esc_html(get_the_title($signature_id)); ?></h2>
          <p><?php echo esc_html(get_the_excerpt($signature_id)); ?> Our most indulgent facial experience combines expert technique with a calm, highly personalized approach.</p>
          <?php if (is_array($signature_benefits) && $signature_benefits): ?>
            <div class="elan-signature-feature__benefits">
              <?php foreach (array_slice($signature_benefits, 0, 3) as $benefit): ?>
                <span>✦ <?php echo esc_html($benefit); ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <div class="elan-signature-feature__footer">
            <div>
              <span><?php echo esc_html(elan_service_meta($signature_id, 'duration', '60 min')); ?></span>
              <span><?php echo esc_html(elan_service_meta($signature_id, 'best_for', 'All skin types')); ?></span>
              <span>From <?php echo esc_html(elan_service_meta($signature_id, 'price', '$155')); ?></span>
            </div>
            <a class="elan-button" href="<?php echo esc_url(get_permalink($signature_id)); ?>">Learn more</a>
          </div>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <section class="elan-service-approach elan-section elan-section--white">
    <div class="elan-shell">
      <div class="elan-section__head">
        <p class="elan-kicker">Our approach</p>
        <h2 class="elan-title">What to Expect</h2>
      </div>
      <div class="elan-approach-grid">
        <article><span class="elan-approach-grid__number">01</span><h3>Consult</h3><p>We start with a personalized consultation to understand your goals, preferences and skin needs.</p></article>
        <article><span class="elan-approach-grid__number">02</span><h3>Treat</h3><p>Every service is thoughtfully tailored using expert technique and premium, results-focused products.</p></article>
        <article><span class="elan-approach-grid__number">03</span><h3>Care</h3><p>We finish with practical aftercare guidance so your results feel considered well beyond the appointment.</p></article>
      </div>
    </div>
  </section>

  <section class="elan-services-cta">
    <div class="elan-shell elan-services-cta__inner">
      <div>
        <p class="elan-kicker elan-kicker--light">Personalized care</p>
        <h2>Ready to feel your best?</h2>
        <p>Book a consultation and let us create a treatment plan around you.</p>
      </div>
      <a class="elan-button elan-button--light" href="<?php echo esc_url(elan_contact_url()); ?>">Book your consultation</a>
    </div>
  </section>
</main>

<?php get_footer(); ?>
