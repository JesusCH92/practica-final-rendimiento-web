# 📖 MPWAR (Rendimiento en Aplicaciones web)

[![Actions Status](https://github.com/rubencougil/mpwar-rendimiento-docker/workflows/Docker%20Image%20CI/badge.svg)](https://github.com/rubencougil/mpwar-rendimiento-docker/actions)

## Configuración del Entorno para desplegar el proyecto

Seguid los siguientes pasos:
1. Desde la carpeta principal `practica-final-rendimiento-web`, ejecutar:
```bash
docker-compose up -d
```
2. Una vez se hayan descargado todos los servicios, instalar las dependencias con lo siguiente:
    * Acceder al servicio de php y ejecutar:
    ```bash
    docker-compose exec php bash
    ```
    * Acceder a la carpeta del proyecto:
    ```bash
    cd /code/test-app
    ```
    * Por último ejecutar:
    ```bash
    composer install
    ```
3. Acceder a la BD desde workbench (o interfaz grafica de su preferencia). El username es **`user`** y el password es **`password`**
![ConfigMySQLWorkbench](https://i.imgur.com/xWKnPP1.png)

4. Ejecutar el siguiente script en la base de datos:
```SQL
USE db;

CREATE TABLE `db`.`images` (
  `id_images` INT NOT NULL AUTO_INCREMENT,
  `image_path` VARCHAR(255),
  `image_name` VARCHAR(255) NULL,
  `image_rename` VARCHAR(255) NULL,
  `image_extension` VARCHAR(5),
  `tags` VARCHAR(255),
  `description` VARCHAR(255) NULL,
  PRIMARY KEY (`id_images`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE UNIQUE INDEX image_rename
ON images (image_rename);
```
5. Levantar los consumidores individualmente abriendo 5 terminales desde la carpeta principal del proyecto y ejecutar para cada uno de ellos lo siguiente:
    * Abrir la primera terminal y ejecutar:
    ```bash
    docker-compose exec php bash
    ```
    * Acceder a la carpeta del proyecto:
    ```bash
    cd /code/test-app
    ```
    * Por último ejecutar el consumidor SepiaFilterConsumer.php:
    ```bash
    php SepiaFilterConsumer.php
    ```
    ![SepiaFilter](https://i.imgur.com/IRaBgLL.png)
    * Abrir la segunda terminal y ejecutar:
    ```bash
    docker-compose exec php bash
    ```
    * Acceder a la carpeta del proyecto:
    ```bash
    cd /code/test-app
    ```
    * Por último ejecutar el consumidor BlackAndWhiteFilterConsumer.php:
    ```bash
    php BlackAndWhiteFilterConsumer.php
    ```
    ![BlackNWhiteFilter](https://i.imgur.com/XAhEc6L.png)
    * Abrir la tercera terminal y ejecutar:
    ```bash
    docker-compose exec php bash
    ```
    * Acceder a la carpeta del proyecto:
    ```bash
    cd /code/test-app
    ```
    * Por último ejecutar el consumidor FlipVerticalFilterConsumer.php:
    ```bash
    php FlipVerticalFilterConsumer.php
    ```
    ![FlipVerticalFilter](https://i.imgur.com/UqedmaY.png)
    * Abrir la cuarta terminal y ejecutar:
    ```bash
    docker-compose exec php bash
    ```
    * Acceder a la carpeta del proyecto:
    ```bash
    cd /code/test-app
    ```
    * Por último ejecutar el consumidor FlipHorizontalFilterConsumer.php:
    ```bash
    php FlipHorizontalFilterConsumer.php
    ```
    ![FlipHorizontalFilter](https://i.imgur.com/TT1h7yi.png)
    * Abrir la quinta terminal y ejecutar:
    ```bash
    docker-compose exec php bash
    ```
    * Acceder a la carpeta del proyecto:
    ```bash
    cd /code/test-app
    ```
    * Por último ejecutar el consumidor DarkBlueFilterConsumer.php:
    ```bash
    php DarkBlueFilterConsumer.php
    ```
    ![DarkBlueFilter](https://i.imgur.com/2kghPvi.png)

## Manejo de la App

1. Una vez se tenga la configuración del entorno correctamente, accedemos al endpoint **`http://localhost:8080/`** y accedemos a la app.
![HomePage](https://i.imgur.com/c3VGt0K.png)

2. **`Upload Images`**: en esta funcionalidad podremos cargar las imagenes con extensiones **`.png`** o **`.jpg`**. Cada vez que se clique el boton **`Send photos`** se enviarán concurrentemente un número máximo de 10 imagenes, de tal manera que si hay más de 10 imagenes se tendrá que clicar otra vez el boton para subir las imagenes que quedan (de forma concurrente), una vez todas hayan sido enviadas, la **`dropzone`** quedará vacía para enviar otras imagenes (aunque si lo desea también se puede seguir cargando imagenes aunque no este vacía).
![UploadImages](https://i.imgur.com/jIvTr3g.png)

3. **`Edit Images`**: esta funcionalidad muestra todas las fotos subidas (con sus respectivos filtros añadidos) a las cuales se le podrá añadir/eliminar tags y una descripción.
![EditImages](https://i.imgur.com/TgcLt2H.png)

4. **`Searcher Images in ElasticSearch`**: esta funcionalidad encontrará resultados según el input de entrada de forma difusa en el nombre de los tags y en la descripción, además de encontrar search-as-you-type, en la imagen podemos ver el resultado de buscar la palabra **`word`**
![SearcherImages](https://i.imgur.com/Fc27hz2.png)

## Instalación

- [Instalar Docker](https://docs.docker.com/install/)
- [Instalar docker-compose](https://docs.docker.com/compose/install/)
- Exportar las variables de entorno necesarias para el [correcto funcionamiento de Blackfire](https://blackfire.io/docs/integrations/docker) y crear el alias `blackfire-curl`. Esta configuración se encuentra en el archivo `.env`
- Ejecutar `docker-compose up -d`

## Servicios

- Mysql: 3306
- Nginx: 8080
- Redis: 6379
- RabbitMQ: 15672
- Elasticsearch: 9200
- Kibana: 5601
- Blackfire
