
# CODE RETREAT TETRIS

[Presentación](https://docs.google.com/presentation/d/1XCL4lNwWEPwftEukpVhnWJyqULx7-kJz1trw9r8E5bQ/edit?usp=sharing)

Este proyecto está basado en dos contenedores, uno de base de datos y uno de apache y php (ver el archivo *docker-compose.yml*). La aplicación como tal se encuentra en las carpetas back y front, que se mapean a volúmenes dentro del contenedor de apache.

## Iniciar la máquina

````bash
cp .env.example .env
docker-compose up
````

### Nota para windows

Debido a que docker necesita un kernel de Linux o Unix para funcionar, en windows es necesario utilizar una máquina virtual. Afortunadamente el *Docker Toolbox* facilita esto.

Para iniciar la máquina

``````bash
docker-machine start
# y detenerla
docker-machine stop
``````


Para conectarse a la máquina

``````bash
docker-machine ssh
``````

Para conocer la ip local de la máquina

``````bash
docker-machine ip
``````

En este caso los puertos de docker serán relativos a esta ip.

Para configurar los volúmenes (carpetas compartidas) también nos toca hacer algo más de trabajo. El truco es especificar la ruta correcta como se ve dentro de la máquina virtual en el archivo *.env* mencionado en la sección anterior. Por defecto la máquina mapea 

``````
C:\Users -> /c/Users
``````

Si la carpeta del proyecto está bajo `C:\Users` basta con colocar un guión al inicio de la ruta y enderezar todos los guiones, por ejemplo

``````
C:\Users\Ana\chevere\code_retreat\tetris\front -> /c/Users/Ana/chevere/code_retreat/tetris/front
``````

Si la carpeta se encuentra en otra ruta, debemos crear una nueva carpeta compartida en VirtualBox. Para verificar la ruta se puede acceder por ssh a la máquina. 

### Inicializar symfony dentro del contenedor

````bash
# revisar el nombre del contenedor
docker ps

# reemplazar el penúltimo argumento por el nombre del contenedor
docker exec -it tetris_apache_1 bash

# Lo anterior abre una consola dentro del contenedor
cd /var/www/back
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
````

### Acceder a la aplicación

Dentro del navegador coloque la url:

Para Mac o Linux:

http://localhost:8081

Para Windows utilizar la ip de la máquina virtual

http://192.168.99.100:8081

### Tareas comúnes

Desde la consola al interior del contenedor (ver arriba)

````bash
# correr pruebas de unidad
php bin/phpunit

# reiniciar la base de datos
php bin/console doctrine:database:drop
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
````

NOTA: El proyecto tiene instalada la libreria [Doctrine Test Bundle](https://github.com/dmaicher/doctrine-test-bundle) que corre todas las pruebas dentro de una transacción y hace roll back al final; por lo tanto no es necesario preocuparse por reinicializar la base de datos en las pruebas de unidad.
