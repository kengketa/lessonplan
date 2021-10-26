set -e

echo "Deploying application..."

#enter maintenance mode
(php artisan down --message 'The application si being updated, please try again')

    git pull origin master

    composer install
    npm run prod

#exit maintenace
php artisan up

echo "code updated."


