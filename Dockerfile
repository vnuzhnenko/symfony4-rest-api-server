FROM php:7.1-cli

RUN docker-php-ext-install pdo_mysql

COPY ./ /var/www/todoapp
WORKDIR /var/www/todoapp

# Keys should be imported from outside for production apps
# For demo it's ok to generate them here
RUN openssl genrsa -passout pass:12345 -out config/jwt/private-with-pass.pem -aes256 4096
RUN openssl rsa -passin pass:12345 -in config/jwt/private-with-pass.pem -out config/jwt/private.pem
RUN openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "public/"]
#CMD ["bin/console", "server:start", "127.0.0.1:8080"]
