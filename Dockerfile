FROM composer:latest as build

# Install Composer
WORKDIR /app/
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Copy composer.json and composer.lock files (if available) to the root directory
COPY composer.json ./
COPY composer.lock ./

# Copy over private dependencies
COPY packages/ ./packages/

# Install dependencies using Composer
RUN composer install --no-scripts --no-autoloader

# Generate the autoloader file
RUN composer dump-autoload --optimize

# Start the Wordpress build
FROM wordpress:6.2

# Copy root composer files over to wordpress container
COPY --from=build /app /var/www