# CODE RETREAT APP


## Running

````bash
cp .env.example .env
docker-compose up
````

### Inicializar symfony dentro del contenedor

````bash
# revisar el nombre del contenedor
docer ps

# reemplazar el penúltimo argumento por el nombre del contenedor
docker exec -it tetris_apache_1 bash

# Lo anterior abre una consola dentro del contenedor
cd /var/www/back
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
````


