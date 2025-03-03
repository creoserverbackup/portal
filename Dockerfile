ARG UID=1000
ARG GID=1000

# Install dependencies only when needed
#FROM node:14 AS deps
#
#WORKDIR /var/www/html
#
#COPY package*.json ./
#RUN npm install

# Rebuild the source code only when needed
#FROM node:14 AS builder
#
#WORKDIR /var/www/html
#
#COPY --from=deps /var/www/html ./node_modules
#COPY . .
#
#
#RUN npm run prod

# PHP
FROM php:8.1-fpm

# Set working directory
WORKDIR /var/www

# Copy composer.lock and composer.json
#COPY composer.lock composer.json ./

#COPY --from=builder /var/www/public ./public

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    nano \
    curl

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN     pecl install uploadprogress \
        && docker-php-ext-enable uploadprogress \
        && chmod uga+x /usr/local/bin/install-php-extensions && sync \
        && install-php-extensions bcmath \
                bz2 \
                calendar \
                exif \
                fileinfo \
                gd \
                gettext \
                imagick \
                imap \
                intl \
                ldap \
                mcrypt \
                memcached \
                mongodb \
                mysqli \
                opcache \
                pdo_mysql \
                redis \
                soap \
                sysvsem \
                sysvshm \
                xmlrpc \
                xsl \
                zip

# Install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]









#FROM php:8.1-fpm-alpine
#ARG UID
#ARG GID
#
#ENV UID=${UID}
#ENV GID=${GID}
#
##RUN addgroup -g ${GID} --system laravel
##RUN adduser -G laravel --system -D -s /bin/sh -u ${UID} laravel
##
##RUN sed -i "s/user = www-data/user = laravel/g" /usr/local/etc/php-fpm.d/www.conf
##RUN sed -i "s/group = www-data/group = laravel/g" /usr/local/etc/php-fpm.d/www.conf
#
#
#WORKDIR /var/www/html
#
#COPY composer.json ./
#COPY composer.lock ./
#
#COPY --from=builder /var/www/html/public ./public
#
#ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
#
#RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
#    && pecl install uploadprogress \
#    && docker-php-ext-enable uploadprogress \
#    && apk del .build-deps $PHPIZE_DEPS \
#    && chmod uga+x /usr/local/bin/install-php-extensions && sync \
#    && install-php-extensions bcmath \
#            bz2 \
#            calendar \
#            exif \
#            gd \
#            gettext \
#            imagick \
#            imap \
#            intl \
#            ldap \
#            mcrypt \
#            memcached \
#            mongodb \
#            mysqli \
#            opcache \
#            pdo_mysql \
#            redis \
#            soap \
#            sysvsem \
#            sysvshm \
#            xmlrpc \
#            xsl \
#            zip \
#    &&  echo -e "\n opcache.enable=1 \n opcache.enable_cli=1 \n opcache.memory_consumption=128 \n opcache.interned_strings_buffer=8 \n opcache.max_accelerated_files=4000 \n opcache.revalidate_freq=60 \n opcache.fast_shutdown=1" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
#    &&  echo -e "\n xdebug.remote_enable=1 \n xdebug.remote_host=localhost \n xdebug.remote_port=9000" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    &&  echo -e "\n xhprof.output_dir='/var/tmp/xhprof'" >> /usr/local/etc/php/conf.d/docker-php-ext-xhprof.ini \
#    && cd ~ \
## Install msmtp - To Send Mails on Production & Development
#    && apk add msmtp
#
## Install composer
#COPY --from=composer /usr/bin/composer /usr/bin/composer
#
#
#RUN addgroup -g ${GID} --system laravel
#RUN adduser -G laravel --system -D -s /bin/sh -u ${UID} laravel
#
#RUN sed -i "s/user = www-data/user = laravel/g" /usr/local/etc/php-fpm.d/www.conf
#RUN sed -i "s/group = www-data/group = laravel/g" /usr/local/etc/php-fpm.d/www.conf
#
#COPY . .
#
#RUN composer install --no-interaction --optimize-autoloader --no-dev
## Optimizing Configuration loading
#RUN php artisan config:cache
## Optimizing Route loading
#RUN php artisan route:cache
## Optimizing View loading
#RUN php artisan view:cache
#
## Expose port 9000 and start php-fpm server
#EXPOSE 9000
#CMD ["php-fpm"]
