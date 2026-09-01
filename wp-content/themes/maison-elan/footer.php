<footer class="elan-footer">
  <div class="elan-shell elan-footer__inner">
    <a class="elan-brand" href="<?php echo esc_url(home_url('/')); ?>">Maison Élan</a>
    <nav class="elan-footer__nav" aria-label="Footer navigation">
      <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
      <a href="<?php echo esc_url(elan_theme_services_url()); ?>">Services</a>
      <a href="<?php echo esc_url(elan_theme_specialists_url()); ?>">Specialists</a>
      <a href="<?php echo esc_url(elan_theme_pricing_url()); ?>">Pricing</a>
      <a href="<?php echo esc_url(elan_theme_studio_url()); ?>">Studio</a>
      <a href="<?php echo esc_url(elan_contact_url()); ?>">Contact</a>
    </nav>
    <div class="elan-footer__meta">© <?php echo esc_html(date('Y')); ?> Maison Élan</div>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
