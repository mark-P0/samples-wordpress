FROM wordpress:6-php8.3-apache

# Bake the custom Maison Élan theme into the WordPress image.
# The official WordPress entrypoint copies /usr/src/wordpress into
# /var/www/html when the application volume is initialized.
COPY wp-content/themes/maison-elan /usr/src/wordpress/wp-content/themes/maison-elan

EXPOSE 80

# Railway mounts persistent volumes as root. WordPress/Apache writes uploads
# as www-data, so repair the uploads directory ownership at container startup.
# Also normalize Apache MPMs because mod_php requires mpm_prefork.
CMD ["bash", "-lc", "mkdir -p /var/www/html/wp-content/uploads; chown -R www-data:www-data /var/www/html/wp-content/uploads; a2dismod mpm_event mpm_worker >/dev/null 2>&1 || true; rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.*; a2enmod mpm_prefork >/dev/null 2>&1 || true; exec docker-entrypoint.sh apache2-foreground"]
