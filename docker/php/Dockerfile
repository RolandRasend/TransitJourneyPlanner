FROM php:8.5-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-install \
    intl \
    pdo_mysql \
    zip

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mkdir -p /root/.config/symfony-cli \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

RUN git config --global user.email "robert.thieme.2003@gmail.com" \
    && git config --global user.name "Robert Thieme"

EXPOSE 9000
CMD ["php-fpm"]
