<?php
get_header();

while (have_posts()):
    the_post();
    $service_id = get_the_ID();
    $terms = get_the_terms($service_id, 'elan_service_category');
    $category_name = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : 'Treatment';
    $duration = elan_service_meta($service_id, 'duration', '60 min');
    $price = elan_service_meta($service_id, 'price', '$155');
    $best_for = elan_service_meta($service_id, 'best_for', 'All skin types');
    $benefits = get_post_meta($service_id, 'elan_service_benefits', true);
    $includes = get_post_meta($service_id, 'elan_service_includes', true);
    $faqs = get_post_meta($service_id, 'elan_service_faqs', true);
    $service_image = elan_service_image($service_id);

    if (!is_array($benefits)) { $benefits = []; }
    if (!is_array($includes)) { $includes = []; }
    if (!is_array($faqs)) { $faqs = []; }

    $related = new WP_Query([
        'post_type' => 'elan_service',
        'post_status' => 'publish',
        'posts_per_page' => 3,
        'post__not_in' => [$service_id],
        'orderby' => 'rand',
    ]);
?>

<main class="elan-service-detail">
  <div class="elan-shell elan-breadcrumbs" aria-label="Breadcrumb">
    <a href="<?php echo esc_url(home_url('/')); ?>">Home</a><span>/</span>
    <a href="<?php echo esc_url(elan_services_url()); ?>">Services</a><span>/</span>
    <span aria-current="page"><?php the_title(); ?></span>
  </div>

  <section class="elan-service-detail-hero">
    <div class="elan-shell elan-service-detail-hero__grid">
      <div class="elan-service-detail-hero__media">
        <img src="<?php echo esc_url($service_image); ?>" alt="<?php the_title_attribute(); ?> at Maison Élan">
      </div>
      <div class="elan-service-detail-hero__content">
        <p class="elan-kicker"><?php echo esc_html($category_name); ?></p>
        <h1><?php the_title(); ?></h1>
        <p class="elan-service-detail-hero__lede"><?php echo esc_html(get_the_excerpt()); ?></p>

        <div class="elan-service-facts">
          <div><span>Duration</span><strong><?php echo esc_html($duration); ?></strong></div>
          <div><span>Price</span><strong>From <?php echo esc_html($price); ?></strong></div>
          <div><span>Best for</span><strong><?php echo esc_html($best_for); ?></strong></div>
          <div><span>Rated</span><strong>5.0 ★★★★★</strong></div>
        </div>

        <div class="elan-service-detail-hero__actions">
          <a class="elan-button" href="<?php echo esc_url(elan_contact_url()); ?>">Book this treatment</a>
          <a class="elan-button elan-button--outline" href="<?php echo esc_url(elan_contact_url()); ?>">Ask a question</a>
        </div>
      </div>
    </div>
  </section>

  <section class="elan-service-overview elan-section elan-section--white">
    <div class="elan-shell elan-service-overview__grid">
      <div>
        <h2 class="elan-title">Treatment Overview</h2>
        <div class="elan-service-overview__copy"><?php the_content(); ?></div>
      </div>
      <aside class="elan-service-benefits">
        <p class="elan-kicker">Key benefits</p>
        <ul>
          <?php foreach ($benefits as $benefit): ?>
            <li><span aria-hidden="true">✦</span><?php echo esc_html($benefit); ?></li>
          <?php endforeach; ?>
        </ul>
      </aside>
    </div>
  </section>

  <?php if ($includes): ?>
    <section class="elan-service-includes elan-section elan-section--white">
      <div class="elan-shell">
        <div class="elan-section__head"><h2 class="elan-title">What’s Included</h2></div>
        <div class="elan-includes-grid">
          <?php foreach ($includes as $index => $item): ?>
            <article>
              <span class="elan-includes-grid__number"><?php echo esc_html($index + 1); ?></span>
              <div class="elan-includes-grid__icon" aria-hidden="true">○</div>
              <h3><?php echo esc_html($item[0] ?? ''); ?></h3>
              <p><?php echo esc_html($item[1] ?? ''); ?></p>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <section class="elan-results">
    <div class="elan-shell elan-results__grid">
      <div class="elan-results__content">
        <p class="elan-kicker">Results & benefits</p>
        <h2 class="elan-title">Care that leaves a lasting glow</h2>
        <p>Your treatment is designed to leave you feeling refreshed and cared for, with results that support your natural features rather than overpower them.</p>
        <?php if ($benefits): ?>
          <div class="elan-results__tags">
            <?php foreach (array_slice($benefits, 0, 4) as $benefit): ?><span><?php echo esc_html($benefit); ?></span><?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
      <div class="elan-results__gallery">
        <img src="<?php echo esc_url($service_image); ?>" alt="">
        <img src="https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?auto=format&fit=crop&w=800&q=85" alt="Skincare detail at Maison Élan">
        <img src="https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=800&q=85" alt="Beauty treatment result detail">
      </div>
    </div>
  </section>

  <?php if ($related->have_posts()): ?>
    <section class="elan-related-services elan-section elan-section--white">
      <div class="elan-shell">
        <div class="elan-section__head"><p class="elan-kicker">Continue your ritual</p><h2 class="elan-title">Recommended Pairings</h2></div>
        <div class="elan-related-grid">
          <?php while ($related->have_posts()): $related->the_post(); ?>
            <?php $related_id = get_the_ID(); ?>
            <article class="elan-related-card">
              <a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url(elan_service_image($related_id)); ?>" alt=""></a>
              <div>
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <p><?php echo esc_html(get_the_excerpt()); ?></p>
                <div class="elan-related-card__meta"><span>◷ <?php echo esc_html(elan_service_meta($related_id, 'duration', '60 min')); ?></span><strong>From <?php echo esc_html(elan_service_meta($related_id, 'price', '$155')); ?></strong></div>
              </div>
            </article>
          <?php endwhile; ?>
        </div>
      </div>
    </section>
    <?php wp_reset_postdata(); ?>
  <?php endif; ?>

  <?php if ($faqs): ?>
    <section class="elan-service-faq elan-section elan-section--white">
      <div class="elan-shell elan-service-faq__inner">
        <div class="elan-section__head"><h2 class="elan-title">Frequently Asked Questions</h2></div>
        <div class="elan-faq-list">
          <?php foreach ($faqs as $faq): ?>
            <details>
              <summary><?php echo esc_html($faq[0] ?? ''); ?><span aria-hidden="true">＋</span></summary>
              <p><?php echo esc_html($faq[1] ?? ''); ?></p>
            </details>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <section class="elan-detail-cta">
    <div class="elan-shell elan-detail-cta__inner">
      <div>
        <p class="elan-kicker">Your next ritual</p>
        <h2>Ready to book your <?php the_title(); ?>?</h2>
        <p>Personalized care, thoughtful technique and a calm experience from start to finish.</p>
      </div>
      <a class="elan-button" href="<?php echo esc_url(elan_contact_url()); ?>">Book your appointment</a>
    </div>
  </section>
</main>

<?php
endwhile;
get_footer();
?>
