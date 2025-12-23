<<<<<<< HEAD
#!/usr/bin/env bash
docker compose up -d
symfony server:start -d

if [ "$1" = "--init" ]; then
    echo "Install dependencies"
    composer install
    echo "Create Database"
    bin/console doctrine:database:create
    bin/console doctrine:schema:update --force
    bin/console doctrine:migrations:migrate
    echo "Install Assets"
    bin/console assets:install
    echo "Clear cache..."
    bin/console cache:clear
fi

echo "Done!"

=======
#!/usr/bin/bash
docker compose up -d
symfony server:start -d
>>>>>>> ae5afe9df6bba8cff89586d0da02ca0c97c284e6
