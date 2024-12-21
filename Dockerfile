FROM ubuntu:24.04

ENV TZ=America/Sao_Paulo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
    && echo $TZ > /etc/timezone \
    && apt-get update -y \
    && apt-get install -y --no-install-recommends \
    software-properties-common \
    && LC_ALL=C.UTF-8 \
    && add-apt-repository -y ppa:ondrej/php \
    && apt-get update -y \
    && apt-get install -y --no-install-recommends \
    zip \
    unzip \
    apache2 \
    cron \
    lynx \
    mysql-client \
    nano \
    php-pear \
    php8.4 \
    php8.4-cli \
    php8.4-mbstring \
    php8.4-mysql \
    php8.4-opcache \
    php8.4-xdebug \
    php8.4-pdo \
    supervisor

EXPOSE 80

CMD ["apachectl", "-D", "FOREGROUND"]
