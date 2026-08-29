FROM wordpress:6-php8.3-apache

# Bake the custom Maison Élan theme into the WordPress image.
# The official WordPress entrypoint copies /usr/src/wordpress into
# /var/www/html when the application volume is initialized.
COPY wp-content/themes/maison-elan /usr/src/wordpress/wp-content/themes/maison-elan

EXPOSE 80

# Railway terminates HTTPS at its reverse proxy and forwards requests to
# Apache over HTTP. WordPress otherwise thinks the request is insecure and
# emits http:// asset URLs, which browsers block as mixed content.
# Only provide this default when WORDPRESS_CONFIG_EXTRA was not explicitly
# configured by the deployment environment.
#
# Railway can also start recent php/Apache images with more than one Apache
# MPM enabled. mod_php requires mpm_prefork, so normalize the enabled MPMs
# before handing control back to WordPress' official entrypoint.
CMD ["bash", "-lc", "if [ -z \"${WORDPRESS_CONFIG_EXTRA:-}\" ]; then export WORDPRESS_CONFIG_EXTRA='if (!empty($_SERVER[\"HTTP_X_FORWARDED_PROTO\"]) && strpos($_SERVER[\"HTTP_X_FORWARDED_PROTO\"], \"https\") !== false) { $_SERVER[\"HTTPS\"] = \"on\"; }'; fi; a2dismod mpm_event mpm_worker >/dev/null 2>&1 || true; rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.*; a2enmod mpm_prefork >/dev/null 2>&1 || true; exec docker-entrypoint.sh apache2-foreground"]
