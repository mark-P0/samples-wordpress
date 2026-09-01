<?php
get_header();

$address = elan_theme_business_detail('address', '123 Belleview Avenue, Toronto, ON M5R 2L3');
$phone = elan_theme_business_detail('phone', '(647) 555-1234');
$email = elan_theme_business_detail('email', 'hello@example.com');
$instagram = elan_theme_business_detail('instagram', '@maison.elan.studio');
?>
<main class="elan-studio-page">
  <section class="elan-page-hero" aria-labelledby="studio-title">
    <img class="elan-page-hero__image" src="https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1800&q=85" alt="Warm and serene Maison Élan studio interior">
    <div class="elan-shell elan-page-hero__content">
      <p class="elan-kicker">Visit Maison Élan</p>
      <h1 id="studio-title">Studio</h1>
      <p>A serene space designed for your beauty and well-being.</p>
    </div>
  </section>

  <section class="elan-section elan-section--white">
    <div class="elan-shell elan-studio-intro">
      <div>
        <p class="elan-kicker">Our studio</p>
        <h2 class="elan-title">Thoughtful care, from the moment you arrive</h2>
        <p class="elan-copy">Maison Élan was created as a calm retreat from the pace of everyday life. Every treatment is considered, every detail intentional, and every visit centered around what feels right for you.</p>
        <p class="elan-copy">Our specialists combine personal consultation, refined technique and a quiet approach to care so your experience feels as restorative as the results.</p>
      </div>
      <img class="elan-studio-intro__image" src="https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=1400&q=85" alt="Maison Élan reception area">
    </div>
  </section>

  <section class="elan-section elan-section--soft">
    <div class="elan-shell">
      <div class="elan-section__head">
        <p class="elan-kicker">Visit us</p>
        <h2 class="elan-title">Everything you need for your visit</h2>
      </div>
      <div class="elan-studio-details">
        <div class="elan-studio-detail"><span class="elan-studio-detail__label">Address</span><p><?php echo esc_html($address); ?></p></div>
        <div class="elan-studio-detail"><span class="elan-studio-detail__label">Hours</span><p>Monday–Friday · 10 AM–7 PM<br>Saturday · 10 AM–6 PM<br>Sunday · Closed</p></div>
        <div class="elan-studio-detail"><span class="elan-studio-detail__label">Phone</span><p><a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a></p></div>
        <div class="elan-studio-detail"><span class="elan-studio-detail__label">Email & social</span><p><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a><br><?php echo esc_html($instagram); ?></p></div>
      </div>
    </div>
  </section>

  <section class="elan-section elan-section--white" id="contact">
    <div class="elan-shell elan-contact-panel">
      <div>
        <p class="elan-kicker">Get in touch</p>
        <h2 class="elan-title">We’re here for you.</h2>
        <p class="elan-copy">Have a question or want to plan your next treatment? Send us a message and our studio team will help you find the right next step.</p>
        <p><a class="elan-text-link" href="mailto:<?php echo esc_attr($email); ?>">Email the studio →</a></p>
      </div>
      <form class="elan-contact-form" action="mailto:<?php echo esc_attr($email); ?>" method="get" enctype="text/plain">
        <label>Name<input type="text" name="name" autocomplete="name" required></label>
        <label>Email<input type="email" name="email" autocomplete="email" required></label>
        <label>How can we help?<textarea name="message" required></textarea></label>
        <button class="elan-button" type="submit">Send message</button>
      </form>
    </div>
  </section>

  <section class="elan-map-placeholder" aria-label="Studio location">
    <div><strong>Maison Élan</strong><span><?php echo esc_html($address); ?></span></div>
  </section>
</main>
<?php get_footer(); ?>
