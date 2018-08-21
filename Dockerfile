FROM php:7.1-cli

RUN docker-php-ext-install pdo_mysql

COPY ./ /var/www/todoapp
WORKDIR /var/www/todoapp

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "public/"]
#CMD ["bin/console", "server:start", "127.0.0.1:8080"]
