<?php

namespace TestApp\ImageDecorator\Infrastructure;

use TestApp\ImageDecorator\Domain\CreateDescriptionToImageRepository;
use TestApp\ImageDecorator\Domain\CreateTagToImageRepository;
use TestApp\ImageDecorator\Domain\DeleteTagToImageRepository;
use TestApp\Shared\Infrastructure\ImageDBConnector;

class ImageInRedis implements CreateTagToImageRepository, DeleteTagToImageRepository, CreateDescriptionToImageRepository
{
    private ImageDBConnector $imageDBConnector;

    public function __construct(ImageDBConnector $imageDBConnector)
    {
        $this->imageDBConnector = $imageDBConnector;
    }

    public function getImageDetails(string $imageRename)
    {
        $imageDetails = $this->imageDBConnector->redis()->exists($imageRename) === 1 ? $this->imageDBConnector->redis()->get($imageRename) : null;
        // var_dump($imageDetails);
        $imageDetails = $imageDetails === null ? null : json_decode($imageDetails, true);
        return $imageDetails;
    }

    public function createTag(string $imageRename, array $imageDetails, string $newTag)
    {   
        array_push($imageDetails["tags"], $newTag);

        $updateImageDetails = json_encode($imageDetails);

        $this->imageDBConnector->redis()->set($imageRename, $updateImageDetails);
    }

    public function deleteTag(string $imageRename, array $imageDetails, string $deleteTag)
    {
        if (($key = array_search($deleteTag, $imageDetails["tags"])) !== false) {
            unset($imageDetails["tags"][$key]);
        }
        // var_dump($imageDetails);
        $updateImageDetails = json_encode($imageDetails);

        $this->imageDBConnector->redis()->set($imageRename, $updateImageDetails);
    }

    public function createDescription(string $imageRename, array $imageDetails, string $newDescription)
    {
        $imageDetails['description'] = $newDescription;

        $updateImageDetails = json_encode($imageDetails);

        $this->imageDBConnector->redis()->set($imageRename, $updateImageDetails);
    }
}