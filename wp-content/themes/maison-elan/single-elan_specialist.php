<?php
get_header();

while (have_posts()): the_post();
    $specialist_id = get_the_ID();
    $role = elan_specialist_meta($specialist_id, 'role', 'Beauty Specialist');
    $experience = elan_specialist_meta($specialist_id, 'experience', 'Experienced');
    $philosophy = elan_specialist_meta($specialist_id, 'philosophy', 'Thoughtful care, tailored to you.');
    $specialties = get_post_meta($specialist_id, 'elan_specialist_specialties', true);
    $education = get_post_meta($specialist_id, 'elan_specialist_education', true);
    $specialties = is_array($specialties) ? $specialties : [];
    $education = is_array($education) ? $education : [];
    $studio_image = 'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=1400&q=85';
?>

<main class="elan-specialist-profile">
  <div class="elan-shell elan-specialist-profile__breadcrumbs" aria-label="Breadcrumb">
    <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
    <span>/</span>
    <a href="<?php echo esc_url(elan_specialists_url()); ?>">Specialists</a>
    <span>/</span>
    <span><?php the_title(); ?></span>
  </div>

  <section class="elan-specialist-profile__hero">
    <div class="elan-shell elan-specialist-profile__hero-inner">
      <div class="elan-specialist-profile__portrait-wrap">
        <img class="elan-specialist-profile__portrait" src="<?php echo esc_url(elan_specialist_image($specialist_id)); ?>" alt="<?php the_title_attribute(); ?>">
      </div>
      <div class="elan-specialist-profile__intro">
        <h1><?php the_title(); ?></h1>
        <p class="elan-specialist-profile__role"><?php echo esc_html($role); ?></p>
        <div class="elan-specialist-profile__rule"></div>
        <div class="elan-specialist-profile__bio"><?php the_content(); ?></div>
        <div class="elan-specialist-profile__signature" aria-hidden="true"><?php the_title(); ?></div>
        <a class="elan-button" href="<?php echo esc_url(elan_contact_url()); ?>">Book with <?php echo esc_html(get_the_title()); ?></a>
      </div>
    </div>
  </section>

  <section class="elan-specialist-profile__facts">
    <div class="elan-shell elan-specialist-profile__facts-grid">
      <article>
        <div class="elan-specialist-profile__fact-icon" aria-hidden="true">✣</div>
        <h2>Specialties</h2>
        <?php if ($specialties): ?>
          <ul>
            <?php foreach ($specialties as $specialty): ?><li><?php echo esc_html($specialty); ?></li><?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </article>
      <article>
        <div class="elan-specialist-profile__fact-icon" aria-hidden="true">◷</div>
        <h2>Experience</h2>
        <p><?php echo esc_html($experience); ?><br>in Beauty &amp; Wellness</p>
      </article>
      <article>
        <div class="elan-specialist-profile__fact-icon" aria-hidden="true">◇</div>
        <h2>Philosophy</h2>
        <p><?php echo esc_html($philosophy); ?></p>
      </article>
    </div>
  </section>

  <section class="elan-specialist-profile__education elan-section elan-section--white">
    <div class="elan-shell elan-specialist-profile__education-grid">
      <div>
        <p class="elan-kicker">Professional background</p>
        <h2 class="elan-title">Education &amp; Certifications</h2>
        <?php if ($education): ?>
          <ul>
            <?php foreach ($education as $credential): ?><li><?php echo esc_html($credential); ?></li><?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
      <img src="<?php echo esc_url($studio_image); ?>" alt="Maison Élan treatment room">
    </div>
  </section>

  <section class="elan-specialists-cta">
    <div class="elan-shell elan-specialists-cta__inner">
      <div>
        <p class="elan-kicker elan-kicker--light">Begin your visit</p>
        <h2>Personalized care starts with a conversation.</h2>
        <p>Book a consultation and we will shape your treatment plan around your goals.</p>
      </div>
      <a class="elan-button elan-button--light" href="<?php echo esc_url(elan_contact_url()); ?>">Book consultation</a>
    </div>
  </section>
</main>

<?php endwhile; ?>
<?php get_footer(); ?>
