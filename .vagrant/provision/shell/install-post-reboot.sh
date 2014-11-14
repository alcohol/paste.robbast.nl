#!/usr/bin/env bash

pacman --sync --noprogressbar --quiet --noconfirm --needed \
    nginx redis php php-fpm
pacman --sync --noprogressbar --quiet --noconfirm --needed \
    php-xcache php-mcrypt php-intl php-apcu xdebug
