FROM letsdockerize/laradock-php-fpm:2.4-7.3
RUN set -xe; \
    apt-get update -yqq && \
    pecl channel-update pecl.php.net && \
    apt-get install -yqq \
    apt-utils \
    libzip-dev zip unzip


RUN docker-php-ext-configure zip --with-libzip && \
    docker-php-ext-install zip 

# Install the PHP pdo_mysql extention
RUN docker-php-ext-install pdo_mysql \
    # Install the PHP pdo_pgsql extention
    # Install the PHP gd library
    && docker-php-ext-configure gd \
    --with-jpeg-dir=/usr/lib \
    --with-freetype-dir=/usr/include/freetype2 && \
    docker-php-ext-install gd


ARG INSTALL_PHPREDIS=true
RUN if [ ${INSTALL_PHPREDIS} = true ]; then \
    # Install Php Redis Extension
    printf "\n" | pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis \
    ;fi


ARG INSTALL_SWOOLE=true
RUN if [ ${INSTALL_SWOOLE} = true ]; then \
    # Install Php Swoole Extension
    if [ $(php -r "echo PHP_MAJOR_VERSION;") = "5" ]; then \
    pecl install swoole-2.0.10; \
    else \
    if [ $(php -r "echo PHP_MINOR_VERSION;") = "0" ]; then \
    pecl install swoole-2.2.0; \
    else \
    pecl install swoole; \
    fi \
    fi && \
    docker-php-ext-enable swoole \
    && php -m | grep -q 'swoole' \
    ;fi





COPY ./laravel.ini /usr/local/etc/php/conf.d
COPY ./xlaravel.pool.conf /usr/local/etc/php-fpm.d/

RUN php-fpm  -m
# Clean up
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    rm /var/log/lastlog /var/log/faillog

RUN usermod -u 1000 www-data

WORKDIR /var/www

#CMD ["php-fpm"]
#
#EXPOSE 9000
