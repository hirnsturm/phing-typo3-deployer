FROM typo3gmbh/php74

#
# Install composer
#
RUN EXPECTED_SIGNATURE=$(curl -s -o - https://composer.github.io/installer.sig); \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"; \
    ACTUAL_SIGNATURE=$(php -r "echo hash_file('SHA384', 'composer-setup.php');"); \
    if [ "$EXPECTED_SIGNATURE" != "$ACTUAL_SIGNATURE" ]; then \
        echo 'ERROR: Invalid installer signature'; \
    else \
        php composer-setup.php --version=2.0.4 --filename=composer --install-dir=/usr/local/bin/; \
    fi \
 && rm composer-setup.php

#
# User configuration
#
ARG uid=1000
RUN useradd -m -u "${uid}" -g www-data developer
USER developer
