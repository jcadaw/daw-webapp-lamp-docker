# daw-webapp-lamp-docker
Creación de un esqueleto para una aplicación web usando `Apache`, `PHP` y `MySQL` sobre `docker`, usando imágenes oficiales de la distribución `alpine` (en caso de estar disponible).
<div id="top"></div>

<!-- TABLE OF CONTENTS -->
<details>
  <summary>Índice</summary>
  <ol>
    <li>
      <a href="#sobre-el-repo">Sobre el repo</a>
    </li>
    <li>
      <a href="#estructura-de-archivos">Estructura de archivos</a>
      <ul>
        <li><a href="#directorio-app">Directorio "./app"</a></li>
        <li><a href="#directorio-docker">Directorio "./docker"</a></li>
        <ul>
          <li><a href="#fichero-docker-composeyml">fichero docker-compose.yml</a></li>
          <li><a href="#fichero-env">fichero .env</a></li>
          <li><a href="#directorio-apache">directorio apache</a></li>
          <li><a href="#directorio-mysql">directorio MySQL</a></li>
          <li><a href="#directorio-php">directorio PHP</a></li>
          <li><a href="#directorio-phpmyadmin">directorio phpMyAdmin</a></li>
        </ul>
      </ul>
      <li><a href="#imágenes-de-docker-usadas">Imágenes de docker usadas</a></li>
      <li><a href="#empezando">Empezando</a></li>
      <ul>
        <li><a href="#prerrequisitos">Prerrequisitos</a></li>
        <li><a href="#instalación">Instalación</a></li>
        <li><a href="#uso">Uso</a></li>
      </ul>
      <li><a href="#contacto">Contacto</a></li>
    </li>
  </ol>
</details>



<!-- ABOUT THE PROJECT -->
## Sobre el repo

El objetivo de este repositorio es servir de armazón para desarrollar una aplicación web usando: `Apache`, `PHP` y `MySQL` con `docker`. También contiene un script para facilitar el uso de `composer` (PHP) a través de docker. Hace uso de `phpMyAdmin`, que puede ser 
accedido a través de http://localhost:8080

`Docker compose` creará 4 contenedores: 
* 1 contenedor con PHP-fpm  8.0 (distribución alpine)
* 1 contenedor con phpMyAdmin-fpm  5.1.1 (distribución alpine)
* 1 contenedor con apache 2.4 (distribución alpine)
* 1 contenedor con MySQL 8

<p align="right">(<a href="#top">volver arriba</a>)</p>

## Estructura de archivos

La estructura de los directorios/archivos es la siguiente: 

```
.
├── app
│   ├── composer
│   ├── index.html
│   └── index.php
├── docker
│   ├── apache
│   │   ├── Dockerfile
│   │   └── localhost.conf
│   ├── mysql
│   │   └── dump
│   │       └── ejemplo.sql
│   ├── php
│   │   └── Dockerfile
│   ├── phpmyadmin
│   │   └── Dockerfile
│   ├── .env
│   └── docker-compose.yml
└── README.md

```
<p align="right">(<a href="#top">volver arriba</a>)</p>

### Directorio app

En `app` es donde crearemos nuestra aplicación web (obviamente es configurable).
Dentro existe un par de ejemplos: 
* `index.html` un ejemplo de web sencilla en html
* `index.php` un ejemplo de aplicación sencilla en PHP que realiza una conexión a la base de datos y muestra unos cuantos datos de ejemplo.

Además, existe un script ejecutable `composer` que sirve para ejecutar composer desde un contenedor docker, de manera que no es necesario realizar la instalación en nuestra máquina. El uso desde el directorio `app`, sería: 
```sh
.../app/$ ./composer OPCIONES_COMPOSER
```

Por ejemplo: 
```sh
.../app/$ ./composer init
```

<p align="right">(<a href="#top">volver arriba</a>)</p>

### Directorio docker

En `docker` es donde está toda la configuración de docker para la creación de los contenedores. 

Desde este directorio ejecutaremos el siguiente comando para levantar los contenedores:

```docker
docker-compose up
```


#### fichero docker-compose.yml

En este fichero tenemos la configuración de docker compose, información sobre los servicios, volúmenes y redes que se van a configurar. 

