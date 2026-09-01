<footer class="elan-footer">
  <div class="elan-shell elan-footer__inner">
    <a class="elan-brand" href="<?php echo esc_url(home_url('/')); ?>">Maison Élan</a>
    <nav class="elan-footer__nav" aria-label="Footer navigation">
      <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
      <a href="<?php echo esc_url(elan_services_url()); ?>">Services</a>
      <a href="<?php echo esc_url(function_exists('elan_specialists_url') ? elan_specialists_url() : home_url('/#specialists')); ?>">Specialists</a>
      <a href="<?php echo esc_url(home_url('/#pricing')); ?>">Pricing</a>
      <a href="<?php echo esc_url(home_url('/#studio')); ?>">Studio</a>
      <a href="<?php echo esc_url(elan_contact_url()); ?>">Contact</a>
    </nav>
    <div class="elan-footer__meta">© <?php echo esc_html(date('Y')); ?> Maison Élan</div>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
