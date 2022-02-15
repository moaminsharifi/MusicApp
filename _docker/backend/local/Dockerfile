FROM php:8.1-fpm

# INSTALL VIA APT-GET
RUN apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends \
    apt-utils \
    vim \
    default-mysql-client \
    cron \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    zip \
    libmagickwand-dev \
    libboost-all-dev \
    curl \
    git \
    supervisor
# INSTALL VIA DOCKER-PHP-EXT
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd

RUN pecl install imagick && \
    docker-php-ext-enable imagick


RUN docker-php-ext-install -j$(nproc) pdo_mysql

RUN docker-php-ext-install gd && \
    docker-php-ext-configure gd --with-freetype --with-jpeg

RUN docker-php-ext-install zip

# Install Swoole extension
RUN pecl install swoole
RUN docker-php-ext-enable swoole


 INSTALL VIA MANUALLY
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# INSTALL Yasd
RUN cd /tmp  &&\
    git clone https://github.com/swoole/yasd.git && \
    cd /tmp/yasd && \
    phpize --clean && \
    phpize && \
    ./configure && \
    make clean && \
    make && \
    make install && \
     cd /
# CONFIGURE PHP
RUN mkdir -p /usr/local/etc/php/conf-available
COPY resources/custom-php.ini /usr/local/etc/php/conf-available/custom-php.ini
RUN ln -s /usr/local/etc/php/conf-available/custom-php.ini /usr/local/etc/php/conf.d/


WORKDIR /var/www/html
EXPOSE 8000

CMD ["sh", "/usr/local/bin/startup.sh"]