<?php

namespace TestApp\ImageDecorator\Infrastructure;

use PDO;
use TestApp\ImageDecorator\Domain\CreateTagToImageRepository;
use TestApp\Shared\Infrastructure\ImageDBConnector;

class ImageInMySql implements CreateTagToImageRepository
{
    private ImageDBConnector $imageDBConnector;

    public function __construct(ImageDBConnector $imageDBConnector)
    {
        $this->imageDBConnector = $imageDBConnector;
    }

    public function getImageDetails(string $imageRename)
    {
        $stmt = $this->imageDBConnector->pdo()->prepare(
            'SELECT image_path, image_name, image_rename, image_extension, tags, description FROM images WHERE image_rename = :image_rename'
        );
        $stmt->bindValue("image_rename", $imageRename);
        $stmt->execute();
        $imageDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $imageDetails = 0 === count($imageDetails) ? null : $imageDetails[0];

        $imageDetails["tags"] = json_decode($imageDetails["tags"], true);

        return $imageDetails;
    }

    public function createTag(string $imageRename, array $imageDetails, string $newTag)
    {
        // echo 'helllllooooooo Mysql!' . PHP_EOL;
        $actualTags = $imageDetails["tags"];
        array_push($actualTags, $newTag);
        // var_dump($actualTags);
        $tags = json_encode($actualTags);

        $stmt = $this->imageDBConnector->pdo()->prepare(
            'UPDATE images SET tags = :tags WHERE image_rename = :image_rename'
        );
        $stmt->bindValue("tags", $tags);
        $stmt->bindValue("image_rename", $imageRename);
        $stmt->execute();
    }
}