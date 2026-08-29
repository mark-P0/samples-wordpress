FROM wordpress:6-php8.3-apache

# Bake the custom Maison Élan theme into the WordPress image.
# The official WordPress entrypoint copies /usr/src/wordpress into
# /var/www/html when the application volume is initialized.
COPY wp-content/themes/maison-elan /usr/src/wordpress/wp-content/themes/maison-elan

EXPOSE 80

# Railway can start recent php/Apache images with more than one Apache MPM
# enabled. mod_php requires mpm_prefork, so normalize the enabled MPMs at
# runtime before handing control back to WordPress' official entrypoint.
# Calling docker-entrypoint.sh explicitly also keeps the WordPress bootstrap
# behavior intact if the platform changes/overrides the inherited entrypoint.
CMD ["bash", "-lc", "a2dismod mpm_event mpm_worker >/dev/null 2>&1 || true; rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.*; a2enmod mpm_prefork >/dev/null 2>&1 || true; exec docker-entrypoint.sh apache2-foreground"]
