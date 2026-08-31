<?php
get_header();

$images = [
  'hero' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=1800&q=85',
  'studio' => 'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=1400&q=85',
  'visit' => 'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1400&q=85',
];

$services = [
  ['Signature Facial', 'Deeply nourishing facials tailored to your skin’s unique needs.', 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=900&q=85', 'signature-facial'],
  ['Skin Renewal', 'Advanced treatments to refine texture, tone and clarity.', 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?auto=format&fit=crop&w=900&q=85', 'renewal-peel'],
  ['Brows & Lashes', 'Expert shaping, tinting and lifting for effortless beauty.', 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=900&q=85', 'brow-styling'],
  ['Massage Ritual', 'Restorative massages to relax the body and calm the mind.', 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=900&q=85', 'restorative-massage'],
];
$specialists = [
  ['Élise Laurent', 'Lead Aesthetician', 'Specializing in advanced skincare and holistic skin health.', 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=700&q=85'],
  ['Camille Dubois', 'Brow & Lash Artist', 'Expert in brow design, lash lifts and enhancing your natural features.', 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=700&q=85'],
  ['Sophie Martin', 'Wellness Therapist', 'Focused on therapeutic massage and overall wellbeing.', 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=700&q=85'],
];
$packages = [
  ['Essential Glow', '$195', 'Perfect for maintaining healthy, radiant skin.', ['Signature Facial', 'LED Light Therapy', 'Custom Skincare Plan'], false],
  ['Radiance Renewal', '$395', 'Advanced treatments for visible renewal and glow.', ['Skin Renewal Treatment', 'LED Light Therapy', 'Hydrating Mask', 'Post-Treatment Care Kit'], true],
  ['Ultimate Indulgence', '$595', 'The ultimate luxury experience from head to toe.', ['Signature Facial', 'Massage Ritual (60 min)', 'LED Light Therapy', 'Scalp & Hand Treatment'], false],
];
$quotes = [
  ['Maison Élan is my sanctuary. The attention to detail, the ambiance, and the results are unmatched. I leave every appointment feeling refreshed and glowing.', 'Léa B.'],
  ['The team is incredibly knowledgeable and makes you feel so cared for. My skin has never looked better.', 'Marie P.'],
  ['Luxury, professionalism and real results. I wouldn’t trust anyone else with my skin and brows.', 'Julie D.'],
];
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
      <div class="elan-services">
        <?php foreach ($services as $service): ?>
          <?php
          $service_post = get_page_by_path($service[3], OBJECT, 'elan_service');
          $service_url = $service_post instanceof WP_Post ? get_permalink($service_post) : elan_services_url();
          ?>
          <article class="elan-card elan-service">
            <img class="elan-service__image" src="<?php echo esc_url($service[2]); ?>" alt="">
            <div class="elan-service__body"><h3 class="elan-service__title"><?php echo esc_html($service[0]); ?></h3><p class="elan-service__desc"><?php echo esc_html($service[1]); ?></p><a class="elan-text-link" href="<?php echo esc_url($service_url); ?>">Learn more →</a></div>
          </article>
        <?php endforeach; ?>
      </div>
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
      <div class="elan-specialists">
        <?php foreach ($specialists as $person): ?>
          <article class="elan-card elan-person"><img class="elan-person__image" src="<?php echo esc_url($person[3]); ?>" alt="<?php echo esc_attr($person[0]); ?>"><div class="elan-person__body"><h3 class="elan-person__name"><?php echo esc_html($person[0]); ?></h3><p class="elan-person__role"><?php echo esc_html($person[1]); ?></p><p class="elan-person__bio"><?php echo esc_html($person[2]); ?></p></div></article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="elan-section elan-section--soft" id="pricing">
    <div class="elan-shell">
      <div class="elan-section__head"><p class="elan-kicker">Invest in yourself</p><h2 class="elan-title"><?php echo esc_html(elan_mod('packages_title', 'Curated Packages for Every Skin & Self-Care Goal')); ?></h2></div>
      <div class="elan-pricing">
        <?php foreach ($packages as $package): ?>
          <article class="elan-price <?php echo $package[4] ? 'elan-price--featured' : ''; ?>"><?php if ($package[4]): ?><div class="elan-price__badge">Most loved</div><?php endif; ?><div class="elan-price__name"><?php echo esc_html($package[0]); ?></div><div class="elan-price__amount"><?php echo esc_html($package[1]); ?></div><p class="elan-price__summary"><?php echo esc_html($package[2]); ?></p><ul><?php foreach ($package[3] as $feature): ?><li><?php echo esc_html($feature); ?></li><?php endforeach; ?></ul><a class="elan-button <?php echo $package[4] ? '' : 'elan-button--ghost'; ?>" href="#contact">Book now</a></article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="elan-section elan-section--white">
    <div class="elan-shell"><div class="elan-section__head"><p class="elan-kicker">Kind words</p><h2 class="elan-title">From Our Clients</h2></div><div class="elan-testimonials"><?php foreach ($quotes as $quote): ?><blockquote class="elan-quote"><p><?php echo esc_html($quote[0]); ?></p><cite>— <?php echo esc_html($quote[1]); ?></cite></blockquote><?php endforeach; ?></div></div>
  </section>

  <section class="elan-visit" id="contact">
    <img class="elan-visit__image" src="<?php echo esc_url($images['visit']); ?>" alt="Maison Élan lounge detail">
    <div class="elan-visit__content"><div><p class="elan-kicker">Visit us</p><h2 class="elan-title"><?php echo esc_html(elan_mod('visit_title', 'We Can’t Wait to Welcome You')); ?></h2><ul class="elan-contact-list"><li>⌖ <?php echo esc_html(elan_mod('address', '123 Belleview Avenue, Toronto, ON M5R 2L3')); ?></li><li>☎ <?php echo esc_html(elan_mod('phone', '(647) 555-1234')); ?></li><li>◎ <?php echo esc_html(elan_mod('instagram', '@maison.elan.studio')); ?></li></ul></div><div class="elan-visit__action"><a class="elan-button" href="mailto:hello@example.com">Schedule your visit</a><p>Book your consultation and begin your beauty journey.</p></div></div>
  </section>
</main>

<?php get_footer(); ?>
