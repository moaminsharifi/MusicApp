FROM ubuntu:20.04
# install base packages
RUN apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends \
    apt-utils \
    vim \
    default-mysql-client \
    cron \
    zip \
    curl \
    git \
    software-properties-common
RUN apt-get update
RUN add-apt-repository universe
RUN add-apt-repository ppa:ondrej/php
RUN apt install -yq --no-install-recommends php8.1
RUN apt install -yq  php8.1-mbstring php8.1-mysql php8.1-curl php8.1-xml
#INSTALL VIA MANUALLY
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# install swoole
RUN add-apt-repository ppa:openswoole/ppa -y
RUN apt install -y php8.1-openswoole
RUN phpenmod -s cli openswoole
# PROVISING OF VOLUMES
VOLUME ["/var/www/html"]


# PREPARE CONTAINER START
RUN chown -R www-data:www-data /var/www/html
COPY resources/startup.sh /usr/local/bin/startup.sh
RUN chmod 774 /usr/local/bin/startup.sh


WORKDIR /var/www/html
EXPOSE 8000

CMD ["sh", "/usr/local/bin/startup.sh"]