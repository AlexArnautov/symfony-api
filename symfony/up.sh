docker-compose up -d
docker-compose exec webserver composer install;
docker-compose exec webserver php bin/console doctrine:migrations:migrate -n;


