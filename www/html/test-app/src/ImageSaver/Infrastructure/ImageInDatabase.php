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

    public function isImageInRedis(string $imageRename)
    {        
        $isImageInRedis = $this->imageDBConnector->redis()->exists($imageRename) === 1;
        return  $isImageInRedis;
    }

    public function imageSavedInRedis(string $imagePath, string $imageName, string $imageRename, string $imageExtension, string $filterAdded, string $imageDescription)
    {
        $isFilterAppliedToImage = $filterAdded !== "" ? true : false;

        $tag = $isFilterAppliedToImage ? array($filterAdded) : array();

        $imageMetadata = array(
            'image_path' => $imagePath,
            'image_name' => $imageName,
            'image_rename' => $imageRename,
            'image_extension' => $imageExtension,
            'tags' => $tag,
            'description' => $imageDescription
        );

        $this->imageDBConnector->redis()->set($imageRename,json_encode($imageMetadata));
    }

    public function isImageInDB(string $imageRename)
    {
        $stmt = $this->imageDBConnector->pdo()->prepare(
            'SELECT image_rename FROM images WHERE image_rename = :image_rename'
        );
        $stmt->bindValue("image_rename", $imageRename);
        $stmt->execute();
        $imageInDB = $stmt->fetchAll();

        $imageInDB = 0 === count($imageInDB) ? null : $imageInDB;

        return $imageInDB;
    }

    public function imageSavedInMySQL(string $imagePath, string $imageName, string $imageRename, string $imageExtension, string $filterAdded)
    {
        $isFilterAppliedToImage = $filterAdded !== "" ? true : false;

        $tag = $isFilterAppliedToImage ? array($filterAdded) : array();
        
        $tag = json_encode($tag);
        $description = '';

        $stmt = $this->imageDBConnector->pdo()->prepare(
            'INSERT INTO images(image_path, image_name, image_rename, image_extension, tags, description) VALUES (:image_path, :image_name, :image_rename, :image_extension, :tags, :description)'
        );
        $stmt->bindValue("image_path", $imagePath);
        $stmt->bindValue("image_name", $imageName);
        $stmt->bindValue("image_rename", $imageRename);
        $stmt->bindValue("image_extension", $imageExtension);
        $stmt->bindValue("tags", $tag);
        $stmt->bindValue("description", $description);
        $stmt->execute();
    }

    public function documentSavedInELK(string $imagePath, string $imageName, string $imageRename, string $imageExtension, string $filterAdded, string $imageDescription)
    {
        $tags = $filterAdded === '' ? [] : [ $filterAdded ];

        $imageDocument = [
            'index' => ImageDBConnector::INDEXNAME,
            'id' => $imageRename,
            'body' => [
                'image_path' => $imagePath,
                'image_name' => $imageName,
                'image_rename' => $imageRename,
                'image_extension' => $imageExtension,
                'tags' => $tags,
                'description' => $imageDescription
            ]
        ];

        $this->imageDBConnector->elasticsearch()->index($imageDocument);
    }

}