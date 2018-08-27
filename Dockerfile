FROM php:7.1-cli

RUN apt-get update
RUN apt-get -y install zlib1g-dev
RUN docker-php-ext-install zip pdo_mysql

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php --install-dir=/usr/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"

COPY ./ /var/www/todoapp
WORKDIR /var/www/todoapp

RUN /usr/bin/composer install

# Keys should be imported from outside for production apps
# For demo it's ok to generate them here
RUN openssl genrsa -passout pass:12345 -out config/jwt/private-with-pass.pem -aes256 4096
RUN openssl rsa -passin pass:12345 -in config/jwt/private-with-pass.pem -out config/jwt/private.pem
RUN openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "public/"]
#CMD ["bin/console", "server:start", "127.0.0.1:8080"]
