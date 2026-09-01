<?php
get_header();

$services = new WP_Query([
    'post_type' => 'elan_service',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => ['menu_order' => 'ASC', 'date' => 'ASC'],
]);
$packages = new WP_Query([
    'post_type' => 'elan_package',
    'post_status' => 'publish',
    'posts_per_page' => 3,
    'orderby' => ['menu_order' => 'ASC', 'date' => 'ASC'],
]);
?>
<main class="elan-pricing-page">
  <section class="elan-page-hero" aria-labelledby="pricing-title">
    <img class="elan-page-hero__image" src="https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=1800&q=85" alt="Premium facial treatment">
    <div class="elan-shell elan-page-hero__content">
      <p class="elan-kicker">Maison Élan</p>
      <h1 id="pricing-title">Pricing</h1>
      <p>Transparent pricing for thoughtful, personalized care and lasting results.</p>
    </div>
  </section>

  <section class="elan-section elan-section--white">
    <div class="elan-shell">
      <div class="elan-section__head">
        <p class="elan-kicker">Signature treatments</p>
        <h2 class="elan-title">Treatments tailored to you</h2>
      </div>
      <?php if ($services->have_posts()): ?>
        <table class="elan-price-table">
          <thead><tr><th>Treatment</th><th>Duration</th><th>Price</th></tr></thead>
          <tbody>
          <?php while ($services->have_posts()): $services->the_post(); $id = get_the_ID(); ?>
            <tr>
              <td><a class="elan-price-table__service" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></td>
              <td><?php echo esc_html(function_exists('elan_service_meta') ? elan_service_meta($id, 'duration') : ''); ?></td>
              <td><?php echo esc_html(function_exists('elan_service_meta') ? elan_service_meta($id, 'price') : ''); ?></td>
            </tr>
          <?php endwhile; wp_reset_postdata(); ?>
          </tbody>
        </table>
        <div style="text-align:center;margin-top:28px"><a class="elan-button elan-button--ghost" href="<?php echo esc_url(elan_theme_services_url()); ?>">View all services</a></div>
      <?php endif; ?>
    </div>
  </section>

  <section class="elan-section elan-section--soft">
    <div class="elan-shell">
      <div class="elan-section__head">
        <p class="elan-kicker">Packages & memberships</p>
        <h2 class="elan-title">A considered approach to ongoing care</h2>
      </div>
      <?php if ($packages->have_posts()): ?>
        <div class="elan-pricing">
          <?php while ($packages->have_posts()): $packages->the_post(); $id = get_the_ID(); $features = get_post_meta($id, 'elan_package_features', true); $featured = function_exists('elan_package_meta') && elan_package_meta($id, 'featured') === '1'; ?>
            <article class="elan-price <?php echo $featured ? 'elan-price--featured' : ''; ?>">
              <?php if ($featured): ?><div class="elan-price__badge">Most loved</div><?php endif; ?>
              <div class="elan-price__name"><?php the_title(); ?></div>
              <div class="elan-price__amount"><?php echo esc_html(function_exists('elan_package_meta') ? elan_package_meta($id, 'price') : ''); ?></div>
              <p class="elan-price__summary"><?php echo esc_html(get_the_excerpt()); ?></p>
              <?php if (is_array($features)): ?><ul><?php foreach ($features as $feature): ?><li><?php echo esc_html($feature); ?></li><?php endforeach; ?></ul><?php endif; ?>
              <a class="elan-button <?php echo $featured ? '' : 'elan-button--ghost'; ?>" href="<?php echo esc_url(elan_theme_studio_url()); ?>#contact">Choose plan</a>
            </article>
          <?php endwhile; wp_reset_postdata(); ?>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <section class="elan-page-cta">
    <div class="elan-page-cta__copy">
      <p class="elan-kicker">Personalized care</p>
      <h2 class="elan-title">Looking for something customized for you?</h2>
      <p class="elan-copy">Our specialists can help shape a treatment plan around your skin, schedule and goals.</p>
      <a class="elan-button" href="<?php echo esc_url(elan_theme_studio_url()); ?>#contact">Book a consultation</a>
    </div>
    <img class="elan-page-cta__image" src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=1400&q=85" alt="Relaxing treatment at Maison Élan">
  </section>
</main>
<?php get_footer(); ?>
