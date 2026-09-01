FROM wordpress:6-php8.3-apache

# Bake the custom Maison Élan theme and content plugin into the WordPress image.
# The official WordPress entrypoint copies /usr/src/wordpress into
# /var/www/html when the application volume is initialized.
COPY wp-content/themes/maison-elan /usr/src/wordpress/wp-content/themes/maison-elan
COPY wp-content/plugins/maison-elan-content /usr/src/wordpress/wp-content/plugins/maison-elan-content

EXPOSE 80

# Railway mounts persistent volumes as root. WordPress/Apache writes uploads
# as www-data, so repair uploads ownership at startup. Existing application
# volumes may already contain an initialized WordPress install, so explicitly
# sync our Git-managed theme/plugin into the live wp-content directory.
# Remove the legacy Specialists MU plugin from earlier feature-branch deploys.
# Also normalize Apache MPMs because mod_php requires mpm_prefork.
CMD ["bash", "-lc", "mkdir -p /var/www/html/wp-content/uploads /var/www/html/wp-content/themes/maison-elan /var/www/html/wp-content/plugins/maison-elan-content; rm -f /var/www/html/wp-content/mu-plugins/maison-elan-specialists.php; cp -rf /usr/src/wordpress/wp-content/themes/maison-elan/. /var/www/html/wp-content/themes/maison-elan/; cp -rf /usr/src/wordpress/wp-content/plugins/maison-elan-content/. /var/www/html/wp-content/plugins/maison-elan-content/; chown -R www-data:www-data /var/www/html/wp-content/uploads /var/www/html/wp-content/themes/maison-elan /var/www/html/wp-content/plugins/maison-elan-content; a2dismod mpm_event mpm_worker >/dev/null 2>&1 || true; rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.*; a2enmod mpm_prefork >/dev/null 2>&1 || true; exec docker-entrypoint.sh apache2-foreground"]
