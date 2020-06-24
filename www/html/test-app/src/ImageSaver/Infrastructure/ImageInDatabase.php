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

    public function isImageInRedis(string $imagePath, string $imageName, string $imageExtension, string $filterAdded)
    {
        $imageName = $filterAdded === "" ? "$imageName.$imageExtension" : "$imageName-$filterAdded.$imageExtension";
        $isImageInRedis = $this->imageDBConnector->redis()->exists($imageName) === 1;
        return  $isImageInRedis;
    }

    public function imageSavedInRedis(string $imagePath, string $imageName, string $imageExtension, string $filterAdded, string $imageDescription)
    {
        $isFilterAppliedToImage = $filterAdded !== "" ? true : false;

        $image = $isFilterAppliedToImage ? "$imageName-$filterAdded.$imageExtension" : "$imageName.$imageExtension";
        $imageName = $isFilterAppliedToImage ? "$imageName-$filterAdded" : $imageName;
        $tag = $isFilterAppliedToImage ? array($filterAdded) : array();

        $imageMetadata = array(
            'image_path' => $imagePath,
            'image_name' => $imageName,
            'image_extension' => $imageExtension,
            'tag' => $tag,
            'description' => $imageDescription
        );

        $this->imageDBConnector->redis()->set($image,json_encode($imageMetadata));
    }

    public function isImageInDB(string $imagePath, string $imageName, string $imageExtension, string $filterAdded)
    {
        $imageName = $filterAdded === "" ? $imageName : "$imageName-$filterAdded";
        $stmt = $this->imageDBConnector->pdo()->prepare(
            'SELECT image_path, image_name, image_extension FROM images WHERE image_path = :image_path AND image_name = :image_name AND image_extension = :image_extension'
        );
        $stmt->bindValue("image_path", $imagePath);
        $stmt->bindValue("image_name", $imageName);
        $stmt->bindValue("image_extension", $imageExtension);
        $stmt->execute();
        $imageInDB = $stmt->fetchAll();

        $imageInDB = 0 === count($imageInDB) ? null : $imageInDB;

        return $imageInDB;
    }

    public function imageSavedInMySQL(string $imagePath, string $imageName, string $imageExtension, string $filterAdded)
    {
        $isFilterAppliedToImage = $filterAdded !== "" ? true : false;

        $imageName = $isFilterAppliedToImage ? "$imageName-$filterAdded" : $imageName;
        $tag = $isFilterAppliedToImage ? array($filterAdded) : array();
        
        $tag = json_encode($tag);
        $stmt = $this->imageDBConnector->pdo()->prepare(
            'INSERT INTO images(image_path, image_name, image_extension, tags) VALUES (:image_path, :image_name, :image_extension, :tags)'
        );
        $stmt->bindValue("image_path", $imagePath);
        $stmt->bindValue("image_name", $imageName);
        $stmt->bindValue("image_extension", $imageExtension);
        $stmt->bindValue("tags", $tag);
        $stmt->execute();

        echo 'aqui guardaremos la imagen: falta implementarlo' . PHP_EOL;
        echo 'esto ha guardado redis de la image: ' . PHP_EOL;
        var_dump(   $this->imageDBConnector->redis()->get($imageName)  );
        echo 'Estas son todas las keys en Redis: ' . PHP_EOL;
        var_dump(   $this->imageDBConnector->redis()->keys('*')  );
    }
}