<?php
get_header();

$hero_image = 'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=1800&q=85';
$specialists_query = new WP_Query([
    'post_type' => 'elan_specialist',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => ['menu_order' => 'ASC', 'date' => 'ASC'],
]);
?>

<main class="elan-specialists-page">
  <section class="elan-specialists-hero" aria-labelledby="specialists-page-title">
    <img class="elan-specialists-hero__image" src="<?php echo esc_url($hero_image); ?>" alt="Maison Élan studio interior">
    <div class="elan-specialists-hero__overlay"></div>
    <div class="elan-shell elan-specialists-hero__content">
      <h1 id="specialists-page-title">Specialists</h1>
      <p>Expert hands. Personalized care.</p>
    </div>
  </section>

  <section class="elan-specialists-intro elan-section elan-section--white">
    <div class="elan-shell">
      <div class="elan-specialists-intro__copy">
        <p class="elan-kicker">Meet our specialists</p>
        <h2 class="elan-title">Beauty is personal. So is our approach.</h2>
        <p>Our team of highly trained professionals is here to help you look and feel your absolute best.</p>
      </div>

      <?php if ($specialists_query->have_posts()): ?>
        <div class="elan-specialists-directory">
          <?php while ($specialists_query->have_posts()): $specialists_query->the_post(); ?>
            <?php
            $specialist_id = get_the_ID();
            $role = elan_specialist_meta($specialist_id, 'role', 'Beauty Specialist');
            ?>
            <article class="elan-specialist-card">
              <a class="elan-specialist-card__image-link" href="<?php the_permalink(); ?>" aria-label="View <?php the_title_attribute(); ?> profile">
                <img class="elan-specialist-card__image" src="<?php echo esc_url(elan_specialist_image($specialist_id)); ?>" alt="<?php the_title_attribute(); ?>">
              </a>
              <div class="elan-specialist-card__body">
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <p class="elan-specialist-card__role"><?php echo esc_html($role); ?></p>
                <p class="elan-specialist-card__bio"><?php echo esc_html(get_the_excerpt()); ?></p>
                <a class="elan-specialist-card__link" href="<?php the_permalink(); ?>">View profile <span aria-hidden="true">→</span></a>
              </div>
            </article>
          <?php endwhile; ?>
        </div>
      <?php else: ?>
        <p class="elan-specialists-empty">Our specialist profiles are being prepared.</p>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </section>

  <section class="elan-specialists-cta">
    <div class="elan-shell elan-specialists-cta__inner">
      <div>
        <p class="elan-kicker elan-kicker--light">Personalized care</p>
        <h2>Not sure who to book with?</h2>
        <p>Tell us what you would like to focus on and we will guide you to the right specialist and treatment.</p>
      </div>
      <a class="elan-button elan-button--light" href="<?php echo esc_url(elan_contact_url()); ?>">Book a consultation</a>
    </div>
  </section>
</main>

<?php get_footer(); ?>
