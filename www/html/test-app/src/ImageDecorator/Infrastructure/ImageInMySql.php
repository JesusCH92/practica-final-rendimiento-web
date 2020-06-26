<?php

namespace TestApp\ImageDecorator\Infrastructure;

use PDO;
use TestApp\ImageDecorator\Domain\CreateDescriptionToImageRepository;
use TestApp\ImageDecorator\Domain\CreateTagToImageRepository;
use TestApp\ImageDecorator\Domain\DeleteTagToImageRepository;
use TestApp\Shared\Infrastructure\ImageDBConnector;

class ImageInMySql implements CreateTagToImageRepository, DeleteTagToImageRepository, CreateDescriptionToImageRepository
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
        $actualTags = $imageDetails["tags"];

        array_push($actualTags, $newTag);

        $tags = json_encode($actualTags);

        $stmt = $this->imageDBConnector->pdo()->prepare(
            'UPDATE images SET tags = :tags WHERE image_rename = :image_rename'
        );

        $stmt->bindValue("tags", $tags);
        $stmt->bindValue("image_rename", $imageRename);
        $stmt->execute();
    }

    public function deleteTag(string $imageRename, array $imageDetails, string $deleteTag)
    {
        $actualTags = $imageDetails["tags"];

        if (($key = array_search($deleteTag, $actualTags)) !== false) {
            unset($actualTags[$key]);
        }

        $tags = json_encode($actualTags);

        $stmt = $this->imageDBConnector->pdo()->prepare(
            'UPDATE images SET tags = :tags WHERE image_rename = :image_rename'
        );

        $stmt->bindValue("tags", $tags);
        $stmt->bindValue("image_rename", $imageRename);
        $stmt->execute();
    }

    public function createDescription(string $imageRename, array $imageDetails, string $newDescription)
    {
        $stmt = $this->imageDBConnector->pdo()->prepare(
            'UPDATE images SET description = :description WHERE image_rename = :image_rename'
        );

        $stmt->bindValue("description", $newDescription);
        $stmt->bindValue("image_rename", $imageRename);
        $stmt->execute();
    }
}