FROM php:8.1-apache

RUN apt-get update && apt-get install -y git vim
RUN docker-php-ext-install mysqli

WORKDIR /var/www/
RUN rm -rf *

COPY ./www .
RUN chmod 777 /var/www/html/upload

COPY ./FLAG .
RUN mv ./FLAG /FLAG

# RUN rm -rf * && git clone https://github.com/qwerty-tommy/php_battle.git .  # 개발 효율을 위해 깃보단 로컬로 작업함

EXPOSE 80