En este repositorio se va a configurar un contenedor para `PHP`, otro para `apache` y otro para `MySQL`. Todos en contenedores docker usando la distribución `alpine`. 

Para determinar las versiones de las imágenes en las que nos vamos a basar, usamos el fichero de configuración `.env`

Creamos dos redes: 
  - `daw-red-www`: en esta red están los contenedores `apache` , `php` y `phpmyadmin`
  - `daw-red-mysql`: en esta red están los contenedores `mysql` , `php` y `phpmyadmin`

Creamos los volúmenes: 
  - `daw-vol-mysql`: volumen para la gestión de la base de datos 
  - `daw-vol-phpmyadmin`: volumen donde está la aplicación phpMyAdmin

<p align="right">(<a href="#top">volver arriba</a>)</p>

#### fichero .env

Fichero en el que configurar algunos parámetros para la creación de los contenedores. Actualmente las variables permitidas son: 

```
APACHE_VERSION=2.4.51
MYSQL_VERSION=8.0.27
PHP_VERSION=8.1.0RC5
PHPMYADMIN_VERSION=5.1.1

MYSQL_ROOT_PASSWORD=rpass
MYSQL_DATABASE=daw_db
MYSQL_USER=daw_dba
MYSQL_PASSWORD=1234

DIR_PROYECTO=../app
```

`DIR_PROYECTO` es el directorio donde está nuestra aplicación web, será nuestro `DocumentRoot` dentro del `VirtualHost` que crearemos en el contenedor de apache

<p align="right">(<a href="#top">volver arriba</a>)</p>

#### directorio apache

Contiene el `Dockerfile` para construir la imagen, basicamente copiamos el fichero de ejemplo de `VirtualHost` (localhost.conf) e incluimos dicho fichero en el `httpd.conf` para que arranque automáticamente esa configuración.


El fichero `localhost.conf` contiene una configuración de ejemplo en el que se redireccionan (mediante un proxy) todas las peticiones `php` al contenedor que ejecuta php-fpm

#### directorio MySQL

Contiene el directorio `dump` en el cual hemos introducido un script que se ejecutará cuando se cree la base de datos. El script se llama `ejemplo.sql` y crea una tabla con unos pocos datos de ejemplo.

#### directorio PHP

Contiene el `Dockerfile` necesario para construir la imagen. De primeras únicamente instalamos la extensión `mysqli`.

#### directorio PHPMyAdmin

Contiene el `Dockerfile` necesario para construir la imagen. Cambia el puerto por defecto
de php-fpm para que use el 9001 para usar el contenedor de phpMyAdmin, el 9000 será usado para las peticiones PHP por defecto.


## Imágenes de docker usadas

Nos hemos basado en las imágenes oficiales de cada uno de los servicios. Usamos además las distribuciones `alpine`.

* [apache](https://hub.docker.com/_/httpd)
* [php](https://hub.docker.com/_/php)
* [phpMyAdmin](https://hub.docker.com/_/phpmyadmin)
* [MySQL](https://hub.docker.com/_/mysql)

<p align="right">(<a href="#top">volver arriba</a>)</p>



<!-- GETTING STARTED -->
## Empezando

Si quieres crear un proyecto `xAMP` usando contenedores `docker`, sigue las siguientes instrucciones:

### Prerrequisitos

Tienes que tener `docker` y `docker-compose` instalado en tu máquina

### Instalación

1. Clona el repo
   ```sh
   git clone https://github.com/jcadaw/daw-webapp-lamp-docker.git 
   ```
2. Ve al directorio `./docker`
   ```sh
   cd docker
   ```
3. Lanza `docker-compose`
   ```sh
   docker-compose up -d
   ```

<p align="right">(<a href="#top">volver arriba</a>)</p>



<!-- USAGE EXAMPLES -->
### Uso

A partir de aquí, siénte libre para crear tu aplicación web.

Para entrar en phpmyadmin simplemente usa http://localhost:8080

<p align="right">(<a href="#top">volver arriba</a>)</p>


## Contacto

José Carlos Álvarez - jca at alzago punto es

Repo: [https://github.com/jcadaw/daw-webapp-lamp-docker.git](https://github.com/jcadaw/daw-webapp-lamp-docker.git)

<p align="right">(<a href="#top">back to top</a>)</p>
