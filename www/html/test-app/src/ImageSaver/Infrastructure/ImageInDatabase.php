<?php

namespace TestApp\ImageSaver\Infrastructure;

use TestApp\ImageSaver\Domain\ImageRepository;
use TestApp\Shared\Infrastructure\ImageDBConnector;

class ImageInDatabase implements ImageRepository
{
    private ImageDBConnector $imageDBConnector;

    public function __construct(ImageDBConnector $imageDBConnector)
    {
        $this->imageDBConnector = $imageDBConnector;
    }

    public function isImageInRedis(string $image)
    {
        $isImageInRedis = $this->imageDBConnector->redis()->exists($image) === 1;
        return  $isImageInRedis;
    }

    public function imageSavedInRedis(string $image, string $tag, string $imageDescription)
    {
        $imageMetadata = array(
            'tag' => array($tag),
            'description' => $imageDescription
        );

        $this->imageDBConnector->redis()->set($image,json_encode($imageMetadata));
    }

    public function isImageInDB(string $imageName)
    {
        $stmt = $this->imageDBConnector->pdo()->prepare(
            'SELECT image, tags FROM images WHERE image = :image'
        );
        $stmt->bindValue("image", $imageName);
        $stmt->execute();
        $imageInDB = $stmt->fetchAll();

        $imageInDB = 0 === count($imageInDB) ? null : $imageInDB;

        return $imageInDB;
    }

    public function imageSavedInMySQL(string $imageName, string $tags)
    {
        $tag = json_encode(array($tags));
        $stmt = $this->imageDBConnector->pdo()->prepare(
            'INSERT INTO images(image, tags) VALUES (:imageName, :tags)'
        );
        $stmt->bindValue("imageName", $imageName);
        $stmt->bindValue("tags", $tag);
        $stmt->execute();

        echo 'aqui guardaremos la imagen: falta implementarlo' . PHP_EOL;
        echo 'esto ha guardado redis de la image: ' . PHP_EOL;
        var_dump(   $this->imageDBConnector->redis()->get($imageName)  );
        echo 'Estas son todas las keys en Redis: ' . PHP_EOL;
        var_dump(   $this->imageDBConnector->redis()->keys('*')  );
    }
}