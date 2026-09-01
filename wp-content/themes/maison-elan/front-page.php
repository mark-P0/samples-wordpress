<?php
get_header();

$images = [
  'hero' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=1800&q=85',
  'studio' => 'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=1400&q=85',
  'visit' => 'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1400&q=85',
];

$services_query = new WP_Query([
  'post_type' => 'elan_service',
  'post_status' => 'publish',
  'posts_per_page' => 4,
  'orderby' => ['menu_order' => 'ASC', 'date' => 'ASC'],
]);
$specialists_query = new WP_Query([
  'post_type' => 'elan_specialist',
  'post_status' => 'publish',
  'posts_per_page' => 3,
  'orderby' => ['menu_order' => 'ASC', 'date' => 'ASC'],
]);
$packages_query = new WP_Query([
  'post_type' => 'elan_package',
  'post_status' => 'publish',
  'posts_per_page' => 3,
  'orderby' => ['menu_order' => 'ASC', 'date' => 'ASC'],
]);
$testimonials_query = new WP_Query([
  'post_type' => 'elan_testimonial',
  'post_status' => 'publish',
  'posts_per_page' => 3,
  'orderby' => ['menu_order' => 'ASC', 'date' => 'ASC'],
]);
?>

<main>
  <section class="elan-hero" aria-labelledby="hero-title">
    <img class="elan-hero__photo" src="<?php echo esc_url($images['hero']); ?>" alt="Relaxing premium facial treatment">
    <div class="elan-shell elan-hero__content">
      <div class="elan-hero__mark" aria-hidden="true">— MÉ —</div>
      <h1 id="hero-title"><?php echo esc_html(elan_mod('hero_title', 'Refined Beauty, Thoughtfully Curated')); ?></h1>
      <p><?php echo esc_html(elan_mod('hero_copy', 'Personalized treatments in a serene, luxurious space designed to enhance your natural beauty and wellbeing.')); ?></p>
      <a class="elan-button" href="#contact"><?php echo esc_html(elan_mod('hero_cta', 'Book your consultation')); ?></a>
    </div>
  </section>

  <section class="elan-section elan-section--white" id="services">
    <div class="elan-shell">
      <div class="elan-section__head"><p class="elan-kicker">Our signature services</p><h2 class="elan-title"><?php echo esc_html(elan_mod('services_title', 'Thoughtful Treatments. Visible Results.')); ?></h2></div>
      <?php if ($services_query->have_posts()): ?>
        <div class="elan-services">
          <?php while ($services_query->have_posts()): $services_query->the_post(); ?>
            <?php $service_id = get_the_ID(); ?>
            <article class="elan-card elan-service">
              <img class="elan-service__image" src="<?php echo esc_url(function_exists('elan_service_image') ? elan_service_image($service_id) : get_the_post_thumbnail_url($service_id, 'large')); ?>" alt="">
              <div class="elan-service__body">
                <h3 class="elan-service__title"><?php the_title(); ?></h3>
                <p class="elan-service__desc"><?php echo esc_html(get_the_excerpt()); ?></p>
                <a class="elan-text-link" href="<?php the_permalink(); ?>">Learn more →</a>
              </div>
            </article>
          <?php endwhile; ?>
        </div>
        <?php wp_reset_postdata(); ?>
      <?php endif; ?>
    </div>
  </section>

  <section class="elan-about" id="studio">
    <img class="elan-about__image" src="<?php echo esc_url($images['studio']); ?>" alt="Maison Élan reception and studio interior">
    <div class="elan-about__content">
      <p class="elan-kicker">About Maison Élan</p>
      <h2 class="elan-title"><?php echo esc_html(elan_mod('about_title', 'A Sanctuary for Timeless Beauty')); ?></h2>
      <p class="elan-copy"><?php echo esc_html(elan_mod('about_copy', 'Maison Élan is a boutique beauty and aesthetics studio where science meets sophistication. Our curated treatments and premium products are designed to enhance your natural beauty in a calm, luxurious setting.')); ?></p>
      <div class="elan-values"><div class="elan-value"><span class="elan-value__icon">◌</span><strong>Personalized<br>Care</strong></div><div class="elan-value"><span class="elan-value__icon">♧</span><strong>Advanced<br>Technology</strong></div><div class="elan-value"><span class="elan-value__icon">♔</span><strong>Luxury<br>Experience</strong></div></div>
      <div><a class="elan-button" href="#contact">Discover our studio</a></div>
    </div>
  </section>

  <section class="elan-section elan-section--white" id="specialists">
    <div class="elan-shell">
      <div class="elan-section__head"><p class="elan-kicker">Meet our specialists</p><h2 class="elan-title"><?php echo esc_html(elan_mod('specialists_title', 'Experts in Enhancing Your Natural Beauty')); ?></h2></div>
      <?php if ($specialists_query->have_posts()): ?>
        <div class="elan-specialists">
          <?php while ($specialists_query->have_posts()): $specialists_query->the_post(); ?>
            <?php $specialist_id = get_the_ID(); ?>
            <article class="elan-card elan-person">
              <a href="<?php the_permalink(); ?>"><img class="elan-person__image" src="<?php echo esc_url(function_exists('elan_specialist_image') ? elan_specialist_image($specialist_id) : get_the_post_thumbnail_url($specialist_id, 'large')); ?>" alt="<?php the_title_attribute(); ?>"></a>
              <div class="elan-person__body">
                <h3 class="elan-person__name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <p class="elan-person__role"><?php echo esc_html(function_exists('elan_specialist_meta') ? elan_specialist_meta($specialist_id, 'role') : ''); ?></p>
                <p class="elan-person__bio"><?php echo esc_html(get_the_excerpt()); ?></p>
              </div>
            </article>
          <?php endwhile; ?>
        </div>
        <?php wp_reset_postdata(); ?>
        <div style="text-align:center;margin-top:32px"><a class="elan-text-link" href="<?php echo esc_url(elan_theme_specialists_url()); ?>">Meet all specialists →</a></div>
      <?php endif; ?>
    </div>
  </section>

  <section class="elan-section elan-section--soft" id="pricing">
    <div class="elan-shell">
      <div class="elan-section__head"><p class="elan-kicker">Invest in yourself</p><h2 class="elan-title"><?php echo esc_html(elan_mod('packages_title', 'Curated Packages for Every Skin & Self-Care Goal')); ?></h2></div>
      <?php if ($packages_query->have_posts()): ?>
        <div class="elan-pricing">
          <?php while ($packages_query->have_posts()): $packages_query->the_post(); ?>
            <?php
            $package_id = get_the_ID();
            $price = function_exists('elan_package_meta') ? elan_package_meta($package_id, 'price') : '';
            $features = get_post_meta($package_id, 'elan_package_features', true);
            $featured = function_exists('elan_package_meta') && elan_package_meta($package_id, 'featured') === '1';
            ?>
            <article class="elan-price <?php echo $featured ? 'elan-price--featured' : ''; ?>">
              <?php if ($featured): ?><div class="elan-price__badge">Most loved</div><?php endif; ?>
              <div class="elan-price__name"><?php the_title(); ?></div>
              <div class="elan-price__amount"><?php echo esc_html($price); ?></div>
              <p class="elan-price__summary"><?php echo esc_html(get_the_excerpt()); ?></p>
              <?php if (is_array($features)): ?><ul><?php foreach ($features as $feature): ?><li><?php echo esc_html($feature); ?></li><?php endforeach; ?></ul><?php endif; ?>
              <a class="elan-button <?php echo $featured ? '' : 'elan-button--ghost'; ?>" href="#contact">Book now</a>
            </article>
          <?php endwhile; ?>
        </div>
        <?php wp_reset_postdata(); ?>
      <?php endif; ?>
    </div>
  </section>

  <section class="elan-section elan-section--white">
    <div class="elan-shell">
      <div class="elan-section__head"><p class="elan-kicker">Kind words</p><h2 class="elan-title">From Our Clients</h2></div>
      <?php if ($testimonials_query->have_posts()): ?>
        <div class="elan-testimonials">
          <?php while ($testimonials_query->have_posts()): $testimonials_query->the_post(); ?>
            <blockquote class="elan-quote"><p><?php echo esc_html(wp_strip_all_tags(get_the_content())); ?></p><cite>— <?php echo esc_html(function_exists('elan_testimonial_client') ? elan_testimonial_client(get_the_ID()) : get_the_title()); ?></cite></blockquote>
          <?php endwhile; ?>
        </div>
        <?php wp_reset_postdata(); ?>
      <?php endif; ?>
    </div>
  </section>

  <section class="elan-visit" id="contact">
    <img class="elan-visit__image" src="<?php echo esc_url($images['visit']); ?>" alt="Maison Élan lounge detail">
    <div class="elan-visit__content">
      <div>
        <p class="elan-kicker">Visit us</p>
        <h2 class="elan-title"><?php echo esc_html(elan_mod('visit_title', 'We Can’t Wait to Welcome You')); ?></h2>
        <ul class="elan-contact-list">
          <li>⌖ <?php echo esc_html(elan_theme_business_detail('address', '123 Belleview Avenue, Toronto, ON M5R 2L3')); ?></li>
          <li>☎ <?php echo esc_html(elan_theme_business_detail('phone', '(647) 555-1234')); ?></li>
          <li>◎ <?php echo esc_html(elan_theme_business_detail('instagram', '@maison.elan.studio')); ?></li>
        </ul>
      </div>
      <div class="elan-visit__action">
        <a class="elan-button" href="mailto:<?php echo esc_attr(elan_theme_business_detail('email', 'hello@example.com')); ?>">Schedule your visit</a>
        <p>Book your consultation and begin your beauty journey.</p>
      </div>
    </div>
  </section>
</main>

<?php get_footer(); ?>
