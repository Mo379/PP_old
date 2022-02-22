#seed
FROM ubuntu:20.04
#setup
ENV TZ=Europe/Minsk
ENV DEBIAN_FRONTEND=noninteractive 
RUN apt-get update
RUN apt-get -y install apt-utils vim curl apache2 apache2-utils git p7zip-full p7zip-rar
RUN a2enmod headers 
RUN a2enmod rewrite
RUN apt-get -y install software-properties-common
RUN add-apt-repository ppa:ondrej/php
RUN apt-get  update
RUN apt-get -y install php7.2
RUN apt-get -y install php7.2-json php7.2-cgi php7.2-cli php7.2-common php7.2-curl php7.2-dev php7.2-mbstring php7.2-mysql php7.2-opcache php7.2-zip php7.2-mysqli php7.2-xdebug 
RUN apt-get -y install php7.2-xml
RUN update-alternatives --set php /usr/bin/php7.2
#installing composer
RUN curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
RUN php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
#app periferals
ADD ./includes/APP_apache.conf /etc/apache2/sites-available/000-default.conf 
ADD ./includes/php.ini /etc/php/7.2/apache2/php.ini
WORKDIR /var/www/html 
RUN composer require
RUN composer dump-autoload
#Groups, Permissions and Ownership
#expose and run
EXPOSE 80 
CMD ["apache2ctl", "-D", "FOREGROUND"]
