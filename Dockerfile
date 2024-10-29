# Use the official PHP image
FROM php:8.0-apache

# Copy your script to the web server directory
COPY index.php /var/www/html/

# Expose port 80
EXPOSE 80
