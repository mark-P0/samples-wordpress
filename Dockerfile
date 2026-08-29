FROM wordpress:6-php8.3-apache

# Bake the custom Maison Élan theme into the WordPress image.
# The official WordPress entrypoint copies /usr/src/wordpress into
# /var/www/html when the application volume is initialized.
COPY wp-content/themes/maison-elan /usr/src/wordpress/wp-content/themes/maison-elan

EXPOSE 80
